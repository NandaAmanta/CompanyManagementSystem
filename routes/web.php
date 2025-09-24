<?php

use App\Http\Middleware\CheckAuthUserGRTechEmail;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::group(['middleware' => ['auth', 'verified', CheckAuthUserGRTechEmail::class]], function () {
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [\App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard');
    });
});
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
