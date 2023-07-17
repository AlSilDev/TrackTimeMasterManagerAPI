<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateVehicleClassRequest;
use App\Http\Resources\VehicleClassResource;
use App\Models\VehicleClass;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleClassController extends Controller
{
    public function index()
    {
        return response()->json(VehicleClass::all());
    }

    public function store(StoreUpdateVehicleClassRequest $request)
    {
        $newVehicleClass = VehicleClass::create($request->validated());
        return new VehicleClassResource($newVehicleClass);
    }

    public function update(StoreUpdateVehicleClassRequest $request, VehicleClass $vehicleClass)
    {
        $vehicleClass->update($request->validated());
        return new VehicleClassResource($vehicleClass);
    }

    public function destroy(VehicleClass $vehicleClass)
    {
        $vehicleClass->delete();
        return new VehicleClassResource($vehicleClass);
    }

    public function show_classes_categoryId(int $vehicleCategoryId)
    {
        return response()->json(DB::table('vehicle_classes AS vcla')
                                ->select('vcla.id', 'vcla.name')
                                ->join('vehicle_categories AS vcat', 'vcat.id', 'vcla.category_id')
                                ->where('vcla.category_id', $vehicleCategoryId)
                                ->get());
    }
}
