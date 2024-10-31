<?php

namespace App\Providers;

use App\Services\NewsApi\NewsApiService;
use App\Services\NewsApi\NewsRepository\NewsApiRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // news Api
        $this->app->singleton(
            abstract: NewsApiService::class,
            concrete: fn (): NewsApiRepository => new NewsApiRepository(),
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
