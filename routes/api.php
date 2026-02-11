<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorsController;
use App\Http\Controllers\Api\SchedulesController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\Api\QueueEntriesController;
use App\Http\Controllers\Api\DoctorScheduleController;
use App\Http\Controllers\Api\ReasonCategoryController;
use App\Http\Controllers\Api\EmergencyEncounterController;

// Public API Routes
Route::post('/login',           [AuthController::class, 'login'])->name('users.login');
Route::post('/adminLogin',      [AuthController::class, 'adminLogin'])->name('admin.login');
Route::post('/users',           [UserController::class, 'store'])->name('users.store');
Route::post('/verify-email',    [UserController::class, 'verifyEmail'])->name('users.verify-email');
Route::post('/resend-verification-code', [UserController::class, 'resendVerificationCode'])->name('users.resend-verification-code');
Route::get('/system-status',    [SystemSettingsController::class, 'show']);
Route::put('/system-status',    [SystemSettingsController::class, 'updateSystemStatus']);
Route::get('/queues',           [QueueEntriesController::class, 'index']);
Route::get('/users',            [UserController::class, 'index']);

Route::get('/reason-categories',        [ReasonCategoryController::class, 'index'])->name('reason-categories.index');
Route::get('/reason-categories/{id}',   [ReasonCategoryController::class, 'show'])->name('reason-categories.show');

Route::get('/doctors',                  [DoctorsController::class, 'index']);
Route::get('/schedules',                [SchedulesController::class, 'index']);
Route::get('/doctor-schedule',          [DoctorScheduleController::class, 'index']);

Route::get('/emergency-encounters',     [EmergencyEncounterController::class, 'index'])->name('emergency-encounters.index');

// Private API Routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('users.logout');

    Route::controller(UserController::class)->group(function () {
        Route::get('/users/{id}',           'show');
        Route::put('/users/{id}',           'update')->name('users.update');
        Route::delete('/users/{id}',        'destroy');
    });

    Route::controller(QueueEntriesController::class)->group(function () {
        Route::get('/queues/{id}',          'show');
        Route::post('/queues',              'store')->name('queues.store');
        Route::put('/queues/status/{id}',   'updateQueueStatus')->name('queues.update.status');
        Route::put('/queues/reason/{id}',   'updateQueueReason')->name('queues.update.reason');
        Route::put('/queues/admin/{id}',    'adminUpdateQueue')->name('queues.admin.update');
        Route::delete('/queues/{id}',       'destroy');
    });

    Route::controller(DoctorsController::class)->group(function () {
        Route::post('/doctors',         'store')->name('doctors.store');
        Route::get('/doctors/{id}',     'show');
        Route::put('/doctors/{id}',     'update')->name('doctors.update');
        Route::delete('/doctors/{id}',  'destroy');
    });

    Route::controller(EmergencyEncounterController::class)->group(function () {
        Route::post('/emergency-encounters',        'store')->name('emergency-encounters.store');
        Route::get('/emergency-encounters/{id}',    'show')->name('emergency-encounters.show');
        Route::put('/emergency-encounters/{id}',    'update')->name('emergency-encounters.update');
        Route::delete('/emergency-encounters/{id}', 'destroy')->name('emergency-encounters.destroy');
    });
    
}); 




