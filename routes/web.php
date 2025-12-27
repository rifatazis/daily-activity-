<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityController;

Route::get('/', function () {
    return session('authenticated')
        ? redirect()->route('dashboard')
        : redirect()->route('pin.form');
});

/* PIN AUTH */
Route::get('/pin', [AuthController::class, 'showPinForm'])->name('pin.form');
Route::post('/pin', [AuthController::class, 'login'])->name('pin.login');
Route::post('/pin/setup', [AuthController::class, 'setup'])->name('pin.setup');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::delete('/activity/{id}', [ActivityController::class, 'destroy'])
    ->middleware('pin');
    
/* DASHBOARD */
Route::get('/dashboard', [ActivityController::class, 'index'])
    ->name('dashboard')
    ->middleware('pin');

Route::post('/activity', [ActivityController::class, 'store'])
    ->middleware('pin');
