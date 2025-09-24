<?php

namespace App\Providers;

use App\Http\Repositories\CompanyRepository;
use App\Http\Repositories\EmployeeRepository;
use App\Http\Repositories\Implemetations\CompanyRepositoryImpl;
use App\Http\Repositories\Implemetations\EmployeeRepositoryImpl;
use Illuminate\Support\ServiceProvider;

class RepositoryRegistrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CompanyRepository::class , CompanyRepositoryImpl::class);
        $this->app->singleton(EmployeeRepository::class , EmployeeRepositoryImpl::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
