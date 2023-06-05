<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTimeRunRequest;
use App\Http\Resources\TimeRunResource;
use App\Models\TimeRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimesRunController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('times_run AS tr')
                                ->select('tr.id', 'tr.run_id', 'dh.name as driver', 'tr.arrival_date', 'tr.departure_date', 'tr.start_date', 'tr.end_date', 'tr.time_mils', 'tr.time_secs', 'tr.started', 'tr.arrived', 'tr.penalty', 'u.name as user_penalty_updated', 'tr.penalty_notes', 'tr.time_points', 'tr.run_points')
                                ->join('stage_runs AS sr', 'sr.id', 'tr.run_id')
                                ->join('participants AS p', 'p.id', 'tr.participant_id')
                                ->join('driver_history AS dh', 'dh.id', 'p.first_driver_id')
                                ->join('users AS u', 'u.id', 'tr.penalty_updated_by')
                                ->get());
    }

    public function store(StoreUpdateTimeRunRequest $request)
    {
        $newStage = TimeRun::create($request->validated());
        return new TimeRunResource($newStage);
    }

    public function destroy(TimeRun $timeRun)
    {
        $timeRun->delete();
        return new TimeRunResource($timeRun);
    }

    public function getStageRunTimeRuns(int $runId)
    {
        return response()->json(DB::table('times_run AS tr')
                                ->select('tr.id', 'tr.run_id', 'dh.name as driver', 'tr.arrival_date', 'tr.departure_date', 'tr.start_date', 'tr.end_date', 'tr.time_mils', 'tr.time_secs', 'tr.started', 'tr.arrived', 'tr.penalty', 'u.name as user_penalty_updated', 'tr.penalty_notes', 'tr.time_points', 'tr.run_points')
                                ->join('stage_runs AS sr', 'sr.id', 'tr.run_id')
                                ->join('participants AS p', 'p.id', 'tr.participant_id')
                                ->join('driver_history AS dh', 'dh.id', 'p.first_driver_id')
                                ->join('users AS u', 'u.id', 'tr.penalty_updated_by')
                                ->where('tr.run_id', $runId)
                                ->get());
    }

    public function getStageTimeRuns(int $stageId)
    {
        return response()->json(DB::table('times_run AS tr')
                                ->select('tr.id', 'tr.run_id', 'dh.name as driver', 'tr.arrival_date', 'tr.departure_date', 'tr.start_date', 'tr.end_date', 'tr.time_mils', 'tr.time_secs', 'tr.started', 'tr.arrived', 'tr.penalty', 'u.name as user_penalty_updated', 'tr.penalty_notes', 'tr.time_points', 'tr.run_points')
                                ->join('stage_runs AS sr', 'sr.id', 'tr.run_id')
                                ->join('participants AS p', 'p.id', 'tr.participant_id')
                                ->join('driver_history AS dh', 'dh.id', 'p.first_driver_id')
                                ->join('users AS u', 'u.id', 'tr.penalty_updated_by')
                                ->where('sr.stage_id', $stageId)
                                ->get());
    }
}
