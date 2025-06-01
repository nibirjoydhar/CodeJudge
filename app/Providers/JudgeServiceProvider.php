<?php

namespace App\Providers;

use App\Services\Judge0Service;
use Illuminate\Support\ServiceProvider;

class JudgeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Judge0Service::class, function ($app) {
            return new Judge0Service();
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