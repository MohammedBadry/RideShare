<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TripsController as AdminTripsController;
use App\Http\Controllers\Admin\DriversController as AdminDriversController;
use App\Http\Controllers\Admin\VehiclesController as AdminVehiclesController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;

// Admin Authentication Routes (Public)
Route::name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin Protected Routes
Route::name('admin.')->middleware('admin.auth')->group(function () {

    // Dashboard Routes
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Drivers Management Routes
    Route::prefix('drivers')->name('drivers.')->middleware('admin.permission:view_drivers')->group(function () {
        Route::get('/', [AdminDriversController::class, 'index'])->name('index');

        Route::middleware('admin.permission:create_drivers')->group(function () {
            Route::get('/create', [AdminDriversController::class, 'create'])->name('create');
            Route::post('/', [AdminDriversController::class, 'store'])->name('store');
        });

        Route::get('/{driver}', [AdminDriversController::class, 'show'])->name('show');

        Route::middleware('admin.permission:edit_drivers')->group(function () {
            Route::get('/{driver}/edit', [AdminDriversController::class, 'edit'])->name('edit');
            Route::put('/{driver}', [AdminDriversController::class, 'update'])->name('update');
        });

        Route::middleware('admin.permission:delete_drivers')->group(function () {
            Route::delete('/{driver}', [AdminDriversController::class, 'destroy'])->name('destroy');
        });
    });

    // Vehicles Management Routes
    Route::prefix('vehicles')->name('vehicles.')->middleware('admin.permission:view_vehicles')->group(function () {
        Route::get('/', [AdminVehiclesController::class, 'index'])->name('index');

        Route::middleware('admin.permission:create_vehicles')->group(function () {
            Route::get('/create', [AdminVehiclesController::class, 'create'])->name('create');
            Route::post('/', [AdminVehiclesController::class, 'store'])->name('store');
        });

        Route::get('/{vehicle}', [AdminVehiclesController::class, 'show'])->name('show');

        Route::middleware('admin.permission:edit_vehicles')->group(function () {
            Route::get('/{vehicle}/edit', [AdminVehiclesController::class, 'edit'])->name('edit');
            Route::put('/{vehicle}', [AdminVehiclesController::class, 'update'])->name('update');
        });

        Route::middleware('admin.permission:delete_vehicles')->group(function () {
            Route::delete('/{vehicle}', [AdminVehiclesController::class, 'destroy'])->name('destroy');
        });
    });

    // Trips Management Routes
    Route::prefix('trips')->name('trips.')->middleware('admin.permission:view_trips')->group(function () {
        Route::get('/', [AdminTripsController::class, 'index'])->name('index');

        Route::middleware('admin.permission:create_trips')->group(function () {
            Route::get('/create', [AdminTripsController::class, 'create'])->name('create');
            Route::post('/', [AdminTripsController::class, 'store'])->name('store');
        });

        Route::get('/{trip}', [AdminTripsController::class, 'show'])->name('show');

        Route::middleware('admin.permission:edit_trips')->group(function () {
            Route::get('/{trip}/edit', [AdminTripsController::class, 'edit'])->name('edit');
            Route::put('/{trip}', [AdminTripsController::class, 'update'])->name('update');
        });

        Route::middleware('admin.permission:delete_trips')->group(function () {
            Route::delete('/{trip}', [AdminTripsController::class, 'destroy'])->name('destroy');
        });
    });

    // Analytics Routes
    Route::prefix('analytics')->name('analytics.')->middleware('admin.permission:view_analytics')->group(function () {
        Route::get('/', [AdminAnalyticsController::class, 'index'])->name('index');

        Route::middleware('admin.permission:export_analytics')->group(function () {
            // Route::get('/export', [AdminAnalyticsController::class, 'export'])->name('export');
        });
    });
});
