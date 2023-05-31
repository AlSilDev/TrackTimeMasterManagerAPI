<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Enrollment;
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
        return $newEnrollment;
    }
}
