<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NewsApi\NewsApiService;
use App\Services\GuardiansApi\GuardiansApiService;
use App\Services\NewsApi\NewsRepository\NewsApiRepository;
use App\Services\GuardiansApi\GuardianRepository\GuardianRepository; // Update this line

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register NewsApiService
        $this->app->singleton(
            NewsApiService::class,
            fn (): NewsApiRepository => new NewsApiRepository(),
        );

        // Register GuardiansApiService
        $this->app->singleton(
            GuardiansApiService::class,
            fn (): GuardiansApiService => new GuardiansApiService(new GuardianRepository()),
        );
    }

    public function boot()
    {
        //
    }
}
