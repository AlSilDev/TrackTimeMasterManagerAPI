<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTechnicalVerificationRequest;
use App\Http\Requests\UpdateTechnicalVerificationVerifiedValueRequest;
use App\Http\Resources\TechnicalVerificationResource;
use App\Models\TechnicalVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechnicalVerificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(DB::table('technical_verifications AS tv')
                                ->select('tv.id', 'dhf.name AS first_driver', 'dhf.driver_id AS first_driver_id','dhs.name AS second_driver', 'dhs.driver_id AS second_driver_id', 'tv.verified', 'tv.notes', 'u.name')
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
        $newTechnicalVerification->enrollment_order = $validated_data['enrollment_order'];
        $newTechnicalVerification->event_id = $validated_data['event_id'];
        $newTechnicalVerification->notes = $validated_data['notes'];
        $newTechnicalVerification->verified = $validated_data['verified'];
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

    public function update_verified_value(UpdateTechnicalVerificationVerifiedValueRequest $request, TechnicalVerification $technicalVerification)
    {
        $technicalVerification->verified = $request->validated()['verified'];
        $technicalVerification->save();
        return new TechnicalVerificationResource($technicalVerification);
    }

    public function destroy(TechnicalVerification $technicalVerification)
    {
        $technicalVerification->delete();
        return new TechnicalVerificationResource($technicalVerification);
    }

    public function getParticipantTechnicalVerification(int $participantId)
    {
        return response()->json(DB::table('technical_verifications AS tv')
                                ->select('tv.id', 'dhf.name AS first_driver', 'dhs.name AS second_driver', 'tv.verified', 'tv.notes', 'u.name')
                                ->join('participants AS p', 'p.id', 'tv.participant_id')
                                ->join('driver_history AS dhf', 'dhf.id', 'tv.first_driver_id')
                                ->join('driver_history AS dhs', 'dhs.id', 'tv.second_driver_id')
                                ->join('users as u', 'u.id', 'tv.verified_by')
                                ->where('sr.stage_id', $participantId)
                                ->get());
    }
}
