<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
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

    public function show_classes_categoryId(Request $request, int $id) {
        $all = DB::table('vehicle_classes')
        ->select()
        ->where('vehicle_classes.category_id', '=', $id)
        ->get();

        return $all;
    }
}
