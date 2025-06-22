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
Route::prefix('trips')->group(function () {
    Route::get('/', [TripController::class, 'index']);
    Route::post('/', [TripController::class, 'store']);
    Route::get('/{tripId}', [TripController::class, 'show']);
    Route::get('/user-history', [TripController::class, 'userHistory']);
    Route::get('/driver/{driverId}/active', [TripController::class, 'driverActiveTrips']);
    Route::patch('/{tripId}/status', [TripController::class, 'updateStatus']);
});

// Job notification routes for drivers
Route::prefix('jobs')->group(function () {
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
