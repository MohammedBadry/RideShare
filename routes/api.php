<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\JobNotificationController;
use App\Http\Controllers\Admin\AvailableDriversController;
use App\Http\Controllers\Admin\VehicleLocationController;
use App\Http\Controllers\Api\AuthController;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']); 

// Trip routes
Route::middleware('auth:sanctum')->prefix('trips')->group(function () {
    Route::get('/', [TripController::class, 'index']);
    Route::post('/', [TripController::class, 'store']);
    Route::get('/user-history', [TripController::class, 'userHistory'])->name('trips.userHistory');
    Route::get('/{trip}', [TripController::class, 'show'])->name('trips.show');
    Route::patch('/{trip}/status', [TripController::class, 'updateStatus'])->name('trips.updateStatus');
    Route::post('/{trip}/retry-assignment', [TripController::class, 'retryDriverAssignment'])->name('trips.retryAssignment');
});

// Job notification routes for drivers
Route::middleware('auth:sanctum')->prefix('jobs')->group(function () {
    Route::get('/driver/{driverId}/available', [JobNotificationController::class, 'getAvailableJobs']);
    Route::post('/driver/{driverId}/accept', [JobNotificationController::class, 'acceptJob']);
    Route::post('/driver/{driverId}/reject/{tripId}', [JobNotificationController::class, 'rejectJob']);
    Route::get('/driver/{driverId}/current', [JobNotificationController::class, 'getCurrentJob']);
    Route::post('/driver/{driverId}/complete/{tripId}', [JobNotificationController::class, 'completeJob']);
});

// Available drivers routes
Route::prefix('available-drivers')->group(function () {
    Route::get('/', [AvailableDriversController::class, 'index']);
    Route::get('/optimized', [AvailableDriversController::class, 'optimizedQuery']);
});

// Vehicle location routes
Route::prefix('vehicles')->group(function () {
    Route::patch('/{vehicleId}/location', [VehicleLocationController::class, 'update']);
    Route::get('/{vehicleId}/location', [VehicleLocationController::class, 'getLocation']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/drivers/{driverId}/active-trips', [TripController::class, 'driverActiveTrips']);
