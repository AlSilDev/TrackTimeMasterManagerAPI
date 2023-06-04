<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Http\Resources\DriverResource;
use App\Http\Requests\StoreUpdateDriverRequest;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        if ($request->attribute && $request->search){
            return response()->json(Driver::whereRaw("UPPER({$request->attribute}) LIKE CONCAT('%', UPPER('{$request->search}'), '%')")->orderBy($request->column, $request->order)->paginate(15));
        }
        return response()->json(Driver::orderBy($request->column, $request->order)->paginate(15));
    }

    public function searchByName(Request $request){
        return response()->json(Driver::whereRaw("LOWER(name) LIKE LOWER('" . $request->name . "%')")->get());
    }

    public function store(StoreUpdateDriverRequest $request)
    {
        $newDriver = Driver::create($request->validated());
        return new DriverResource($newDriver);
    }
    public function show(Driver $driver)
    {
        return new DriverResource($driver);
    }

    public function update(StoreUpdateDriverRequest $request, Driver $driver)
    {
        $driver->update($request->validated());
        return new DriverResource($driver);
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return new DriverResource($driver);
    }
}
