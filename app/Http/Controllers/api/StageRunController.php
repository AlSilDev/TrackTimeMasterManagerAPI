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

    public function store(StoreUpdateStageRunRequest $request)
    {
        $newStageRun = StageRun::create($request->validated());
        return new StageRunResource($newStageRun);
    }

    public function getStageRunFromStage(int $stageId)
    {
        return response()->json(DB::table('stage_runs AS sr')
                                ->select('sr.id', 's.name AS stage_name', 'sr.run_num', 'sr.practice')
                                ->join('stage AS s', 's.id', 'sr.stage_id')
                                ->where('sr.stage_id', $stageId)
                                ->get());
    }
}
