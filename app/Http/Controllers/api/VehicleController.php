<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Http\Resources\VehicleResource;
use App\Http\Requests\StoreUpdateVehicleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    public function index(Request $request)
    {

        if ($request->attribute && $request->search){
            //dd('pqp');
            //$request->attribute = 'vehicle_classes.name';
            return response()->json(DB::table('vehicles AS v')
                                    ->select('v.id', 'v.model', 'v.engine_capacity', 'v.year', 'v.license_plate', 'vcl.name AS class', 'vct.name AS category')
                                    ->join('vehicle_classes AS vcl', 'v.class_id', '=', 'vcl.id')
                                    ->join('vehicle_categories AS vct', 'vcl.category_id', '=', 'vct.id')
                                    ->whereRaw("UPPER({$request->attribute}) LIKE CONCAT('%', UPPER('{$request->search}'), '%')")
                                    ->orderBy($request->column, $request->order)
                                    ->paginate(15));
        };

        return response()->json(DB::table('vehicles AS v')
                                ->select('v.id', 'v.model', 'v.engine_capacity', 'v.year', 'v.license_plate', 'vcl.name AS class', 'vct.name AS category')
                                ->join('vehicle_classes AS vcl', 'v.class_id', '=', 'vcl.id')
                                ->join('vehicle_categories AS vct', 'vcl.category_id', '=', 'vct.id')
                                ->orderBy($request->column, $request->order)
                                ->paginate(15));
    }

    public function store(StoreUpdateVehicleRequest $request)
    {
        $validated_data = $request->validated();

        $newVehicle = new Vehicle;
        $newVehicle->model = $validated_data['model'];
        $newVehicle->class_id = $validated_data['class_id'];
        $newVehicle->license_plate = $validated_data['license_plate'];
        $newVehicle->year = $validated_data['year'];
        $newVehicle->engine_capacity = $validated_data['engine_capacity'];

        $newVehicle->save();
        return new VehicleResource($newVehicle);
    }

    public function searchByLicensePlate(Request $request){
        $eventId = $request->eventId;

        $vehiclesNotEnrroledInEvent = DB::table('enrollments AS e')
                                                ->join('vehicle_history AS vh', 'vh.id', '=', 'e.vehicle_id')
                                                ->where('e.event_id', $eventId)
                                                ->pluck('vh.vehicle_id');

        return response()->json(DB::table('vehicles AS v')->select('v.id', 'v.model', 'v.engine_capacity', 'v.year', 'v.license_plate', 'vcl.name AS class', 'vct.name AS category')
                                                    ->join('vehicle_classes AS vcl', 'v.class_id', '=', 'vcl.id')
                                                    ->join('vehicle_categories AS vct', 'vcl.category_id', '=', 'vct.id')
                                                    ->whereRaw("LOWER(license_plate) LIKE LOWER('%" . $request->license_plate . "%')")
                                                    ->whereNotIn('v.id', $vehiclesNotEnrroledInEvent)
                                                    ->get());
    }

    public function show(Vehicle $vehicle)
    {
        return new VehicleResource($vehicle);
    }

    public function update(StoreUpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());

        return new VehicleResource($vehicle);
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return new VehicleResource($vehicle);
    }
}
