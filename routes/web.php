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
    
    Route::prefix('companies')->group(function () {
        Route::get('/', [\App\Http\Controllers\Company\CompanyController::class, 'index'])->name('company');
        Route::post('/', [\App\Http\Controllers\Company\CompanyController::class, 'store'])->name('company.store');
        Route::put('/{id}', [\App\Http\Controllers\Company\CompanyController::class, 'update'])->name('company.update');
        Route::delete('/{id}', [\App\Http\Controllers\Company\CompanyController::class, 'destroy'])->name('company.destroy');
    });

    Route::prefix('employees')->group(function () {
        Route::get('/', [\App\Http\Controllers\Employee\EmployeeController::class, 'index'])->name('employee');
        Route::post('/', [\App\Http\Controllers\Employee\EmployeeController::class, 'store'])->name('employee.store');
        Route::put('/{id}', [\App\Http\Controllers\Employee\EmployeeController::class, 'update'])->name('employee.update');
        Route::delete('/{id}', [\App\Http\Controllers\Employee\EmployeeController::class, 'destroy'])->name('employee.destroy');
    });
});
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
