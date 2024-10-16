<?php

namespace App\Http\Controllers\API\V1;

use App\Events\NotificationEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\MulticastNotificationRequest;
use App\Http\Requests\SingleNotificationRequest;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Class NotificationController
 *
 * Handles sending notifications to one or multiple recipients via events.
 */
class NotificationController extends Controller
{
    /**
     * Send a notification to a single recipient.
     *
     * @param SingleNotificationRequest $request The HTTP request containing notification details.
     * @return JsonResponse
     */
    public function sendNotification(SingleNotificationRequest $request): JsonResponse
    {
        return $this->triggerNotification($request->app, $request->validated(), 'Successfully notification sent!');
    }

    /**
     * Send notifications to multiple recipients (Multicast).
     *
     * @param MulticastNotificationRequest $request The HTTP request containing multicast notification details.
     * @return JsonResponse
     */
    public function sendMulticastNotification(MulticastNotificationRequest $request): JsonResponse
    {
        return $this->triggerNotification($request->app, $request->validated(), 'Successfully multicast notification sent!');
    }

    /**
     * Trigger a notification event and handle potential exceptions.
     *
     * @param string $app The application identifier.
     * @param array $data The validated notification data.
     * @param string $successMessage The success message to return upon successful sending.
     * @return JsonResponse
     */
    private function triggerNotification(?string $app = null, array $data, string $successMessage): JsonResponse
    {
        try {

            // Trigger event for sending the notification
            event(new NotificationEvent($app, $data));
            return response()->json(['success' => true, 'message' => $successMessage], 200);
        } catch (FirebaseException $e) {

            // Log the error with contextual information
            Log::error('Notification failed for app ' . $app . ': ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send notification.'], 500);
        }
    }
}
