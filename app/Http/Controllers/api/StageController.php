<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateStageRequest;
use App\Http\Resources\StageResource;
use App\Models\Event;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StageController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('stages AS s')
                                ->select('s.id', 's.name', 's.date_start')
                                ->join('events AS e', 'e.id', '=', 's.event_id')
                                ->get());
    }

    public function show(Stage $stage)
    {
        return new StageResource($stage);
    }

    public function store(StoreUpdateStageRequest $request, Event $event)
    {
        $validated_data = $request->validated();
        $validated_data['event_id'] = $event->id;
        $newStage = Stage::create($validated_data);
        return new StageResource($newStage);
    }

    public function update(StoreUpdateStageRequest $request, Event $event, Stage $stage)
    {
        $validated_data = $request->validated();

        if($stage->event_id != $event->id)
        {
            return response('Invalid request: a stage cannot be transferred to another event', 403);
        }

        $stage->name = $validated_data['name'];
        //$stage->num_runs = $validated_data['num_runs'];
        //$stage->time_until_next_run_mins = $validated_data['time_until_next_run_mins'];
        $stage->date_start = $validated_data['date_start'];

        $stage->save();

        return new StageResource($stage);
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();
        return new StageResource($stage);
    }

    public function getEventStages(Event $event)
    {
        return response()->json(DB::table('stages AS s')
                                ->select('s.id', 's.name', 's.date_start')
                                ->join('events AS e', 'e.id', '=', 's.event_id')
                                ->where('e.id', '=', $event->id)
                                ->get());
    }
}
