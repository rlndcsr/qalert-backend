<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QueueEntriesController;


// Public API Routes
Route::post('/login', [AuthController::class, 'login'])->name('users.login');
Route::post('/users', [UserController::class, 'store'])->name('users.store');


// Private API Routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('users.logout');

    Route::controller(UserController::class)->group(function () {
        Route::get('/users',                'index');
        Route::get('/users/{id}',           'show');
        Route::put('/users/{id}',           'update')->name('users.update');
        Route::delete('/users/{id}',        'destroy');
    });

    Route::controller(QueueEntriesController::class)->group(function () {
        Route::get('/queues',               'index');
        Route::get('/queues/{id}',          'show');
        Route::post('/queues',              'store')->name('queues.store');
        Route::put('/queues/status/{id}',   'updateQueueStatus')->name('queues.update.status');
        Route::put('/queues/reason/{id}',   'updateQueueReason')->name('queues.update.reason');
        Route::delete('/queues/{id}',       'destroy');
    });
    
}); 




