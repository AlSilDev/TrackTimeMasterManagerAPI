<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\VehicleClass;
use Illuminate\Http\Request;

class VehicleClassController extends Controller
{
    public function index()
    {
        return response()->json(VehicleClass::all());
    }
}
