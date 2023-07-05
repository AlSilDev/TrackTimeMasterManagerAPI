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
use App\Models\TechnicalVerification;

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

Route::get('events/{eventId}/technicalVerifications/canBeVerified', [TechnicalVerificationController::class, 'getEventTechnicalVerifications']);
Route::get('events/{eventId}/adminVerifications/canBeVerified', [AdminVerificationController::class, 'getEventAdminVerificationsForVerify']);

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
    Route::get('users/withUserCategory/{userCategoryId}', [UserController::class, 'getUsersWithCategory']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::patch('users/{user}/password', [UserController::class, 'update_password']);
    Route::patch('users/{user}/blocked', [UserController::class, 'update_blocked']);
    Route::post('users', [UserController::class, 'store']);
    Route::delete('users/{user}/delete', [UserController::class, 'destroy']);
    /********************** Users **********************/


    /********************** Vehicle Categories **********************/
    Route::get('vehicles/categories', [VehicleCategoryController::class, 'index']);
    Route::post('vehicles/categories', [VehicleCategoryController::class, 'store']);
    Route::put('vehicles/categories/{category}', [VehicleCategoryController::class, 'update']);
    Route::delete('vehicles/categories/{category}', [VehicleCategoryController::class, 'destroy']);
    /********************** Vehicle Categories **********************/


    /********************** Vehicle classes **********************/
    Route::get('vehicles/classes', [VehicleClassController::class, 'index']);
    Route::get('vehicles/classes/withCategory/{categoryId}', [VehicleClassController::class, 'show_classes_categoryId']);
    Route::post('vehicles/classes', [VehicleClassController::class, 'store']);
    Route::put('vehicles/classes/{class}', [VehicleClassController::class, 'update']);
    Route::delete('vehicles/classes/{class}', [VehicleClassController::class, 'destroy']);
    /********************** Vehicle classes **********************/


    /********************** Vehicles **********************/
    Route::get('vehicles', [VehicleController::class, 'index']);
    Route::get('vehicles/canRun/byLicensePlate/{eventId}/{licensePlate}', [VehicleController::class, 'searchByLicensePlate']);
    Route::get('vehicles/{vehicle}', [VehicleController::class, 'show']);
    Route::post('vehicles', [VehicleController::class, 'store']);
    Route::put('vehicles/{vehicle}', [VehicleController::class, 'update']);
    Route::delete('vehicles/{vehicle}', [VehicleController::class, 'destroy']);
    /********************** Vehicles **********************/


    /********************** Vehicles History **********************/
    Route::get('vehiclesHistory', [VehicleHistoryController::class, 'index']);
    Route::get('vehiclesHistory/byLicensePlate/{licensePlate}', [VehicleHistoryController::class, 'searchByLicensePlate']);
    Route::get('vehiclesHistory/{vehicle}', [VehicleHistoryController::class, 'show']);
    Route::post('vehiclesHistory', [VehicleHistoryController::class, 'store']);
    Route::put('vehiclesHistory/{vehicleHistory}', [VehicleHistoryController::class, 'update']);
    Route::delete('vehiclesHistory/{vehicleHistory}', [VehicleHistoryController::class, 'destroy']);
    /********************** Vehicles History **********************/


    /********************** Events **********************/
    Route::get('events', [EventController::class, 'index']);
    Route::get('events/{event}', [EventController::class, 'show']);
    Route::get('events/withEventCategory/{eventCategoryId}', [EventController::class, 'getEventsWithCategory']);
    Route::get('events/{event}/classifications', [EventController::class, 'getClassifications']);
    Route::post('events', [EventController::class, 'store']);
    Route::put('events/{event}', [EventController::class, 'update']);
    Route::delete('events/{event}', [EventController::class, 'destroy']);

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
    Route::get('drivers/canDrive/byName/{eventId}/{name}', [DriverController::class, 'searchByName']);
    Route::get('drivers/{driver}', [DriverController::class, 'show']);
    Route::post('drivers', [DriverController::class, 'store']);
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
    Route::get('userCategories', [UserCategoryController::class, 'index']);
    Route::get('userCategories/{userCategoryId}/name', [UserCategoryController::class, 'showNameById']);
    Route::get('userCategories/onlyTrashed', [UserCategoryController::class, 'indexOnlyTrashed']);
    Route::get('userCategories/withTrashed', [UserCategoryController::class, 'indexWithTrashed']);
    Route::get('userCategories/restore/{userCategoryId}', [UserCategoryController::class, 'restore']);
    Route::get('userCategories/{userCategory}', [UserCategoryController::class, 'show']);
    Route::post('userCategories', [UserCategoryController::class, 'store']);
    Route::put('userCategories/{userCategory}', [UserCategoryController::class, 'update']);
    Route::delete('userCategories/{userCategory}', [UserCategoryController::class, 'destroy']);
    /********************** User Categories **********************/


    /********************** Enrollments **********************/
    Route::get('enrollments', [EnrollmentController::class, 'index']);
    Route::get('events/{eventId}/enrollments', [EnrollmentController::class, 'getEventEnrollments']);
    Route::get('events/{eventId}/enrollmentsNotAlreadyVerified', [EnrollmentController::class, 'getEnrollmentsNotAlreadyVerified']);
    //Route::get('event/{eventId}/enrollmentsToAdminVerifications', [EnrollmentController::class, 'getEventEnrollmentsForAdminVerifications']);
    //Route::get('event/{eventId}/enrollmentsToTechnicalVerifications', [EnrollmentController::class, 'getEventEnrollmentsForTechnicalVerifications']);
    Route::post('enrollments', [EnrollmentController::class, 'store']);
    Route::delete('enrollments/{enrollment}', [EnrollmentController::class, 'destroy']);
    Route::put('enrollments/{event}/run_order', [EnrollmentController::class, 'updateRunOrder']);
    /********************** Enrollments **********************/


    /********************** Participants **********************/
    Route::get('participants', [ParticipantController::class, 'index']);
    Route::get('events/{eventId}/participants/all', [ParticipantController::class, 'getAllEventParticipants']);
    Route::get('events/{eventId}/participants/canCompete', [ParticipantController::class, 'getEventParticipantsCanCompete']);
    //Route::put('events/{eventId}/participants/changeCanCompete', [ParticipantController::class, 'update_can_compete']);
    Route::post('participants', [ParticipantController::class, 'store']);
    Route::delete('participants/{participant}', [ParticipantController::class, 'destroy']);
    /********************** Participants **********************/


    /********************** Stages **********************/
    Route::get('stages', [StageController::class, 'index']);
    Route::get('stages/{stage}', [StageController::class, 'show']);
    Route::get('events/{event}/stages', [StageController::class, 'getEventStages']);
    Route::get('stages/{stage}/classifications', [StageController::class, 'getClassifications']);
    Route::post('events/{event}/stages', [StageController::class, 'store']);
    Route::put('events/{event}/stages/{stage}', [StageController::class, 'update']);
    Route::delete('stages/{stage}', [StageController::class, 'destroy']);
    /********************** Stages **********************/


    /********************** Stage Runs **********************/
    Route::get('stageRuns', [StageRunController::class, 'index']);
    Route::get('stageRuns/{stageRun}', [StageRunController::class, 'show']);
    Route::get('stageRuns/{stageRun}/classifications', [StageRunController::class, 'getClassifications']);
    Route::post('stageRuns', [StageRunController::class, 'store']);
    Route::put('stageRuns/{stageRun}', [StageRunController::class, 'update']);
    Route::delete('stageRuns/{stageRun}', [StageRunController::class, 'destroy']);
    Route::get('stages/{stageId}/runs', [StageRunController::class, 'getStageRunFromStage']);

    /********************** Stage Runs **********************/


    /********************** Admin Verifications **********************/
    Route::get('adminVerifications', [AdminVerificationController::class, 'index']);
    Route::get('adminVerifications/{adminVerification}', [AdminVerificationController::class, 'show']);
    Route::get('adminVerifications/{enrollmentId}', [AdminVerificationController::class, 'getEnrollmentAdminVerification']);
    Route::get('events/{eventId}/adminVerifications/all', [AdminVerificationController::class, 'getAllEventAdminVerifications']);
    //TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
    Route::post('adminVerifications', [AdminVerificationController::class, 'store']);
    Route::put('adminVerifications/{adminVerification}', [AdminVerificationController::class, 'update']);
    Route::put('adminVerifications/{adminVerification}/changeVerified', [AdminVerificationController::class, 'update_verified_value_and_by']);
    Route::put('adminVerifications/{adminVerification}/changeVerifiedAndNotes', [AdminVerificationController::class, 'update_verified_value_and_by_and_notes']);
    Route::put('adminVerifications/{adminVerification}/changeNotes', [AdminVerificationController::class, 'update_notes']);
    Route::delete('adminVerifications/{adminVerification}', [AdminVerificationController::class, 'destroy']);
    //Route::get('participant/{participantId}/adminVerification', [AdminVerificationController::class, 'getParticipantAdminVerification']);
    /********************** Admin Verifications **********************/


    /********************** Technical Verifications **********************/
    Route::get('technicalVerifications', [TechnicalVerificationController::class, 'index']);
    Route::get('technicalVerifications/{technicalVerification}', [TechnicalVerificationController::class, 'show']);
    Route::get('technicalVerifications/{enrollmentId}', [TechnicalVerificationController::class, 'getEnrollmentTechnicalVerification']);
    Route::get('events/{eventId}/technicalVerifications/all', [TechnicalVerificationController::class, 'getAllEventTechnicalVerifications']);
    //TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
    Route::post('technicalVerifications', [TechnicalVerificationController::class, 'store']);
    Route::put('technicalVerifications/{technicalVerification}', [TechnicalVerificationController::class, 'update']);
    Route::put('technicalVerifications/{technicalVerification}/changeVerified', [TechnicalVerificationController::class, 'update_verified_value_and_by']);
    Route::put('technicalVerifications/{technicalVerification}/changeVerifiedAndNotes', [TechnicalVerificationController::class, 'update_verified_value_and_by_and_notes']);
    Route::put('technicalVerifications/{technicalVerification}/changeNotes', [TechnicalVerificationController::class, 'update_notes']);
    Route::delete('technicalVerifications/{technicalVerification}', [TechnicalVerificationController::class, 'destroy']);
    //Route::get('participant/{participantId}/technicalVerification', [TechnicalVerificationController::class, 'getParticipantTechnicalVerification']);
    /********************** Technical Verifications **********************/


    /********************** Time Runs **********************/
    Route::get('timesRuns', [TimesRunController::class, 'index']);
    Route::post('timesRuns', [TimesRunController::class, 'store']);
    Route::delete('timesRuns/{timeRun}', [TimesRunController::class, 'destroy']);
    Route::get('stageRuns/{runId}/times', [TimesRunController::class, 'getStageRunTimeRuns']);
    Route::put('stageRuns/{run}/times/{time}/start', [TimesRunController::class, 'updateStartTime']);
    Route::put('stageRuns/{run}/times/{time}/end', [TimesRunController::class, 'updateEndTime']);
    Route::get('stages/{stageId}/times', [TimesRunController::class, 'getStageTimeRuns']);
    /********************** Time Runs **********************/


    /*Route::resources([
        'drivers' => DriverController::class,
        'vehicles' => VehicleController::class,
    ]);*/
});

