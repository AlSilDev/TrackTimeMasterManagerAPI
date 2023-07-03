<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateParticipantRequest;
use App\Http\Requests\UpdateParticipantCanCompeteValueRequest;
use App\Http\Resources\ParticipantResource;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipantController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('participants AS p')
                                ->select('p.id', 'e.id', 'e.run_order', 'e.enroll_order', 'e.event_id','fd.name AS first_driver_name', 'sd.name AS second_driver_name', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate', 'p.can_compete')
                                ->join('enrollments AS e', 'e.id', 'p.enrollment_id')
                                ->join('drivers AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('drivers AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicles AS v', 'e.vehicle_id', '=', 'v.id')
                                ->get());
    }

    public function store(StoreUpdateParticipantRequest $request)
    {
        $validated_data = $request->validated();

        $newParticipant = new Participant;
        $newParticipant->enrollment_id = $validated_data['enrollment_id'];
        $newParticipant->can_compete = $validated_data['can_compete'];

        $newParticipant->save();
        return new ParticipantResource($newParticipant);
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();
        return new ParticipantResource($participant);
    }

    public function getAllEventParticipants(int $eventId)
    {
        return response()->json(DB::table('participants AS p')
                                ->select('p.id', 'e.id', 'fd.name AS first_driver_name', 'e.enroll_order AS enroll_order', 'e.run_order AS run_order','sd.name AS second_driver_name', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate', 'p.can_compete')
                                ->join('enrollments AS e', 'e.id', 'p.enrollment_id')
                                ->join('drivers AS fd', 'e.first_driver_id', '=', 'fd.id')
                                ->join('drivers AS sd', 'e.second_driver_id', '=', 'sd.id')
                                ->join('vehicles AS v', 'e.vehicle_id', '=', 'v.id')
                                ->join('events AS ev', 'ev.id', 'e.event_id')
                                ->where('e.event_id', $eventId)
                                ->get());
    }

    public function getEventParticipantsCanCompete(int $eventId)
    {
        return response()->json(DB::table('participants AS p')
                                ->select('p.id', 'e.id', 'fd.name AS first_driver_name', 'fd.license_num AS first_driver_license', 'e.enroll_order AS enroll_order', 'e.run_order AS run_order','sd.name AS second_driver_name', 'sd.license_num AS second_driver_license', 'v.model AS vehicle_model', 'v.license_plate AS vehicle_license_plate', 'vc.name AS vehicle_class', 'vcc.name AS vehicle_category','p.can_compete')
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
