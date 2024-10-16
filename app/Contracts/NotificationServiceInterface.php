<?php

namespace App\Contracts;

/**
 * Interface for sending notifications via a notification service (e.g., Firebase).
 */
interface NotificationServiceInterface
{
    /**
     * Set the service account for the specified app.
     *
     * @param string $app The name of the Firebase project to load credentials for.
     * @return void
     */
    public function setServiceAccount(string $app): void;

    /**
     * Send a single notification to a device.
     *
     * @param array $data The notification data, including token, title, body, and optional extra data.
     * @return void
     */
    public function sendNotification(array $data);

    /**
     * Send a multicast notification to multiple devices.
     *
     * @param array $data The notification data, including tokens, title, body, and optional extra data.
     * @return void
     */
    public function sendMulticast(array $data);
}
