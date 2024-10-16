<?php

namespace App\Providers;

use App\Contracts\NotificationServiceInterface;
use App\Services\FirebaseService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the NotificationServiceInterface to FirebaseService
        $this->app->bind(NotificationServiceInterface::class, FirebaseService::class);

        // Bind a service alias for the Facade
        $this->app->singleton('notification.service', function ($app) {
            return $app->make(NotificationServiceInterface::class);
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
