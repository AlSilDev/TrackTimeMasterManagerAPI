<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateParticipantRequest;
use App\Http\Requests\UpdateParticipantCanCompeteValueRequest;
use App\Http\Requests\UpdateParticipantDuringEventRequest;
use App\Http\Resources\ParticipantResource;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipantController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('participants AS p')
                                ->select('p.id', 'e.id', 'e.run_order', 'e.enroll_order', 'e.event_id','dhf.name AS first_driver_name', 'dhs.name AS second_driver_name', 'vh.model AS vehicle_model', 'vh.license_plate AS vehicle_license_plate', 'p.can_compete')
                                ->join('enrollments AS e', 'e.id', 'p.enrollment_id')
                                ->join('driver_history dhf', 'p.first_driver_id', '=', 'dhf.driver_id')
                                ->join('driver_history dhs', 'p.first_driver_id', '=', 'dhs.driver_id')
                                ->join('vehicle_history vh', 'p.vehicle_id', '=', 'vh.vehicle_id')
                                ->get());
    }

    public function store(StoreUpdateParticipantRequest $request)
    {
        $validated_data = $request->validated();

        $newParticipant = new Participant;
        $newParticipant->enrollment_id = $validated_data['enrollment_id'];
        $newParticipant->first_driver_id = $validated_data['first_driver_id'];
        $newParticipant->second_driver_id = $validated_data['second_driver_id'];
        $newParticipant->vehicle_id = $validated_data['vehicle_id'];
        $newParticipant->can_compete = 0;

        $newParticipant->save();
        return new ParticipantResource($newParticipant);
    }

    public function updateParticipantDuringEvent(UpdateParticipantDuringEventRequest $request, Participant $participant)
    {
        $validated_data = $request->validated();

        $participant->first_driver_id = $validated_data['first_driver_id'];
        $participant->second_driver_id = $validated_data['second_driver_id'];
        $participant->vehicle_id = $validated_data['vehicle_id'];

        $participant->save();
        return new ParticipantResource($participant);
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();
        return new ParticipantResource($participant);
    }

    public function getAllEventParticipants(int $eventId)
    {
        return response()->json(DB::table('participants AS p')
                                ->select('p.id', 'e.id', 'fd.name AS first_driver_name', 'fd.license_num AS first_driver_license', 'e.enroll_order AS enroll_order', 'e.run_order AS run_order','sd.name AS second_driver_name', 'sd.license_num AS second_driver_license', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate', 'vc.name AS vehicle_class', 'vcc.name AS vehicle_category', 'p.can_compete')
                                ->join('enrollments AS e', 'e.id', 'p.enrollment_id')
                                ->join('driver_history AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('driver_history AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicle_history AS v', 'e.vehicle_id', '=', 'v.id')
                                ->join('vehicle_classes AS vc', 'v.class_id', '=', 'vc.id')
                                ->join('vehicle_categories AS vcc', 'vc.category_id', '=', 'vcc.id')
                                ->join('events AS ev', 'ev.id', 'e.event_id')
                                ->where('e.event_id', $eventId)
                                ->orderBy('e.run_order')
                                ->get());
    }

    public function getEventParticipantsCanCompete(int $eventId)
    {
        return response()->json(DB::table('participants AS p')
                                ->select('p.id', 'e.id', 'fd.name AS first_driver_name', 'fd.license_num AS first_driver_license_num', 'fd.country AS first_driver_country', 'fd.phone_num AS first_driver_phone_num', 'e.enroll_order AS enroll_order', 'e.run_order AS run_order', 'sd.name AS second_driver_name', 'sd.license_num AS second_driver_license_num', 'sd.country AS second_driver_country', 'sd.phone_num AS second_driver_phone_num', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate', 'vc.name AS vehicle_class', 'vcc.name AS vehicle_category', 'p.can_compete')
                                ->join('enrollments AS e', 'e.id', 'p.enrollment_id')
                                ->join('driver_history AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('driver_history AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicle_history AS v', 'e.vehicle_id', '=', 'v.id')
                                ->join('vehicle_classes AS vc', 'v.class_id', '=', 'vc.id')
                                ->join('vehicle_categories AS vcc', 'vc.category_id', '=', 'vcc.id')
                                ->join('events AS ev', 'ev.id', 'e.event_id')
                                ->where('e.event_id', $eventId)
                                ->where('can_compete', 1)
                                ->orderBy('e.run_order', 'asc')
                                ->get());
    }
}
