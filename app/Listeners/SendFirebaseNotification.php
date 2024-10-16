<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use FireBaseNotification;

/**
 * Class SendFirebaseNotification
 *
 * Listens for NotificationEvent and sends notifications via Firebase.
 */
class SendFirebaseNotification
{
    /**
     * Handle the incoming notification event.
     *
     * @param NotificationEvent $event The event containing notification data.
     * @return void
     */
    public function handle(NotificationEvent $event): void
    {
        // Set the Firebase project using the project identifier from the event.
        FireBaseNotification::setServiceAccount($event->app);

        // Check if the request is for multicast or single notification and send accordingly.
        if ($this->isMulticast($event->data)) {
            $this->sendMulticastNotification($event->data);
        } else {
            $this->sendSingleNotification($event->data);
        }
    }

    /**
     * Determine if the event data indicates a multicast notification.
     *
     * @param array $data The notification data.
     * @return bool True if it is a multicast notification, false otherwise.
     */
    protected function isMulticast(array $data): bool
    {
        return isset($data['tokens']);
    }

    /**
     * Send a multicast notification using Firebase.
     *
     * @param array $data The notification data, including tokens, title, body, and optional extra data.
     * @return void
     */
    protected function sendMulticastNotification(array $data): void
    {
        FireBaseNotification::sendMulticast($data);
    }

    /**
     * Send a single notification using Firebase.
     *
     * @param array $data The notification data, including token, title, body, and optional extra data.
     * @return void
     */
    protected function sendSingleNotification(array $data): void
    {
        FireBaseNotification::sendNotification($data);
    }
}
