<?php

namespace App\Providers;

use App\Events\NotificationEvent;
use App\Listeners\SendFirebaseNotification;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NotificationEvent::class => [
            SendFirebaseNotification::class,
        ],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
