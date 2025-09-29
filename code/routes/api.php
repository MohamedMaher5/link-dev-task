<?php

use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Provider\AvailabilityController;
use App\Http\Controllers\Provider\AvailabilityOverrideController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SlotsController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

    // Provider routes
    Route::middleware('role:provider')->prefix('provider')->group(function () {
        Route::post('services', [ServiceController::class, 'store']);
        Route::post('availabilities', [AvailabilityController::class, 'store']);
        Route::post('availability-overrides', [AvailabilityOverrideController::class, 'store']);

        Route::patch('bookings/{booking}/confirm', [BookingController::class, 'confirm']);
        Route::patch('bookings/{booking}/cancel', [BookingController::class, 'cancel']);
    });

    // Customer routes
    Route::middleware('role:customer')->group(function () {
        Route::get('services', [ServiceController::class, 'index']);
        Route::get('services/{id}', [ServiceController::class, 'show']);

        Route::middleware('throttle:5,1')->group(function () {
            Route::post('bookings', [BookingController::class, 'store']);
        });
        Route::get('bookings', [BookingController::class, 'index']);

        Route::get('slots', [SlotsController::class, 'index']);
    });

    // Admin reports
    Route::middleware('role:admin')->prefix('admin/reports')->group(function () {
        Route::get('bookings-per-provider', [ReportController::class, 'bookingsPerProvider']);
        Route::get('service-status-rate', [ReportController::class, 'serviceStatusRate']);
        Route::get('peak-hours', [ReportController::class, 'peakHours']);
        Route::get('average-duration', [ReportController::class, 'averageBookingDuration']);
        Route::get('export-excel', [ReportController::class, 'exportExcel']);
    });
});
