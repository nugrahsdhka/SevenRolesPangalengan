<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AIProviderService;
use Illuminate\Pagination\Paginator;
use App\Services\GeminiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GeminiService::class, function ($app) {
            return new AIProviderService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('pagination.red');
    }
}
