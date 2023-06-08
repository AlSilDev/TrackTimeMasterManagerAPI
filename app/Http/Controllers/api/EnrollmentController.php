<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
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
        $newEnrollment = Enrollment::create($request->validated());
        return new EnrollmentResource($newEnrollment);
    }

    public function destroy(Enrollment $enrollment)
    {
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
                                ->get());
    }
}
