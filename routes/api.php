<?php

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ShowTimeController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ReservationMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return response()->json(['message' => 'debes iniciar sesión'], 403);
    })->name('login');

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    //showtimes
    Route::get('/showtimes', [ShowTimeController::class, 'index']);
    Route::get('/showtime/{id}', [ShowTimeController::class, 'show']);

    //movies
    Route::get('/movies', [MovieController::class, 'index']);

    //auth
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('/info', [AuthController::class, 'info']);

        //reservations
        Route::get('/reservations', [ReservationController::class, 'index']);
        Route::post('/reservation/add', [ReservationController::class, 'store']);
        Route::middleware(ReservationMiddleware::class)->group(function(){
            Route::get('/reservation/{id}', [ReservationController::class, 'show']);
            Route::delete('/reservation/{id}/delete', [ReservationController::class, 'destroy']);
        });

        Route::middleware(AdminMiddleware::class)->group(function () {
            //movie manage
            Route::post('/movie/add', [MovieController::class, 'store']);
            Route::get('/movie/{id}', [MovieController::class, 'show']);
            Route::put('/movie/{id}/update', [MovieController::class, 'update']);
            Route::delete('/movie/{id}/delete', [MovieController::class, 'destroy']);

            //showtime manage
            Route::post('/showtime/add', [ShowTimeController::class, 'store']);

            //rooms mange
            Route::get('/rooms', [RoomController::class, 'index']);
            Route::post('/room/add', [RoomController::class, 'store']);
            Route::get('/room/{id}', [RoomController::class, 'show']);
            Route::put('/room/{id}/update', [RoomController::class, 'update']);
            Route::delete('/room/{id}/delete', [RoomController::class, 'destroy']);
        });
    });


    //debug

});
