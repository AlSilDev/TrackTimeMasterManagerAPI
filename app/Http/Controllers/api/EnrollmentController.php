<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRunOrderRequest;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\VehicleHistory;
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
        $enrollments_count = DB::table('enrollments AS e')
            ->select(DB::raw('COUNT(e.id) AS count'))
            ->where('event_id', '=', $request->event_id)
            ->get()[0]->count;
        //dd($enrollments_count);
        $validated_data = $request->validated();
        $vehicle_history_id = VehicleHistory::where('vehicle_id', '=', $validated_data['vehicle_id'])->orderBy('created_at', 'desc')->get()[0]['id'];
        //dd($vehicle_history_id);
        $validated_data['vehicle_id'] = $vehicle_history_id;
        $validated_data['enroll_order'] = ++$enrollments_count;
        $validated_data['run_order'] = $validated_data['enroll_order'];
        //dd($validated_data);
        $newEnrollment = Enrollment::create($validated_data);
        return new EnrollmentResource($newEnrollment);
    }

    public function destroy(Enrollment $enrollment)
    {
        if (Carbon::now() <= $enrollment->event->date_end_enrollments)
            $enrollment->delete();

        return new EnrollmentResource($enrollment);
    }

    public function getEventEnrollments(int $eventId)
    {
        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'e.enroll_order', 'e.run_order', 'fd.name AS first_driver_name', 'fd.country AS first_driver_country', 'fd.license_num AS first_driver_license', 'sd.name AS second_driver_name', 'sd.country AS second_driver_country', 'sd.license_num AS second_driver_license', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate', 'vc.name AS vehicle_class', 'vcc.name AS vehicle_category')
                                ->join('driver_history AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('driver_history AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicle_history AS v', 'e.vehicle_id', '=', 'v.id')
                                ->join('vehicle_classes AS vc', 'v.class_id', '=', 'vc.id')
                                ->join('vehicle_categories AS vcc', 'vc.category_id', '=', 'vcc.id')
                                ->where('e.event_id', $eventId)
                                ->orderBy('e.run_order')
                                ->get());
    }

    public function getEventEnrollmentsForAdminVerifications(int $eventId)
    {
        $enrollmentsIdAdminVerified = DB::table('admin_verifications')->pluck('id');

        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'e.enroll_order', 'e.run_order', 'fd.name AS first_driver_name', 'sd.name AS second_driver_name', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate')
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
        //$enrollmentsIdAdminVerified = DB::table('admin_verifications')->pluck('id');
        $approved = 1;

        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'e.enroll_order', 'e.run_order', 'fd.name AS first_driver_name', 'sd.name AS second_driver_name', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate')
                                ->join('driver_history AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('driver_history AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicle_history AS v', 'e.vehicle_id', '=', 'v.id')
                                ->join('admin_verifications AS av', 'av.enrollment_id', '=', 'e.id')
                                ->where('e.event_id', $eventId)
                                ->where('av.verified', $approved)
                                //->whereIn('e.id', $enrollmentsIdAdminVerified)
                                ->orderBy('e.run_order')
                                ->get());
    }

    public function updateRunOrder(Event $event, UpdateEnrollmentRunOrderRequest $request)
    {
        //dd(count($request->request));
        foreach($request->request as $enrollment)
        {
            //dd($enrollment);
            Enrollment::where('id', '=', $enrollment['id'])->update(['run_order' => $enrollment['run_order']]);
        }

        return response()->json(Enrollment::where('event_id', '=', $event['id'])->get());
    }
}
