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
        if ($run->ended)
        {
            return response('Não é possível alterar tempos de uma partida terminada', 403);
        }

        $validated_data = $request->validated();

        if ($validated_data['run_id'] != $run->id)
            return response('Não é possível atualizar o tempo de uma partida diferente', 403);

        if ($validated_data['participant_id'] != $time->participant_id)
            return response('Não é possível atualizar o tempo de outro participante', 403);

        $time->started = 1;
        //dd($validated_data['start_date']);
        $dateAux = new DateTime($validated_data['start_date'], new DateTimeZone('UTC'));
        $time->start_date = $dateAux->setTimezone(new DateTimeZone('Europe/Lisbon'));
        $time->start_date = $time->start_date->format('Y-m-d H:i:s');
        //dd($time->start_date);
        $time->penalty = $run->stage->event->base_penalty;
        $time->save();
        return new TimeRunResource($time);
    }

    public function updateEndTime(StoreUpdateTimeRunRequest $request, StageRun $run, TimeRun $time)
    {
        if ($run->ended)
        {
            return response('Não é possível alterar tempos de uma partida terminada', 403);
        }

        $validated_data = $request->validated();

        if ($validated_data['run_id'] != $run->id)
            return response('Não é possível atualizar o tempo de uma partida diferente', 403);

        if ($validated_data['participant_id'] != $time->participant_id)
            return response('Não é possível atualizar o tempo de outro participante', 403);

        if (!$time->started)
            return response('Não é possível atribuir tempo final se a partida não começou', 403);

        $dateAux = new DateTime($validated_data['end_date'], new DateTimeZone('UTC'));
        $time->end_date = $dateAux->setTimezone(new DateTimeZone('Europe/Lisbon'));
        $time->time_mils = $validated_data['time_mils'];
        /*
        $time->time_secs = intval($dateAux2->format('U')) - intval($dateAux->format('U'));
        dd($dateAux->format('U'));*/

        //dd($time->start_date);
        $time->time_secs = $validated_data['time_secs'];
        //dd(strtotime($aux[0]));
        //dd($aux[0]);
        //dd($time->start_date);

        //$time->time_secs = 60;
        if ($time->time_secs <= 0)
            return response('A prova tem de demorar mais de 0s', 403);

        $time->end_date = $time->end_date->format('Y-m-d H:i:s');

        $time->time_points = round($time->time_secs * $run->stage->event->point_calc_reason, 2);
        $time->penalty = 0;
        $time->run_points = $time->time_points + $time->penalty;

        $time->arrived = 1;
        $time->save();

        //dd($time->stage_run);

        if (!$time->stage_run->practice) {
            $totalPts = DB::table('times_run AS tr')
                        ->select('tr.run_points')
                        ->join('stage_runs AS sr', 'sr.id', 'tr.run_id')
                        ->join('participants AS p', 'p.id', 'tr.participant_id')
                        ->where('sr.practice', '=', '0')
                        ->where('p.id', '=', $time->participant_id)
                        ->first()->run_points;
            //dd($totalPts);
            //dd($time->stage_run->stage->classifications_stage);
            DB::update('UPDATE classifications_stage SET stage_points = ? WHERE stage_id = ? AND participant_id = ?', [$totalPts, $time->stage_run->stage_id, $time->participant_id]);
        }


        //$time->stage_run->stage->classifications_stage->
        return new TimeRunResource($time);
    }
}
