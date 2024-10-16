<?php

namespace App\Services;

use App\Contracts\NotificationServiceInterface;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Factory;

/**
 * FirebaseService handles sending notifications via Firebase Cloud Messaging (FCM).
 *
 * @package App\Services
 */
class FirebaseService implements NotificationServiceInterface
{
    /**
     * @var \Kreait\Firebase\Messaging
     */
    protected $messaging;

    /**
     * Initialize the Firebase service with a specified app.
     *
     * @param string|null $app The Firebase project name to load the credentials from.
     * @throws \InvalidArgumentException if the credentials path is invalid.
     */
    public function setServiceAccount(?string $app = null): void
    {
        $app = $app ?? 'app';
        $credentialsPath = base_path(config("firebase.projects.{$app}.credentials"));

        if (!file_exists($credentialsPath)) {
            throw new \InvalidArgumentException("Invalid credentials path: {$credentialsPath}");
        }

        // Load the service account
        $this->messaging = (new Factory)
            ->withServiceAccount($credentialsPath)
            ->createMessaging();
    }

    /**
     * Send a single notification to a device via Firebase Cloud Messaging.
     *
     * @param array $data The notification data including 'token', 'title', 'body', and 'extraData'.
     * @return mixed The response from the Firebase service.
     * @throws FirebaseException if the notification fails to send.
     */
    public function sendNotification(array $data)
    {
        return $this->messaging->send($this->buildMessage($data));
    }

    /**
     * Send a multicast notification to multiple devices via Firebase Cloud Messaging.
     *
     * @param array $data The notification data including 'tokens', 'title', 'body', and 'extraData'.
     * @return mixed The response from the Firebase service.
     * @throws FirebaseException if the notification fails to send.
     */
    public function sendMulticast(array $data)
    {
        return $this->messaging->sendMulticast($this->buildMessage($data), $data['tokens']);
    }

    /**
     * Build a CloudMessage with notification and extra data.
     *
     * @param array $data The notification data including 'title', 'body', 'token' or 'tokens', and 'extraData'.
     * @return CloudMessage The constructed CloudMessage.
     */
    public function buildMessage(array $data): CloudMessage
    {
        $targetType = isset($data['tokens']) ? 'topic' : 'token';
        $targetValue = $data['tokens'][0] ?? $data['token'] ?? '';

        return CloudMessage::withTarget($targetType, $targetValue)
            ->withNotification(Notification::create($data['title'], $data['body']))
            ->withData($data['extraData'] ?? []);
    }
}
