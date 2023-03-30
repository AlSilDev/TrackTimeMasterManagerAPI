<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\DriverController;
use App\Http\Controllers\api\VehicleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('users', [UserController::class, 'index']);
//Route::get('drivers', [DriverController::class, 'index']);
//Route::get('vehicles', [VehicleController::class, 'index']);

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('users/me', [UserController::class, 'show_me']);
    Route::patch('users/{user}/password', [UserController::class, 'update_password']);
    
    Route::get('users', [UserController::class, 'index']);
    Route::get('drivers', [DriverController::class, 'index']);
    Route::get('vehicles', [VehicleController::class, 'index']);

    Route::post('users', [UserController::class, 'store']);
    Route::post('drivers', [DriverController::class, 'store']);
    Route::post('vehicles', [VehicleController::class, 'store']);

    Route::put('users/{user}', [UserController::class, 'update']);
    Route::put('drivers/{driver}', [DriverController::class, 'update']);
    Route::put('vehicles/{vehicle}', [VehicleController::class, 'update']);

    Route::delete('users/{user}', [UserController::class, 'destroy']);
    Route::delete('drivers/{driver}', [DriverController::class, 'destroy']);
    Route::delete('vehicles/{vehicle}', [VehicleController::class, 'destroy']);

    /*Route::resources([
        'drivers' => DriverController::class,
        'vehicles' => VehicleController::class,
    ]);*/
});

