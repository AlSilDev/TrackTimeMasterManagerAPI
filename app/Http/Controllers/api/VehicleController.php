<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Http\Resources\VehicleResource;
use App\Http\Requests\StoreUpdateVehicleRequest;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(Vehicle::orderBy($request->column, $request->order)->paginate(15));
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
    public function store(StoreUpdateVehicleRequest $request)
    {
        $newVehicle = Vehicle::create($request->validated());
        return new VehicleResource($newVehicle);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return new VehicleResource($vehicle);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());
        return new VehicleResource($vehicle);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return new VehicleResource($vehicle);
    }
}
