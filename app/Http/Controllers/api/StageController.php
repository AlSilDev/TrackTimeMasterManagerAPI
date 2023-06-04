<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateStageRequest;
use App\Http\Resources\StageResource;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StageController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('stages AS s')
                                ->select('s.id', 'e.name AS evento_name', 's.name', 's_date_start', 's.num_runs', 's.time_until_next_stage_mins')
                                ->join('events AS e', 'e.id', 's.event_id')
                                ->get());
    }

    public function store(StoreUpdateStageRequest $request)
    {
        $newStage = Stage::create($request->validated());
        return new StageResource($newStage);
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();
        return new StageResource($stage);
    }

    public function getEventStages(int $eventId)
    {
        return response()->json(DB::table('stages AS s')
                                ->select('s.id', 'e.name AS evento_name', 's.name', 's_date_start', 's.num_runs', 's.time_until_next_stage_mins')
                                ->join('events AS e', 'e.id', 's.event_id')
                                ->where('e.event_id', $eventId)
                                ->get());
    }
}
