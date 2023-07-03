<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateStageRunRequest;
use App\Http\Resources\StageRunResource;
use App\Models\StageRun;
use Illuminate\Support\Facades\DB;

class StageRunController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('stage_runs AS sr')
                                ->select('sr.id', 's.name AS stage_name', 'sr.run_num', 'sr.practice')
                                ->join('stage AS s', 's.id', 'sr.stage_id')
                                ->get());
    }

    public function show(StageRun $stageRun)
    {
        return new StageRunResource($stageRun);
    }

    public function store(StoreUpdateStageRunRequest $request)
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

    public function getStageRunFromStage(int $stageId)
    {
        return response()->json(DB::table('stage_runs AS sr')
                                ->select('sr.id', 'sr.run_num', 'sr.practice', 'sr.date_start')
                                ->where('sr.stage_id', $stageId)
                                ->get());
    }
}
