<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Http\Resources\VehicleResource;
use App\Http\Requests\StoreUpdateVehicleRequest;
use App\Http\Requests\CreateVehicleRequest;
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
    public function store(CreateVehicleRequest $request)
    {
        //$newVehicle = Vehicle::create($request->validated());
        $validated_data = $request->validated();

        $newVehicle = new Vehicle;
        $newVehicle->model = $validated_data['model'];
        $newVehicle->category = $validated_data['category'];
        $newVehicle->class = $validated_data['class'];
        $newVehicle->license_plate = $validated_data['license_plate'];
        $newVehicle->year = $validated_data['year'];
        $newVehicle->engine_capacity = $validated_data['engine_capacity'];

        $newVehicle->save();
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
