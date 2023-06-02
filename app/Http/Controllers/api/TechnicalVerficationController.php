<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTechnicalVerificationRequest;
use App\Http\Requests\UpdateEnrollmentCheckInRequest;
use App\Http\Requests\UpdateTechnicalVerificationVerifiedValueRequest;
use App\Http\Resources\TechnicalVerificationResource;
use App\Models\TechnicalVerfication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechnicalVerficationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(DB::table('technical_verifications')->get());
    }

    public function show(TechnicalVerfication $technicalVerfication)
    {
        return new TechnicalVerificationResource($technicalVerfication);
    }

    public function show_me(Request $request)
    {
        return new TechnicalVerificationResource($request->technical_verification());
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

    public function store(StoreUpdateTechnicalVerificationRequest $request)
    {
        $validated_data = $request->validated();

        $newTechnicalVerification = new TechnicalVerfication;
        $newTechnicalVerification->enrollment_id = $validated_data['enrollment_id'];
        $newTechnicalVerification->notes = $validated_data['notes'];
        $newTechnicalVerification->verified_by = $validated_data['verified_by'];

        $newTechnicalVerification->save();
        return new TechnicalVerificationResource($newTechnicalVerification);
    }

    public function destroy(TechnicalVerfication $technicalVerfication)
    {
        $technicalVerfication->delete();
        return new TechnicalVerificationResource($technicalVerfication);
    }
}
