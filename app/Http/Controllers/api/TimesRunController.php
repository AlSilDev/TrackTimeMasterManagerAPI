<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTimeRunRequest;
use App\Http\Resources\TimeRunResource;
use App\Models\StageRun;
use App\Models\TimeRun;
use DateTime;
use DateTimeZone;
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
        /*$query = DB::table('times_run AS tr')
                ->select('tr.id', 'tr.run_id', 'e.run_order', 'dh.name as driver', 'tr.arrival_date', 'tr.departure_date', 'tr.start_date', 'tr.end_date', 'tr.time_mils', 'tr.time_secs', 'tr.started', 'tr.arrived', 'tr.penalty', 'u.name as user_penalty_updated', 'tr.penalty_notes', 'tr.time_points', 'tr.run_points')
                ->join('participants AS p', 'p.id', '=', 'tr.participant_id')
                ->join('enrollments AS e', 'e.id', '=', 'p.enrollment_id')
                ->join('driver_history AS dh', 'dh.id', '=', 'p.first_driver_id')
                ->join('users AS u', 'u.id', '=', 'tr.penalty_updated_by')
                ->where('tr.run_id', '=', $runId)
                ->get();
        dd($query);*/
        return response()->json(DB::table('times_run AS tr')
                                ->select('tr.id', 'tr.run_id', 'e.run_order', 'p.id AS participant_id', 'dh.name as driver', 'tr.arrival_date', 'tr.departure_date', 'tr.start_date', 'tr.end_date', 'tr.time_mils', 'tr.time_secs', 'tr.started', 'tr.arrived', 'tr.penalty', 'tr.penalty_notes', 'tr.time_points', 'tr.run_points')
                                ->join('stage_runs AS sr', 'sr.id', '=', 'tr.run_id')
                                ->join('participants AS p', 'p.id', '=', 'tr.participant_id')
                                ->join('enrollments AS e', 'e.id', '=', 'p.enrollment_id')
                                ->join('driver_history AS dh', 'dh.id', '=', 'p.first_driver_id')
                                ->where('tr.run_id', '=', $runId)
                                ->orderBy('e.run_order')
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
                                ->where('sr.stage_id', '=', $stageId)
                                ->get());
    }

    public function updateStartTime(StoreUpdateTimeRunRequest $request, StageRun $run, TimeRun $time)
    {
        $validated_data = $request->validated();

        if ($validated_data['run_id'] != $run->id)
            return response('Invalid request: a time cannot be set to a different run', 403);

        if ($validated_data['participant_id'] != $time->participant_id)
            return response('Invalid request: a time cannot be set to a different participant', 403);

        $time->started = 1;

        $dateAux = new DateTime($validated_data['start_date'], new DateTimeZone('UTC'));
        $time->start_date = $dateAux->setTimezone(new DateTimeZone('Europe/Lisbon'));
        //dd($time->start_date);
        $time->save();
        return new TimeRunResource($time);
    }
}
