<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTechnicalVerificationRequest;
use App\Http\Requests\UpdateTechnicalVerificationVerifiedValueRequest;
use App\Http\Resources\TechnicalVerificationResource;
use App\Models\TechnicalVerfication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechnicalVerficationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(DB::table('technical_verifications AS tv')
                                ->select('tv.id', 'dhf.name AS first_driver', 'dhs.name AS second_driver', 'tv.verified', 'tv.notes', 'u.name')
                                ->join('participants AS p', 'p.id', 'tv.participant_id')
                                ->join('driver_history AS dhf', 'dhf.id', 'tv.first_driver_id')
                                ->join('driver_history AS dhs', 'dhs.id', 'tv.second_driver_id')
                                ->join('users as u', 'u.id', 'tv.verified_by')
                                ->get());
    }

    public function show(TechnicalVerfication $technicalVerfication)
    {
        return new TechnicalVerificationResource($technicalVerfication);
    }

    public function store(StoreUpdateTechnicalVerificationRequest $request)
    {
        $validated_data = $request->validated();

        $newTechnicalVerification = new TechnicalVerfication;
        $newTechnicalVerification->participant_id = $validated_data['participant_id'];
        $newTechnicalVerification->notes = $validated_data['notes'];
        $newTechnicalVerification->verified_by = $validated_data['verified_by'];

        $newTechnicalVerification->save();
        return new TechnicalVerificationResource($newTechnicalVerification);
    }

    public function update(StoreUpdateTechnicalVerificationRequest $request, TechnicalVerfication $technicalVerfication)
    {
        $validated_data = $request->validated();
        $technicalVerfication->notes = $validated_data['notes'];

        $technicalVerfication->save();
        return new TechnicalVerificationResource($technicalVerfication);
    }

    public function update_verified_value(UpdateTechnicalVerificationVerifiedValueRequest $request, TechnicalVerfication $technicalVerfication)
    {
        $technicalVerfication->verified = $request->validated()['verified'];
        $technicalVerfication->save();
        return new TechnicalVerificationResource($technicalVerfication);
    }

    public function destroy(TechnicalVerfication $technicalVerfication)
    {
        $technicalVerfication->delete();
        return new TechnicalVerificationResource($technicalVerfication);
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
