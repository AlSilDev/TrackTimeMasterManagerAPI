<?php

use App\Http\Controllers\api\AdminVerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\DriverController;
use App\Http\Controllers\api\DriverHistoryController;
use App\Http\Controllers\api\EnrollmentController;
use App\Http\Controllers\api\EventCategoryController;
use App\Http\Controllers\api\VehicleCategoryController;
use App\Http\Controllers\api\VehicleClassController;
use App\Http\Controllers\api\VehicleController;
use App\Http\Controllers\api\EventController;
use App\Http\Controllers\api\PressController;
use App\Http\Controllers\api\RegulationController;
use App\Http\Controllers\api\StageController;
use App\Http\Controllers\api\StageRunController;
use App\Http\Controllers\api\TimesRunController;
use App\Http\Controllers\api\UserCategoryController;
use App\Http\Controllers\api\VehicleHistoryController;
use App\Http\Controllers\api\VideoController;
use App\Http\Controllers\api\ParticipantController;
use App\Http\Controllers\api\TechnicalVerificationController;

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

Route::get('drivers/canDrive/byName/{eventId}/{name}', [DriverController::class, 'searchByName']);
Route::get('vehicles/canRun/byLicensePlate/{eventId}/{licensePlate}', [VehicleController::class, 'searchByLicensePlate']);

Route::get('event/{eventId}/enrollmentsToAdminVerifications', [EnrollmentController::class, 'getEventEnrollmentsForAdminVerifications']);
Route::get('event/{eventId}/enrollmentsToTechnicalVerifications', [EnrollmentController::class, 'getEventEnrollmentsForTechnicalVerifications']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);

