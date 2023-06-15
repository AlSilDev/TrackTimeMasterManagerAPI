<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRunOrderRequest;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use App\Models\Event;
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
                                ->select('e.id', 'e.event_id', 'e.enroll_order', 'e.run_order', 'fd.name AS first_driver_name', 'sd.name AS second_driver_name', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate')
                                ->join('driver_history AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('driver_history AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicle_history AS v', 'e.vehicle_id', '=', 'v.id')
                                ->where('e.event_id', $eventId)
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
