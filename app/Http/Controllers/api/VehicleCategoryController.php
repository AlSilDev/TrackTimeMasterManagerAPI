<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateVehicleCategoryRequest;
use App\Http\Resources\VehicleCategoryResource;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleCategoryController extends Controller
{
    public function index()
    {
        return response()->json(VehicleCategory::all());
    }

    public function store(StoreUpdateVehicleCategoryRequest $request)
    {
        $newVehicleCategory = VehicleCategory::create($request->validated());
        return new VehicleCategoryResource($newVehicleCategory);
    }

    public function update(StoreUpdateVehicleCategoryRequest $request, VehicleCategory $vehicleCategory)
    {
        $vehicleCategory->update($request->validated());
        return new VehicleCategoryResource($vehicleCategory);
    }

    public function destroy(VehicleCategory $vehicleCategory)
    {
        $vehicleCategory->delete();
        return new VehicleCategoryResource($vehicleCategory);
    }
}
