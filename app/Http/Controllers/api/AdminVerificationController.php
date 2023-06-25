<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateAdminVerificationRequest;
use App\Http\Requests\UpdateAdminVerificationVerifiedValueRequest;
use App\Http\Resources\AdminVerificationResource;
use App\Models\AdminVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminVerificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(DB::table('admin_verifications AS av')
                                ->select('av.id', 'dhf.name AS first_driver', 'dhf.driver_id AS first_driver_id', 'dhs.name AS second_driver', 'dhs.driver_id AS second_driver_id', 'av.verified', 'av.notes', 'u.name')
                                ->join('enrollments AS e', 'e.id', 'av.enrollment_id')
                                ->join('driver_history AS dhf', 'dhf.id', 'av.first_driver_id')
                                ->join('driver_history AS dhs', 'dhs.id', 'av.second_driver_id')
                                ->join('users as u', 'u.id', 'av.verified_by')
                                ->get());
    }

    public function show(AdminVerification $adminVerification)
    {
        return new AdminVerificationResource($adminVerification);
    }

    public function store(StoreUpdateAdminVerificationRequest $request)
    {
        $validated_data = $request->validated();

        $newAdminVerification = new AdminVerification;
        $newAdminVerification->enrollment_id = $validated_data['enrollment_id'];
        $newAdminVerification->enrollment_order = $validated_data['enrollment_order'];
        $newAdminVerification->event_id = $validated_data['event_id'];
        $newAdminVerification->notes = $validated_data['notes'];
        $newAdminVerification->verified = $validated_data['verified'];
        $newAdminVerification->verified_by = $validated_data['verified_by'];

        $newAdminVerification->save();
        return new AdminVerificationResource($newAdminVerification);
    }

    public function update(StoreUpdateAdminVerificationRequest $request, AdminVerification $adminVerification)
    {
        $validated_data = $request->validated();
        $adminVerification->notes = $validated_data['notes'];

        $adminVerification->save();
        return new AdminVerificationResource($adminVerification);
    }

    public function update_verified_value(UpdateAdminVerificationVerifiedValueRequest $request, AdminVerification $adminVerification)
    {
        $adminVerification->verified = $request->validated()['verified'];
        $adminVerification->save();
        return new AdminVerificationResource($adminVerification);
    }

    public function destroy(AdminVerification $adminVerification)
    {
        $adminVerification->delete();
        return new AdminVerificationResource($adminVerification);
    }

    public function getEnrollmentAdminVerification(int $enrollmentId)
    {
        return response()->json(DB::table('admin_verifications AS av')
                                ->select('av.id', 'dhf.name AS first_driver', 'dhs.name AS second_driver', 'av.verified', 'av.notes', 'u.name')
                                ->join('enrollments AS e', 'e.id', 'av.enrollment_id')
                                ->join('driver_history AS dhf', 'dhf.id', 'av.first_driver_id')
                                ->join('driver_history AS dhs', 'dhs.id', 'av.second_driver_id')
                                ->join('users as u', 'u.id', 'av.verified_by')
                                ->where('sr.stage_id', $enrollmentId)
                                ->get());
    }

}
