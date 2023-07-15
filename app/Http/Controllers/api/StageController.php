<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateStageRequest;
use App\Http\Resources\StageResource;
use App\Models\ClassificationStage;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Stage;
use App\Models\StageRun;
use App\Models\TimeRun;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\DB;
use stdClass;

class StageController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('stages AS s')
                                ->select('s.id', 's.name', 's.date_start', 's.ended')
                                ->join('events AS e', 'e.id', '=', 's.event_id')
                                ->get());
    }

    public function show(Stage $stage)
    {
        return new StageResource($stage);
    }

    public function getClassifications(Stage $stage)
    {
        $classifications = DB::table('classifications_stage AS cs')
                            ->select('cs.id', 'cs.stage_points AS points', 'cs.participant_id', 'e.run_order', 'v.model AS vehicle_model', 'vc.name AS vehicle_class', 'vcc.name AS vehicle_category', 'fd.country AS first_driver_country', 'fd.name AS first_driver_name', 'sd.name AS second_driver_name', 'sd.country AS second_driver_country')
                            ->join('participants AS p', 'p.id', '=', 'cs.participant_id')
                            ->join('enrollments AS e', 'e.id', '=', 'p.enrollment_id')
                            ->join('driver_history AS fd', 'fd.id', '=', 'p.first_driver_id')
                            ->join('driver_history AS sd', 'sd.id', '=', 'p.second_driver_id')
                            ->join('vehicle_history AS v', 'v.id', '=', 'p.vehicle_id')
                            ->join('vehicle_classes AS vc', 'vc.id', '=', 'v.class_id')
                            ->join('vehicle_categories AS vcc', 'vcc.id', '=', 'vc.category_id')
                            ->where('cs.stage_id', '=', $stage->id)
                            ->orderBy('cs.stage_points')
                            ->get()->toArray();

        //dd($classifications);
        //dd(count($classifications));

        for($i = 0; $i < count($classifications); $i++)
        {
            //dd($classifications[$i]->participant_id);
            $runs = DB::table('times_run AS tr')
                    ->select('tr.id', 'tr.time_secs', 'tr.time_mils', 'tr.run_points AS points', 'tr.penalty')
                    ->join('stage_runs AS sr', 'sr.id', '=', 'tr.run_id')
                    ->where('sr.stage_id', '=', $stage->id)
                    ->where('sr.practice', '=', '0')
                    ->where('tr.participant_id', '=', $classifications[$i]->participant_id)
                    ->orderBy('sr.run_num')
                    ->get()->toArray();
            $classifications[$i]->runs = $runs;

            if(count($runs) > 0)
                $classifications[$i]->time_mils_first = $runs[0]->time_mils;
        }

        usort($classifications, function($a, $b) {
            $diff_pts = $a->points - $b->points;
            return $diff_pts != 0 ? $diff_pts : $a->time_mils_first - $b->time_mils_first;
        });

        $object = new stdClass();
        $object->classifications = $classifications;
        $object->num_runs = StageRun::where('stage_id', '=', $stage->id)->where('practice', '=', '0')->count();
        //dd($object);

        //dd($classifications);

        return $object;
    }

    public function store(StoreUpdateStageRequest $request, Event $event)
    {
        $validated_data = $request->validated();
        $validated_data['event_id'] = $event->id;

        $dateAux = new DateTime($validated_data['date_start'], new DateTimeZone('UTC'));
        $timezone = new DateTimeZone('Europe/Lisbon');
        $dateAux->setTimezone($timezone);
        $date_start_event = new DateTime($event->date_start_event, $timezone);
        $date_end_event = new DateTime($event->date_end_event, $timezone);
        if ($dateAux < $date_start_event || $dateAux > $date_end_event)
        {
            //dd($validated_data['date_start']);
            return response('A data de início da etapa tem de coincidir com a data da prova', 401);
        }
            
        $newStage = Stage::create($validated_data);

        $firstRun = new StageRun;
        $firstRun->stage_id = $newStage->id;
        $firstRun->run_num = 1;
        $firstRun->practice = false;
        $firstRun->date_start = $newStage->date_start;
        $firstRun->save();

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

    public function end(Stage $stage)
    {
        foreach($stage->stage_runs as $stageRun)
        {
            $stageRun->ended = true;
            $stageRun->save();
        }

        $stage->ended = true;
        $stage->save();

        return new StageResource($stageRun);
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();
        return new StageResource($stage);
    }

    public function getEventStages(Event $event)
    {
        return response()->json(DB::table('stages AS s')
                                ->select('s.id', 's.name', 's.date_start', 's.ended')
                                ->join('events AS e', 'e.id', '=', 's.event_id')
                                ->where('e.id', '=', $event->id)
                                ->get());
    }

    public function test_feature(Participant $participant)
    {
        //dd($participant->enrollment->event->stages);
        foreach($participant->enrollment->event->stages as $stage){
            $stage_pts = 0;
            foreach($stage->stage_runs as $run)
            {
                /* Registar time_runs iniciais para aparecer nas páginas de partida */
                $newRun = new TimeRun;
                $newRun->run_id = $run->id;
                $newRun->participant_id = $participant->id;

                $start_date = new DateTime($run->date_start);
                //dd($run);
                $start_date = $start_date->add(DateInterval::createFromDateString(($participant->enrollment->run_order - 1) . ' minutes'));
                $newRun->start_date = $start_date;
                //dd($newRun);
                $newRun->end_date = $start_date;

                $newRun->time_mils = 0;
                $newRun->time_secs = 0;
                $newRun->started = false;
                $newRun->arrived = false;
                $newRun->penalty = $run->stage->event->base_penalty;
                $newRun->run_points = $newRun->penalty;
                $newRun->time_points = 0;

                if (!$run->practice)
                    $stage_pts += $newRun->run_points;

                $newRun->save();
                /* ********************* */
            }

            /* Registar classifications stage iniciais */
            $newClassification = new ClassificationStage;
            $newClassification->stage_id = $stage->id;
            $newClassification->participant_id = $participant->id;
            $newClassification->stage_points = $stage_pts;

            $newClassification->save();
            /* ********************* */
        }
    }
}
