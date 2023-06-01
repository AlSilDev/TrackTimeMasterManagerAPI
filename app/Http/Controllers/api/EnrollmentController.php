<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentCheckInRequest;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'fd.name AS first_driver_name', 'sd.name AS second_driver_name', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate')
                                ->join('drivers AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('drivers AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicles AS v', 'e.vehicle_id', '=', 'v.id')
                                ->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnrollmentRequest $request)
    {
        $newEnrollment = Enrollment::create($request->validated());
        return new EnrollmentResource($newEnrollment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return new EnrollmentResource($enrollment);
    }

    public function getAllEnrollments()
    {
        $giveCheckIn = 0;
        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'fd.name AS first_driver_name', 'sd.name AS second_driver_name', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate')
                                ->join('drivers AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('drivers AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicles AS v', 'e.vehicle_id', '=', 'v.id')
                                ->where('check_in', $giveCheckIn)
                                ->get());
    }

    public function getAllParticipants()
    {
        $giveCheckIn = 1;
        //return response()->json(DB::table('enrollments')->where('check_in', $giveCheckIn)->get());
        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'fd.name AS first_driver_name', 'sd.name AS second_driver_name', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate')
                                ->join('drivers AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('drivers AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicles AS v', 'e.vehicle_id', '=', 'v.id')
                                ->where('check_in', $giveCheckIn)
                                ->get());
    }

    public function getEventEnrollments(int $eventId)
    {
        $giveCheckIn = 0;
        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'fd.name AS first_driver_name', 'sd.name AS second_driver_name', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate')
                                ->join('drivers AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('drivers AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicles AS v', 'e.vehicle_id', '=', 'v.id')
                                ->where('event_id', $eventId)
                                ->where('check_in', $giveCheckIn)
                                ->get());
    }

    public function getEventParticipants(int $eventId)
    {
        $giveCheckIn = 1;
        return response()->json(DB::table('enrollments AS e')
                                ->select('e.id', 'e.event_id', 'fd.name AS first_driver_name', 'sd.name AS second_driver_name', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate')
                                ->join('drivers AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('drivers AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicles AS v', 'e.vehicle_id', '=', 'v.id')
                                ->where('event_id', $eventId)
                                ->where('check_in', $giveCheckIn)
                                ->get());
    }

    public function checkInEnrollment(UpdateEnrollmentCheckInRequest $request, Enrollment $enrollment)
    {
        $enrollment->check_in = $request->validated()['check_in'];
        $enrollment->save();
        return new EnrollmentResource($enrollment);
    }
}
