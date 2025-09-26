<?php

namespace App\Providers;

use App\Repositories\CompanyRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\Implemetations\CompanyRepositoryImpl;
use App\Repositories\Implemetations\EmployeeRepositoryImpl;
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
