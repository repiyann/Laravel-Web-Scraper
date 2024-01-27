<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ScraperService;

class ScraperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ScraperService::class, function ($app) {
            return new ScraperService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
