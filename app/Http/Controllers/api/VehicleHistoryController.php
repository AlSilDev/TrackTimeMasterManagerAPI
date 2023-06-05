<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateVehicleHistoryRequest;
use App\Http\Resources\VehicleHistoryResource;
use App\Models\VehicleHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleHistoryController extends Controller
{
    public function index(Request $request)
    {

        if ($request->attribute && $request->search){
            //dd('pqp');
            //$request->attribute = 'vehicle_classes.name';
            return response()->json(DB::table('vehicle_history AS vh')
                                    ->select('vh.id', 'vh.model', 'vh.engine_capacity', 'vh.year', 'vh.license_plate', 'vcl.name AS class', 'vct.name AS category')
                                    ->join('vehicle_classes AS vcl', 'vh.class_id', '=', 'vcl.id')
                                    ->join('vehicle_categories AS vct', 'vcl.category_id', '=', 'vct.id')
                                    ->whereRaw("UPPER({$request->attribute}) LIKE CONCAT('%', UPPER('{$request->search}'), '%')")
                                    ->orderBy($request->column, $request->order)
                                    ->paginate(15));
        };

        return response()->json(DB::table('vehicle_history AS vh')
                                ->select('vh.id', 'vh.model', 'vh.engine_capacity', 'vh.year', 'vh.license_plate', 'vcl.name AS class', 'vct.name AS category')
                                ->join('vehicle_classes AS vcl', 'vh.class_id', '=', 'vcl.id')
                                ->join('vehicle_categories AS vct', 'vcl.category_id', '=', 'vct.id')
                                ->orderBy($request->column, $request->order)
                                ->paginate(15));
    }

    public function store(StoreUpdateVehicleHistoryRequest $request)
    {
        $validated_data = $request->validated();

        $newVehicleHistory = new VehicleHistory();
        $newVehicleHistory->model = $validated_data['model'];
        $newVehicleHistory->class_id = $validated_data['class_id'];
        $newVehicleHistory->license_plate = $validated_data['license_plate'];
        $newVehicleHistory->year = $validated_data['year'];
        $newVehicleHistory->engine_capacity = $validated_data['engine_capacity'];

        $newVehicleHistory->save();
        return new VehicleHistoryResource($newVehicleHistory);
    }

    public function searchByLicensePlate(Request $request){
        return response()->json(DB::table('vehicle_history AS vh')->select('vh.id', 'vh.model', 'vh.engine_capacity', 'vh.year', 'vh.license_plate', 'vcl.name AS class', 'vct.name AS category')
                                                    ->join('vehicle_classes AS vcl', 'vh.class_id', '=', 'vcl.id')
                                                    ->join('vehicle_categories AS vct', 'vcl.category_id', '=', 'vct.id')
                                                    ->whereRaw("LOWER(license_plate) LIKE LOWER('%" . $request->licensePlate . "%')")
                                                    ->get());
    }

    public function show(VehicleHistory $vehicleHistory)
    {
        return new VehicleHistoryResource($vehicleHistory);
    }

    public function update(StoreUpdateVehicleHistoryRequest $request, VehicleHistory $vehicleHistory)
    {
        $vehicleHistory->update($request->validated());

        return new VehicleHistoryResource($vehicleHistory);
    }

    public function destroy(VehicleHistory $vehicleHistory)
    {
        $vehicleHistory->delete();
        return new VehicleHistoryResource($vehicleHistory);
    }
}
