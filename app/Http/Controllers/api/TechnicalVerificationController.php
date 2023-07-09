<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTechnicalVerificationRequest;
use App\Http\Requests\UpdateTechnicalVerificationNotesRequest;
use App\Http\Requests\UpdateTechnicalVerificationVerifiedAndNotesRequest;
use App\Http\Requests\UpdateTechnicalVerificationVerifiedValueAndByRequest;
use App\Http\Requests\UpdateTechnicalVerificationVerifiedValueRequest;
use App\Http\Requests\UpdateTehnicalVerificationNotesRequest;
use App\Http\Requests\UpdateTehnicalVerificationVerifiedAndNotesRequest;
use App\Http\Resources\TechnicalVerificationResource;
use App\Models\Participant;
use App\Models\TechnicalVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechnicalVerificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(DB::table('technical_verifications AS tv')
                                ->select('tv.id','e.event_id', 'e.enroll_order', 'e.run_order', 'dhf.name AS first_driver', 'dhf.driver_id AS first_driver_id','dhs.name AS second_driver', 'dhs.driver_id AS second_driver_id', 'tv.verified', 'tv.notes', 'u.name')
                                ->join('participants AS p', 'p.id', 'tv.participant_id')
                                ->join('driver_history AS dhf', 'dhf.id', 'tv.first_driver_id')
                                ->join('driver_history AS dhs', 'dhs.id', 'tv.second_driver_id')
                                ->join('users as u', 'u.id', 'tv.verified_by')
                                ->get());
    }

    public function show(TechnicalVerification $technicalVerification)
    {
        return new TechnicalVerificationResource($technicalVerification);
    }

    public function store(StoreUpdateTechnicalVerificationRequest $request)
    {
        $validated_data = $request->validated();

        $newTechnicalVerification = new TechnicalVerification;
        $newTechnicalVerification->enrollment_id = $validated_data['enrollment_id'];
        $newTechnicalVerification->verified = $validated_data['verified'];
        $newTechnicalVerification->notes = $validated_data['notes'];
        $newTechnicalVerification->verified_by = $validated_data['verified_by'];

        $newTechnicalVerification->save();

        return new TechnicalVerificationResource($newTechnicalVerification);
    }

    public function update(StoreUpdateTechnicalVerificationRequest $request, TechnicalVerification $technicalVerification)
    {
        $validated_data = $request->validated();
        $technicalVerification->notes = $validated_data['notes'];

        $technicalVerification->save();
        return new TechnicalVerificationResource($technicalVerification);
    }

    public function update_verified_value_and_by(UpdateTechnicalVerificationVerifiedValueAndByRequest $request, TechnicalVerification $technicalVerification)
    {
        //alterar nos Participants para o o can_compete
        //dd($technicalVerification->id);
        $participant = Participant::where('enrollment_id', '=', $technicalVerification->enrollment_id)->first();
        //dd($participant);
        $participant->can_compete = $request->validated()['verified'];
        $participant->save();

        $technicalVerification->verified = $request->validated()['verified'];
        $technicalVerification->verified_by = $request->validated()['verified_by'];
        $technicalVerification->save();
        return new TechnicalVerificationResource($technicalVerification);
    }

    public function update_verified_value_and_by_and_notes(UpdateTechnicalVerificationVerifiedAndNotesRequest $request, TechnicalVerification $technicalVerification)
    {
        $technicalVerification->verified = $request->validated()['verified'];
        $technicalVerification->verified_by = $request->validated()['verified_by'];
        $technicalVerification->notes = $request->validated()['notes'];
        $technicalVerification->save();
        return new TechnicalVerificationResource($technicalVerification);
    }

    public function update_notes(UpdateTechnicalVerificationNotesRequest $request, int $technicalVerificationId)
    {
        $technicalVerification = TechnicalVerification::find($technicalVerificationId);
        $technicalVerification->notes = $request->validated()['notes'];
        $technicalVerification->save();
        return new TechnicalVerificationResource($technicalVerification);
    }


    public function destroy(TechnicalVerification $technicalVerification)
    {
        $technicalVerification->delete();
        return new TechnicalVerificationResource($technicalVerification);
    }

    public function getEnrollmentTechnicalVerification(int $enrollmentId)
    {
        return response()->json(DB::table('technical_verifications AS tv')
                                ->select('tv.id', 'dhf.name AS first_driver', 'dhs.name AS second_driver', 'tv.verified', 'tv.notes', 'u.name')
                                ->join('enrollments AS e', 'e.id', 'tv.enrollment_id')
                                ->join('driver_history AS dhf', 'dhf.id', 'tv.first_driver_id')
                                ->join('driver_history AS dhs', 'dhs.id', 'tv.second_driver_id')
                                ->join('users as u', 'u.id', 'tv.verified_by')
                                ->where('tv.enrollment_id', $enrollmentId)
                                ->get());
    }

    public function getAllEventTechnicalVerifications(int $eventId)
    {
        return response()->json(DB::table('technical_verifications AS tv')
                                //->select('tv.id', 'dhf.name AS first_driver', 'dhs.name AS second_driver', 'tv.verified', 'tv.notes', 'u.name')
                                ->select('tv.id', 'tv.enrollment_id', 'e.enroll_order', 'e.run_order', 'vh.model AS vehicle_model', 'vh.license_plate AS vehicle_license_plate', 'dhf.name AS first_driver_name', 'dhs.name AS second_driver_name', 'tv.verified', 'tv.verified_by', 'tv.notes')
                                ->join('enrollments AS e', 'e.id', 'tv.enrollment_id')
                                ->join('admin_verifications AS av', 'tv.enrollment_id', 'av.enrollment_id')
                                ->join('driver_history AS dhf', 'dhf.driver_id', 'e.first_driver_id')
                                ->join('driver_history AS dhs', 'dhs.driver_id', 'e.second_driver_id')
                                ->join('vehicle_history AS vh', 'vh.vehicle_id', 'e.vehicle_id')
                                //->join('users as u', 'u.id', 'tv.verified_by')
                                ->where('e.event_id', $eventId)
                                ->get());
    }

    public function getEventTechnicalVerificationsForVerify(int $eventId)
    {
        return response()->json(DB::table('technical_verifications AS tv')
                                //->select('tv.id', 'dhf.name AS first_driver', 'dhs.name AS second_driver', 'tv.verified', 'tv.notes', 'u.name')
                                ->select('tv.id', 'tv.enrollment_id', 'e.enroll_order', 'e.run_order', 'vh.model AS vehicle_model', 'vh.vehicle_id AS vehicle_id', 'vh.license_plate AS vehicle_license_plate', 'dhf.name AS first_driver_name', 'dhf.license_num AS first_driver_license_num', 'dhf.country AS first_driver_country', 'dhf.phone_num AS first_driver_phone_num', 'dhs.name AS second_driver_name', 'dhs.license_num AS second_driver_license_num', 'dhs.country AS second_driver_country', 'dhs.phone_num AS second_driver_phone_num', 'tv.verified', 'tv.verified_by', 'vc.name AS vehicle_class', 'vcc.name AS vehicle_category', 'tv.notes')
                                ->join('enrollments AS e', 'e.id', 'tv.enrollment_id')
                                ->join('admin_verifications AS av', 'tv.enrollment_id', 'av.enrollment_id')
                                ->join('driver_history AS dhf', 'dhf.driver_id', 'e.first_driver_id')
                                ->join('driver_history AS dhs', 'dhs.driver_id', 'e.second_driver_id')
                                ->join('vehicle_history AS vh', 'vh.vehicle_id', 'e.vehicle_id')
                                ->join('vehicle_classes AS vc', 'vh.class_id', '=', 'vc.id')
                                ->join('vehicle_categories AS vcc', 'vc.category_id', '=', 'vcc.id')
                                //->join('users as u', 'u.id', 'tv.verified_by')
                                ->where('e.event_id', $eventId)
                                ->where('av.verified', 1)
                                ->where('tv.verified', 0)
                                ->where('tv.verified_by', null)
                                ->orderBy('e.run_order', 'asc')
                                //->orderBy('e.enrollment_id', 'asc')
                                ->get());
    }
}
