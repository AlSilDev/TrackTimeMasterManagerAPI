<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleCategoryController extends Controller
{
    public function index()
    {
        return response()->json(VehicleCategory::all());
    }

    /*public function show_categoryID(Request $request) {
        $all = DB::table('vehicle_categories')
        ->select('id')
        ->where('vehicle_category.category_id', '=')
        ->get();

        return $all;
    }*/
}
