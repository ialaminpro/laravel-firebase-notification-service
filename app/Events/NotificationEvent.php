<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class NotificationEvent
 *
 * Represents a notification event to be broadcasted or handled.
 */
class NotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The notification data.
     *
     * @var array
     */
    public $data;

    /**
     * The Firebase project identifier.
     *
     * @var string
     */
    public $app;

    /**
     * Create a new event instance.
     *
     * @param string $app The identifier for the Firebase project.
     * @param array $data The notification data.
     */
    public function __construct(?string $app = null, array $data)
    {
        $this->app = $app;
        $this->data = $data;
    }
}
