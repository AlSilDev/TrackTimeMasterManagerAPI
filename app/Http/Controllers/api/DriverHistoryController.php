<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDriverHistoryRequest;
use App\Http\Resources\DriverHistoryResource;
use App\Models\DriverHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverHistoryController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('driver_history AS dh') ->get());
    }

    public function store(StoreDriverHistoryRequest $request)
    {
        $newDriverHistory = DriverHistory::create($request->validated());
        return new DriverHistoryResource($newDriverHistory);
    }

    public function show(DriverHistory $driverHistory)
    {
        return new DriverHistoryResource($driverHistory);
    }

    public function update(StoreDriverHistoryRequest $request, DriverHistory $driverHistory)
    {
        $driverHistory->update($request->validated());
        return new DriverHistoryResource($driverHistory);
    }

    public function destroy(DriverHistory $driverHistory)
    {
        $driverHistory->delete();
        return new DriverHistoryResource($driverHistory);
    }
}
