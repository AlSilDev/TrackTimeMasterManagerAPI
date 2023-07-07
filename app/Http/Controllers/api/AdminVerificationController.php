<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateAdminVerificationRequest;
use App\Http\Requests\UpdateAdminVerificationNotesRequest;
use App\Http\Requests\UpdateAdminVerificationVerifiedAndNotes;
use App\Http\Requests\UpdateAdminVerificationVerifiedValueAndByRequest;
use App\Http\Resources\AdminVerificationResource;
use App\Models\AdminVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminVerificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(DB::table('admin_verifications AS av')
                                ->select('av.id','e.event_id', 'e.enroll_order', 'e.run_order', 'dhf.name AS first_driver', 'dhf.driver_id AS first_driver_id', 'dhs.name AS second_driver', 'dhs.driver_id AS second_driver_id', 'av.verified', 'av.notes', 'u.name')
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
        $newAdminVerification->verified = $validated_data['verified'];
        $newAdminVerification->notes = $validated_data['notes'];
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

    public function update_verified_value_and_by(UpdateAdminVerificationVerifiedValueAndByRequest $request, AdminVerification $adminVerification)
    {
        $adminVerification->verified = $request->validated()['verified'];
        $adminVerification->verified_by = $request->validated()['verified_by'];
        $adminVerification->save();
        return new AdminVerificationResource($adminVerification);
    }

    public function update_verified_value_and_by_and_notes(UpdateAdminVerificationVerifiedAndNotes $request, AdminVerification $adminVerification)
    {
        $adminVerification->verified = $request->validated()['verified'];
        $adminVerification->verified_by = $request->validated()['verified_by'];
        $adminVerification->notes = $request->validated()['notes'];
        $adminVerification->save();
        return new AdminVerificationResource($adminVerification);
    }

    public function update_notes(UpdateAdminVerificationNotesRequest $request, int $adminVerificationId)
    {
        $adminVerification = AdminVerification::find($adminVerificationId);
        $adminVerification->notes = $request->validated()['notes'];
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
                                ->where('av.enrollment_id', $enrollmentId)
                                ->get());
    }

    public function getAllEventAdminVerifications(int $eventId)
    {
        return response()->json(DB::table('admin_verifications AS av')
                                //->select('av.id', 'dhf.name AS first_driver', 'dhs.name AS second_driver', 'av.verified', 'av.notes', 'u.name')
                                ->select('av.id', 'av.enrollment_id', 'e.enroll_order', 'e.run_order', 'vh.model AS vehicle_model', 'vh.license_plate AS vehicle_license_plate', 'dhf.name AS first_driver_name', 'dhs.name AS second_driver_name', 'av.verified', 'av.verified_by', 'av.notes')
                                ->join('enrollments AS e', 'e.id', 'av.enrollment_id')
                                ->join('driver_history AS dhf', 'dhf.driver_id', 'e.first_driver_id')
                                ->join('driver_history AS dhs', 'dhs.driver_id', 'e.second_driver_id')
                                ->join('vehicle_history AS vh', 'vh.vehicle_id', 'e.vehicle_id')
                                //->join('users as u', 'u.id', 'av.verified_by')
                                ->where('e.event_id', $eventId)
                                ->get());
    }

    public function getEventAdminVerificationsForVerify(int $eventId)
    {
        return response()->json(DB::table('admin_verifications AS av')
                                //->select('av.id', 'dhf.name AS first_driver', 'dhs.name AS second_driver', 'av.verified', 'av.notes', 'u.name')
                                ->select('av.id', 'av.enrollment_id', 'e.enroll_order', 'e.run_order', 'vh.model AS vehicle_model', 'vh.license_plate AS vehicle_license_plate', 'dhf.name AS first_driver_name', 'part.first_driver_id AS first_driver_id', 'dhf.license_num AS first_driver_license_num', 'dhs.name AS second_driver_name', 'part.second_driver_id AS second_driver_id', 'dhs.license_num AS second_driver_license_num', 'av.verified', 'av.verified_by', 'vc.name AS vehicle_class', 'vcc.name AS vehicle_category', 'av.notes')
                                ->join('enrollments AS e', 'e.id', 'av.enrollment_id')
                                ->join('participants AS part', 'e.id', 'part.enrollment_id')
                                ->join('driver_history AS dhf', 'dhf.id', 'part.first_driver_id')
                                ->join('driver_history AS dhs', 'dhs.id', 'part.second_driver_id')
                                ->join('vehicle_history AS vh', 'vh.id', 'part.vehicle_id')
                                ->join('vehicle_classes AS vc', 'vh.class_id', '=', 'vc.id')
                                ->join('vehicle_categories AS vcc', 'vc.category_id', '=', 'vcc.id')
                                //->join('users as u', 'u.id', 'av.verified_by')
                                ->where('e.event_id', $eventId)
                                ->where('av.verified', 0)
                                ->where('av.verified_by', null)
                                ->orderBy('e.run_order', 'asc')
                                ->get());
    }

}