//ACCESS ALOWED ONLY FOR REGISTERED USERS
Route::middleware('auth:api')->group(function () {

    /********************** LogOut **********************/
    Route::post('logout', [AuthController::class, 'logout']);
    /********************** LogOut **********************/

    /********************** Users **********************/
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/me', [UserController::class, 'show_me']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::patch('users/{user}/password', [UserController::class, 'update_password']);
    Route::patch('users/{user}/blocked', [UserController::class, 'update_blocked']);
    Route::post('users', [UserController::class, 'store']);
    Route::delete('users/{user}/delete', [UserController::class, 'destroy']);
    Route::get('users/withUserCategory/{userCategoryId}', [UserController::class, 'getUsersWithCategory']);
    /********************** Users **********************/

    /********************** Vehicle Categories **********************/
    Route::get('vehicles/categories', [VehicleCategoryController::class, 'index']);
    Route::post('vehicles/categories', [VehicleCategoryController::class, 'store']);
    Route::put('vehicles/categories/{category}', [VehicleCategoryController::class, 'update']);
    Route::delete('vehicles/categories/{category}', [VehicleCategoryController::class, 'destroy']);
    /********************** Vehicle Categories **********************/

    /********************** Vehicle classes **********************/
    Route::get('vehicles/classes', [VehicleClassController::class, 'index']);
    Route::post('vehicles/classes', [VehicleClassController::class, 'store']);
    Route::put('vehicles/classes/{class}', [VehicleClassController::class, 'update']);
    Route::delete('vehicles/classes/{class}', [VehicleClassController::class, 'destroy']);
    Route::get('vehicles/classes/withCategory/{categoryId}', [VehicleClassController::class, 'show_classes_categoryId']);
    /********************** Vehicle classes **********************/

    /********************** Vehicles **********************/
    Route::get('vehicles', [VehicleController::class, 'index']);
    Route::post('vehicles', [VehicleController::class, 'store']);
    //TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
    Route::get('vehicles/{vehicle}', [VehicleController::class, 'show']);
    Route::put('vehicles/{vehicle}', [VehicleController::class, 'update']);
    Route::delete('vehicles/{vehicle}', [VehicleController::class, 'destroy']);
    /********************** Vehicles **********************/

    /********************** Vehicles History **********************/
    Route::get('vehiclesHistory', [VehicleHistoryController::class, 'index']);
    Route::post('vehiclesHistory', [VehicleHistoryController::class, 'store']);
    Route::get('vehiclesHistory/byLicensePlate/{licensePlate}', [VehicleHistoryController::class, 'searchByLicensePlate']);
    Route::get('vehiclesHistory/{vehicle}', [VehicleHistoryController::class, 'show']);
    Route::put('vehiclesHistory/{vehicleHistory}', [VehicleHistoryController::class, 'update']);
    Route::delete('vehiclesHistory/{vehicleHistory}', [VehicleHistoryController::class, 'destroy']);
    /********************** Vehicles History **********************/

    /********************** Events **********************/
    Route::get('events', [EventController::class, 'index']);
    Route::post('events', [EventController::class, 'store']);
    Route::get('events/{event}', [EventController::class, 'show']);
    Route::put('events/{event}', [EventController::class, 'update']);
    Route::delete('events/{event}', [EventController::class, 'destroy']);
    Route::get('events/withEventCategory/{eventCategoryId}', [EventController::class, 'getEventsWithCategory']);
    /********************** Events **********************/

    /********************** Videos **********************/
    Route::get('events/{event}/videos', [VideoController::class, 'show']);
    Route::post('events/{event}/videos', [VideoController::class, 'store']);
    Route::delete('videos/{video}', [VideoController::class, 'destroy']);
    /********************** Videos **********************/

    /********************** Press **********************/
    Route::get('events/{event}/press', [PressController::class, 'show']);
    Route::post('events/{event}/press', [PressController::class, 'store']);
    Route::delete('press/{press}', [PressController::class, 'destroy']);
    /********************** Press **********************/

    /********************** Regulations **********************/
    Route::get('events/{event}/regulations', [RegulationController::class, 'show']);
    Route::post('events/{event}/regulations', [RegulationController::class, 'store']);
    Route::delete('regulations/{regulation}', [RegulationController::class, 'destroy']);
    /********************** Regulations **********************/

    /********************** Drivers **********************/
    Route::get('drivers', [DriverController::class, 'index']);
    //TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
    Route::post('drivers', [DriverController::class, 'store']);
    Route::get('drivers/{driver}', [DriverController::class, 'show']);
    Route::put('drivers/{driver}', [DriverController::class, 'update']);
    Route::delete('drivers/{driver}', [DriverController::class, 'destroy']);
    /********************** Drivers **********************/

    /********************** Drivers History **********************/
    Route::get('driversHistory', [DriverHistoryController::class, 'index']);
    Route::get('driversHistory/{driversHistoryId}', [DriverHistoryController::class, 'show']);
    Route::post('driversHistory', [DriverHistoryController::class, 'store']);
    Route::put('driversHistory/{driversHistoryId}', [DriverHistoryController::class, 'update']);
    Route::delete('driversHistory/{driversHistoryId}', [DriverHistoryController::class, 'destroy']);
    /********************** Drivers History **********************/

    /********************** Event Categories **********************/
    Route::get('eventCategories', [EventCategoryController::class, 'index']);
    Route::get('eventCategories/onlyTrashed', [EventCategoryController::class, 'indexOnlyTrashed']);
    Route::get('eventCategories/withTrashed', [EventCategoryController::class, 'indexWithTrashed']);
    Route::get('eventCategories/restore/{eventCategoryId}', [EventCategoryController::class, 'restore']);
    Route::get('eventCategories/{eventCategory}', [EventCategoryController::class, 'show']);
    Route::post('eventCategories', [EventCategoryController::class, 'store']);
    Route::put('eventCategories/{eventCategory}', [EventCategoryController::class, 'update']);
    Route::delete('eventCategories/{eventCategory}', [EventCategoryController::class, 'destroy']);
    //EventController have function (getEventsWithCategory) to return events with specific category
    /********************** Event Categories **********************/

    /********************** User Categories **********************/
    Route::get('userCategories/onlyTrashed', [UserCategoryController::class, 'indexOnlyTrashed']);
    Route::get('userCategories/withTrashed', [UserCategoryController::class, 'indexWithTrashed']);
    Route::get('userCategories/restore/{userCategoryId}', [UserCategoryController::class, 'restore']);
    Route::get('userCategories/{userCategory}', [UserCategoryController::class, 'show']);
    Route::post('userCategories', [UserCategoryController::class, 'store']);
    Route::put('userCategories/{userCategory}', [UserCategoryController::class, 'update']);
    Route::delete('userCategories/{userCategory}', [UserCategoryController::class, 'destroy']);
    Route::get('userCategories/{userCategoryId}/name', [UserCategoryController::class, 'showNameById']);
    Route::get('userCategories', [UserCategoryController::class, 'index']);
    /********************** User Categories **********************/

    /********************** Enrollments **********************/
    Route::get('enrollments', [EnrollmentController::class, 'index']);
    Route::post('enrollments', [EnrollmentController::class, 'store']);
    Route::delete('enrollments/{enrollment}', [EnrollmentController::class, 'destroy']);
    Route::get('event/{eventId}/enrollments', [EnrollmentController::class, 'getEventEnrollments']);
    //TOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
    Route::put('enrollments/{event}/run_order', [EnrollmentController::class, 'updateRunOrder']);
    /********************** Enrollments **********************/

    /********************** Participants **********************/
    Route::get('participants', [ParticipantController::class, 'index']);
    Route::post('participants', [ParticipantController::class, 'store']);
    Route::delete('participants/{participant}', [ParticipantController::class, 'destroy']);
    Route::get('event/{eventId}/participants', [ParticipantController::class, 'getEventParticipants']);
    /********************** Participants **********************/

    /********************** Stages **********************/
    Route::get('stages', [StageController::class, 'index']);
    Route::post('stages', [StageController::class, 'store']);
    Route::delete('stages/{stage}', [StageController::class, 'destroy']);
    Route::get('event/{eventId}/stages', [StageController::class, 'getEventStages']);
    /********************** Stages **********************/

    /********************** Stage Runs **********************/
    Route::get('stageRuns', [StageRunController::class, 'index']);
    Route::post('stageRuns', [StageRunController::class, 'store']);
    Route::delete('stageRuns/{stageRun}', [StageRunController::class, 'destroy']);
    Route::get('stages/{stageId}/stageRuns', [StageRunController::class, 'getStageRunFromStage']);
    /********************** Stage Runs **********************/

    /********************** Admin Verifications **********************/
    Route::get('adminVerifications', [AdminVerificationController::class, 'index']);
    Route::get('adminVerifications/{adminVerification}', [AdminVerificationController::class, 'show']);
    Route::post('adminVerifications', [AdminVerificationController::class, 'store']);
    Route::put('adminVerifications/{adminVerification}', [AdminVerificationController::class, 'update']);
    Route::put('adminVerifications/{adminVerification}/changeVerified', [AdminVerificationController::class, 'update_verified_value']);
    Route::delete('adminVerifications/{stage}', [AdminVerificationController::class, 'destroy']);
    Route::get('participant/{participantId}/adminVerification', [AdminVerificationController::class, 'getParticipantAdminVerification']);
    /********************** Admin Verifications **********************/

    /********************** Technical Verifications **********************/
    Route::get('technicalVerifications', [TechnicalVerificationController::class, 'index']);
    Route::get('technicalVerifications/{technicalVerification}', [TechnicalVerificationController::class, 'show']);
    Route::post('technicalVerifications', [TechnicalVerificationController::class, 'store']);
    Route::put('technicalVerifications/{technicalVerification}', [TechnicalVerificationController::class, 'update']);
    Route::put('technicalVerifications/{technicalVerification}/changeVerified', [TechnicalVerificationController::class, 'update_verified_value']);
    Route::delete('technicalVerifications/{stage}', [TechnicalVerificationController::class, 'destroy']);
    Route::get('participant/{participantId}/technicalVerification', [TechnicalVerificationController::class, 'getParticipantTechnicalVerification']);
    /********************** Technical Verifications **********************/

    /********************** Time Runs **********************/
    Route::get('timesRuns', [TimesRunController::class, 'index']);
    Route::post('timesRuns', [TimesRunController::class, 'store']);
    Route::delete('timesRuns/{timeRun}', [TimesRunController::class, 'destroy']);
    Route::get('stageRuns/{runId}/timesRun', [TimesRunController::class, 'getStageRunTimeRuns']);
    Route::get('stages/{stageId}/timesRun', [TimesRunController::class, 'getStageTimeRuns']);
    /********************** Time Runs **********************/

    /*Route::resources([
        'drivers' => DriverController::class,
        'vehicles' => VehicleController::class,
    ]);*/
});

