<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AccessAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::prefix('user')->group(function () {
       Route::get('profile', [UserController::class, 'profile']);
   });

    Route::prefix('travel')->group(function () {
        Route::get('/', [TravelController::class, 'index']);
        Route::get('/{id}', [TravelController::class, 'show']);

        Route::group(['middleware' => AccessAdmin::class], function () {
            Route::post('/', [TravelController::class, 'store']);
            Route::put('/{id}', [TravelController::class, 'update']);
            Route::delete('/{id}', [TravelController::class, 'destroy']);
        });
    });

    Route::prefix('booking')->group(function () {
        Route::post('/', [BookingController::class, 'store']);
        Route::get('/', [BookingController::class, 'index']);
        Route::get('/{id}', [BookingController::class, 'show']);
        Route::put('/{id}', [BookingController::class, 'update']);
    });

    Route::prefix('payment')->group(function () {
       Route::get('/', [PaymentController::class, 'index']);
        Route::get('/{id}', [PaymentController::class, 'show']);
        Route::post('/{id}', [PaymentController::class, 'update']);
    });

});
