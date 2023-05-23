<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\DriverController;
use App\Http\Controllers\api\EventCategoryController;
use App\Http\Controllers\api\VehicleCategoryController;
use App\Http\Controllers\api\VehicleClassController;
use App\Http\Controllers\api\VehicleController;
use App\Http\Controllers\api\EventController;
use App\Http\Controllers\api\PressController;
use App\Http\Controllers\api\RegulationController;
use App\Http\Controllers\api\VideoController;

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

Route::get('events/withEventCategory/{eventCategoryId}', [EventController::class, 'getEventsWithCategory']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users', [UserController::class, 'index']);

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('users/me', [UserController::class, 'show_me']);
    Route::patch('users/{user}/password', [UserController::class, 'update_password']);

    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);

    /********************** Event Categories **********************/
    Route::get('eventCategories', [EventCategoryController::class, 'index']);
    Route::get('eventCategories/onlyTrashed', [EventCategoryController::class, 'indexOnlyTrashed']);
    Route::get('eventCategories/withTrashed', [EventCategoryController::class, 'indexWithTrashed']);
    Route::get('eventCategories/restore/{eventCategoryId}', [EventCategoryController::class, 'restore']);
    Route::get('eventCategories/{eventCategory}', [EventCategoryController::class, 'show']);
    Route::post('eventCategories', [EventCategoryController::class, 'store']);
    Route::put('eventCategories/{eventCategory}', [EventCategoryController::class, 'update']);
    Route::delete('eventCategories/{eventCategory}', [EventCategoryController::class, 'destroy']);
    /********************** Event Categories **********************/


    //all classes and all categories
    Route::get('categories', [VehicleCategoryController::class, 'index']);
    Route::get('classes', [VehicleClassController::class, 'index']);
    Route::get('classes/{categoryId}', [VehicleClassController::class, 'show_classes_categoryId']);

    Route::get('drivers', [DriverController::class, 'index']);
    Route::get('drivers/{driver}', [DriverController::class, 'show']);

    //Route::put('vehicles/{vehicle}', [VehicleController::class, 'update']);
    Route::get('vehicles', [VehicleController::class, 'index']);
    Route::get('vehicles/{vehicle}', [VehicleController::class, 'show']);
    Route::post('vehicles', [VehicleController::class, 'store']);

    Route::post('drivers', [DriverController::class, 'store']);
    Route::post('users', [UserController::class, 'store']);

    Route::put('users/{user}', [UserController::class, 'update']);
    //Route::put('drivers/{driver}', [DriverController::class, 'update']);
    Route::put('vehicles/{vehicle}', [VehicleController::class, 'update']);

    Route::patch('users/{user}/password', [UserController::class, 'update_password']);
    Route::patch('users/{user}/blocked', [UserController::class, 'update_blocked']);

    Route::delete('users/{user}/delete', [UserController::class, 'destroy']);
    Route::delete('drivers/{driver}', [DriverController::class, 'destroy']);
    Route::delete('vehicles/{vehicle}', [VehicleController::class, 'destroy']);

    Route::get('events', [EventController::class, 'index']);
    //to put
    Route::post('events', [EventController::class, 'store']);
    Route::get('events/{event}', [EventController::class, 'show']);
    Route::put('events/{event}', [EventController::class, 'update']);
    Route::delete('events/{event}', [EventController::class, 'destroy']);

    Route::get('events/{event}/press', [PressController::class, 'show']);
    Route::post('events/{event}/press', [PressController::class, 'store']);
    Route::delete('press/{press}', [PressController::class, 'destroy']);

    Route::get('events/{event}/videos', [VideoController::class, 'show']);
    Route::post('events/{event}/videos', [VideoController::class, 'store']);
    Route::delete('videos/{video}', [VideoController::class, 'destroy']);

    Route::get('events/{event}/regulations', [RegulationController::class, 'show']);
    Route::post('events/{event}/regulations', [RegulationController::class, 'store']);
    Route::delete('regulations/{regulation}', [RegulationController::class, 'destroy']);

    /*Route::resources([
        'drivers' => DriverController::class,
        'vehicles' => VehicleController::class,
    ]);*/
});

