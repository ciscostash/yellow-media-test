<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\CompanyService;
use App\Services\UserService;
use App\Interfaces\CompanyServiceInterface;
use App\Interfaces\UserServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RepositoryServiceProvider::class);

        $this->app->bind(CompanyServiceInterface::class, CompanyService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }
}
