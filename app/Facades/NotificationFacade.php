<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class NotificationFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'notification.service';  // This will be the alias we'll bind to the service
    }
}

