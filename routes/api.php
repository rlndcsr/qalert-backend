<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\QueueEntriesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update.details');
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::get('/queues', [QueueEntriesController::class, 'index']);
Route::get('/queues/{id}', [QueueEntriesController::class, 'show']);
Route::post('/queues', [QueueEntriesController::class, 'store'])->name('queues.store');
Route::put('/queues/status/{id}', [QueueEntriesController::class, 'updateQueueStatus'])->name('queues.status.update');
Route::put('/queues/reason/{id}', [QueueEntriesController::class, 'updateQueueReason'])->name('queues.reason.update');
Route::delete('/queues/{id}', [QueueEntriesController::class, 'destroy']);
