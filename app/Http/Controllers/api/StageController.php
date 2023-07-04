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
use Illuminate\Support\Facades\DB;
use stdClass;

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
