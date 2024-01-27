<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BaseUrlServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('base_url', function () {
            return 'https://www.masakapahariini.com//'; // Default BaseURL
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
