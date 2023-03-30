<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Http\Resources\DriverResource;
use App\Http\Requests\StoreUpdateDriverRequest;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return response()->json(Driver::all());
        return DriverResource::collection(Driver::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateDriverRequest $request)
    {
        $newDriver = Driver::create($request->validated());
        return new DriverResource($newDriver);
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        return new DriverResource($driver);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateDriverRequest $request, Driver $driver)
    {
        $driver->update($request->validated());
        return new DriverResource($driver);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();
        return new DriverResource($driver);
    }
}
