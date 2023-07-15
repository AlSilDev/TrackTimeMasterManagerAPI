<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateStageRunRequest;
use App\Http\Resources\StageRunResource;
use App\Models\StageRun;
use App\Models\Stage;
use App\Models\TimeRun;
use Illuminate\Support\Facades\DB;

class StageRunController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('stage_runs AS sr')
                                ->select('sr.id', 's.name AS stage_name', 'sr.run_num', 'sr.practice', 'sr.ended')
                                ->join('stage AS s', 's.id', 'sr.stage_id')
                                ->get());
    }

    public function show(StageRun $stageRun)
    {
        return new StageRunResource($stageRun);
    }

    public function getClassifications(StageRun $stageRun)
    {
        $result = DB::table('times_run AS tr')
            ->select('tr.id', 'e.run_order', 'tr.time_secs', 'tr.time_mils', 'tr.penalty', 'tr.run_points AS points', 'v.model AS vehicle_model', 'vc.name AS vehicle_class', 'vcc.name AS vehicle_category', 'fd.country AS first_driver_country', 'fd.name AS first_driver_name', 'sd.country AS second_driver_country', 'sd.name AS second_driver_name')
            ->join('participants AS p', 'p.id', '=', 'tr.participant_id')
            ->join('enrollments AS e', 'e.id', '=', 'p.enrollment_id')
            ->join('vehicle_history AS v', 'v.id', '=', 'p.vehicle_id')
            ->join('vehicle_classes AS vc', 'vc.id', '=', 'v.class_id')
            ->join('vehicle_categories AS vcc', 'vcc.id', '=', 'vc.category_id')
            ->join('driver_history AS fd', 'fd.id', '=', 'p.first_driver_id')
            ->join('driver_history AS sd', 'sd.id', '=', 'p.second_driver_id')
            ->where('run_id', '=', $stageRun->id)
            ->orderBy('tr.run_points')
            ->orderBy('tr.time_secs')
            ->orderBy('tr.time_mils')
            ->get()->toArray();

        //dd($result);

        //$arrived = TimeRun::where('run_id', '=', $stageRun->id)->where('arrived', '=', '1')->orderBy('time_secs')->orderBy('time_mils')->pluck('id');
        //dd($arrived);
        /*for($i = 0; $i < count($arrived); $i++)
        {
            $key = array_search($arrived[$i], array_column($result, 'id'));
            $result[$key]->position = $i + 1;
        }*/

        return $result;
    }

    public function store(StoreUpdateStageRunRequest $request, Stage $stage)
    {
        $validated_data = $request->validated();

        //dd(StageRun::where('stage_id', '=', $validated_data['stage_id'])->orderBy('run_num', 'desc')->first()->run_num + 1);
        $largest_run_num = StageRun::where('stage_id', '=', $validated_data['stage_id'])->orderBy('run_num', 'desc')->first()->run_num;
        if (!$largest_run_num)
            $largest_run_num = 0;

        $validated_data['run_num'] = $largest_run_num + 1;

        $newStageRun = StageRun::create($validated_data);

        return new StageRunResource($newStageRun);
    }

    public function update(StoreUpdateStageRunRequest $request, StageRun $stageRun)
    {
        $validated_data = $request->validated();

        if ($validated_data['stage_id'] != $stageRun->stage_id)
        {
            return response('Invalid request: a stage run cannot be transferred to another stage', 403);
        }

        $stageRun->practice = $validated_data['practice'];
        $stageRun->date_start = $validated_data['date_start'];
        $stageRun->save();

        return new StageRunResource($stageRun);
    }

    public function end(StageRun $stageRun)
    {
        $stageRun->ended = true;
        $stageRun->save();

        if (StageRun::where('stage_id', '=', $stageRun->stage->id)->orderByDesc('created_at')->pluck('id')->first() == $stageRun->id)
        {
            $stageRun->stage->ended = true;
            $stageRun->stage->save();
        }

        return new StageRunResource($stageRun);
    }

    public function getStageRunFromStage(int $stageId)
    {
        return response()->json(DB::table('stage_runs AS sr')
                                ->select('sr.id', 'sr.run_num', 'sr.practice', 'sr.date_start', 'sr.ended')
                                ->where('sr.stage_id', $stageId)
                                ->get());
    }
}
