<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRunOrderRequest;
use App\Http\Resources\EnrollmentResource;
use App\Models\AdminVerification;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\Participant;
use App\Models\TechnicalVerification;
use App\Models\VehicleHistory;
use App\Models\DriverHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'e.enroll_order', 'e.run_order', 'fd.name AS first_driver_name', 'sd.name AS second_driver_name', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate')
                                ->join('driver_history AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('driver_history AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicle_history AS v', 'e.vehicle_id', '=', 'v.id')
                                ->get());
    }

    public function store(StoreEnrollmentRequest $request)
    {
        //$newEnrollment = Enrollment::create($request->validated());
        //dd($request->event_id);
        $validated_data = $request->validated();
        $first_driver_history_id = DriverHistory::where('driver_id', '=', $validated_data['first_driver_id'])->orderBy('created_at', 'desc')->first()['id'];
        //dd($first_driver_history_id);
        $second_driver_history_id = DriverHistory::where('driver_id', '=', $validated_data['second_driver_id'])->orderBy('created_at', 'desc')->first()['id'];
        $vehicle_history_id = VehicleHistory::where('vehicle_id', '=', $validated_data['vehicle_id'])->orderBy('created_at', 'desc')->get()[0]['id'];

        $conflict = DB::table('enrollments AS e')
        ->select(DB::raw('COUNT(e.enroll_order) as count'))
        ->where('event_id', '=', $validated_data['event_id'])
        ->where(function ($query) use ($first_driver_history_id, $second_driver_history_id, $vehicle_history_id) {
            $query->whereIn('e.first_driver_id', [$first_driver_history_id, $second_driver_history_id])
                  ->orWhereIn('e.second_driver_id', [$first_driver_history_id, $second_driver_history_id])
                  ->orWhere('e.vehicle_id', $vehicle_history_id);
        })
        ->orderBy('e.enroll_order', 'desc')
        ->get()[0]->count;

        if($conflict > 0)
        {
            return response('Conflito detetado', 403);
        }

        $enrollments_count = DB::table('enrollments AS e')
            ->select(DB::raw('COUNT(e.enroll_order) as count'))
            ->where('event_id', '=', $request->event_id)
            ->orderBy('e.enroll_order', 'desc')
            ->get()[0]->count;
        //dd($enrollments_count);

        if($enrollments_count != 0)
        {
            $enrollments_countAux = DB::table('enrollments AS e')
            ->select('e.enroll_order')
            ->where('event_id', '=', $request->event_id)
            ->orderBy('e.enroll_order', 'desc')
            ->get()[0]->enroll_order;
            $enrollments_count = $enrollments_countAux;
            //dd(++$enrollments_count);
        }
        //dd(++$enrollments_count);
        //$lastmore1 = ++$enrollments_count;
        //dd(++$enrollments_count->count);
        
        
        //dd($vehicle_history_id);
        $validated_data['vehicle_id'] = $vehicle_history_id;
        $validated_data['first_driver_id'] = $first_driver_history_id;
        $validated_data['second_driver_id'] = $second_driver_history_id;
        $validated_data['enroll_order'] = ++$enrollments_count;
        $validated_data['run_order'] = $validated_data['enroll_order'];
        //dd($validated_data);
        $newEnrollment = Enrollment::create($validated_data);
        return new EnrollmentResource($newEnrollment);
    }

    public function destroy(Enrollment $enrollment)
    {
        $eventId = $enrollment->event->id;
        if (Carbon::now() <= $enrollment->event->date_end_enrollments)
        {
            $enrollment->delete();
            $eventEnrolls = Enrollment::where('event_id', '=', $eventId)->get();
            foreach ($eventEnrolls as $eventEnroll) {
                if ($eventEnroll->id > $enrollment->id){
                    $eventEnroll->run_order -=1;
                    $eventEnroll->save();
                    //dd($eventEnroll->run_order);
                }
            }
        }

        return new EnrollmentResource($enrollment);
    }

    public function getEventEnrollments(int $eventId)
    {
        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'e.enroll_order', 'e.run_order', 'fd.name AS first_driver_name', 'fd.id AS first_driver_id', 'fd.country AS first_driver_country', 'fd.license_num AS first_driver_license', 'fd.phone_num AS first_driver_phone_num', 'sd.name AS second_driver_name', 'sd.id AS second_driver_id', 'sd.country AS second_driver_country', 'sd.license_num AS second_driver_license', 'sd.phone_num AS second_driver_phone_num', 'v.model AS vehicle_model', 'v.vehicle_id AS vehicle_id', 'v.license_plate AS vehicle_license_plate', 'vc.name AS vehicle_class', 'vcc.name AS vehicle_category')
                                ->join('driver_history AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('driver_history AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicle_history AS v', 'e.vehicle_id', '=', 'v.id')
                                ->join('vehicle_classes AS vc', 'v.class_id', '=', 'vc.id')
                                ->join('vehicle_categories AS vcc', 'vc.category_id', '=', 'vcc.id')
                                ->where('e.event_id', $eventId)
                                ->orderBy('e.run_order')
                                ->get());
    }

    public function getEnrollmentsNotAlreadyVerified(int $eventId)
    {
        $enrollmentsIdAdminVerified = DB::table('admin_verifications')->pluck('id');
        $enrollmentsIdTechnicalVerified = DB::table('technical_verifications')->pluck('id');

        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'e.enroll_order', 'e.run_order', 'fd.name AS first_driver_name', 'fd.driver_id AS first_driver_id', 'sd.name AS second_driver_name', 'fd.driver_id AS second_driver_id', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate', 'v.vehicle_id AS vehicle_id')
                                ->join('driver_history AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('driver_history AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicle_history AS v', 'e.vehicle_id', '=', 'v.id')
                                ->where('e.event_id', $eventId)
                                ->whereNotIn('e.id', $enrollmentsIdAdminVerified)
                                ->whereNotIn('e.id', $enrollmentsIdTechnicalVerified)
                                ->orderBy('e.run_order')
                                ->get());
    }

    public function getEventEnrollmentsForAdminVerifications(int $eventId)
    {
        $enrollmentsIdAdminVerified = DB::table('admin_verifications')->pluck('id');

        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'e.enroll_order', 'e.run_order', 'fd.name AS first_driver_name', 'fd.driver_id AS first_driver_id', 'sd.name AS second_driver_name', 'fd.driver_id AS second_driver_id', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate', 'v.vehicle_id AS vehicle_id')
                                ->join('driver_history AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('driver_history AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicle_history AS v', 'e.vehicle_id', '=', 'v.id')
                                ->where('e.event_id', $eventId)
                                ->whereNotIn('e.id', $enrollmentsIdAdminVerified)
                                ->orderBy('e.run_order')
                                ->get());
    }

    public function getEventEnrollmentsForTechnicalVerifications(int $eventId)
    {
        $enrollmentsIdHaveAlreadyTechnicalVerified = DB::table('participants')->pluck('enrollment_id');

        $approved = 1;

        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'e.enroll_order', 'e.run_order', 'fd.name AS first_driver_name', 'fd.driver_id AS first_driver_id', 'sd.name AS second_driver_name', 'sd.driver_id AS second_driver_id', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate', 'v.vehicle_id AS vehicle_id')
                                ->join('driver_history AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('driver_history AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicle_history AS v', 'e.vehicle_id', '=', 'v.id')
                                ->join('admin_verifications AS av', 'av.enrollment_id', '=', 'e.id')
                                ->where('e.event_id', $eventId)
                                ->where('av.verified', $approved)
                                ->whereNotIn('e.id', $enrollmentsIdHaveAlreadyTechnicalVerified)
                                //->whereIn('e.id', $enrollmentsIdAdminVerified)
                                ->orderBy('e.run_order')
                                ->get());
    }

    public function updateRunOrder(Event $event, UpdateEnrollmentRunOrderRequest $request)
    {
        //event_id = $event['id']
        //dd($event['id']);
        $notVerified = 0;
        $eventEnrollsIds = DB::table('enrollments')->where('event_id', '=', $event['id'])->pluck('id');
        $adminVerIds = DB::table('admin_verifications')->whereIn('enrollment_id', $eventEnrollsIds)->pluck('id');
        $technicalVerIds = DB::table('technical_verifications')->whereIn('enrollment_id', $eventEnrollsIds)->pluck('id');
        $participantsIds = DB::table('participants')->whereIn('enrollment_id', $eventEnrollsIds)->pluck('id');

        if($adminVerIds->isEmpty() || $technicalVerIds->isEmpty() || $participantsIds->isEmpty())
        {
            foreach($request->request as $enrollment)
            {
            //dd($enrollment);
            Enrollment::where('id', '=', $enrollment['id'])->update(['run_order' => $enrollment['run_order']]);

            //criar enroll em admin/ technical e participants
            //dd($request);

            //dd($enrollment['id']);
            AdminVerification::create(['enrollment_id' => $enrollment['id'], 'verified' => $notVerified]);

            TechnicalVerification::create(['enrollment_id' => $enrollment['id'], 'verified' => $notVerified]);

            Participant::create(['enrollment_id' => $enrollment['id'], 'first_driver_id' => $enrollment['first_driver_id'], 'second_driver_id' => $enrollment['second_driver_id'], 'vehicle_id' => $enrollment['vehicle_id']]);
            /*$adminVerification = new AdminVerification();
            $adminVerification->enrollment_id = $enrollment['id'];
            $adminVerification->save();

            $technicalVerification = new TechnicalVerification();
            $technicalVerification->enrollment_id = $enrollment['id'];
            $technicalVerification->save();

            $participant = new Participant();
            $participant->enrollment_id = $enrollment['id'];
            $participant->first_driver_id = $enrollment['first_driver_id'];
            $participant->second_driver_id = $enrollment['second_driver_id'];
            $participant->vehicle_id = $enrollment['vehicle_id'];
            $participant->save();*/
            }
        }else{
            foreach($request->request as $enrollment)
            {
                Enrollment::where('id', '=', $enrollment['id'])->update(['run_order' => $enrollment['run_order']]);
            }
        }

        return response()->json(Enrollment::where('event_id', '=', $event['id'])->get());
    }
}
