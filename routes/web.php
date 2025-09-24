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
        Route::get('/', [\App\Http\Controllers\Management\CompanyController::class, 'index'])->name('companies.index');
        Route::get('/{id}', [\App\Http\Controllers\Management\CompanyController::class, 'show'])->name('companies.show');
        Route::post('/', [\App\Http\Controllers\Management\CompanyController::class, 'store'])->name('companies.store');
        Route::put('/{id}', [\App\Http\Controllers\Management\CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/{id}', [\App\Http\Controllers\Management\CompanyController::class, 'destroy'])->name('companies.destroy');
    });

    Route::prefix('employees')->group(function () {
        Route::get('/', [\App\Http\Controllers\Management\EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/{id}', [\App\Http\Controllers\Management\EmployeeController::class, 'show'])->name('employees.show');
        Route::post('/', [\App\Http\Controllers\Management\EmployeeController::class, 'store'])->name('employees.store');
        Route::put('/{id}', [\App\Http\Controllers\Management\EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/{id}', [\App\Http\Controllers\Management\EmployeeController::class, 'destroy'])->name('employees.destroy');
    });
});
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
