<?php

namespace Tests\Unit\Services;

use App\Services\FirebaseService;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Config;
use Mockery;
use Tests\TestCase;


class FirebaseServiceTest extends TestCase
{
    protected FirebaseService $firebaseService;
    protected array $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->initializeConfig();
        $this->initializeTestData();
        $this->initializeFirebaseService();
    }

    /**
     * Initialize Firebase configuration for testing.
     */
    protected function initializeConfig(): void
    {
        $credentialsPath = Config::get("firebase.projects.app.credentials");

        Config::set('firebase.projects.app.credentials', $credentialsPath);
        Config::set('firebase.projects.test_app.credentials', $credentialsPath);
    }

    /**
     * Set up test data for notifications.
     */
    protected function initializeTestData(): void
    {
        // Please replace Device-FCM-TOKEN with your testing device fcm token
        $this->data = [
            'token' => 'Device-FCM-TOKEN',
            'title' => 'Notification Title',
            'body' => 'Notification Body',
            'extraData' => [
                'dept' => null,
                'date' => '2024-10-11',
                'sound' => 'default',
                'color' => '#0073B3',
                'icon' => 'fcm_push_icon',
            ],
        ];
    }

    /**
     * Initialize the FirebaseService instance.
     */
    protected function initializeFirebaseService(): void
    {
        $this->firebaseService = new FirebaseService();
        $this->firebaseService->setServiceAccount('test_app');
    }

    public function testBuildMessage()
    {
        $message = $this->firebaseService->buildMessage($this->data);
        // Use Reflection to access the private properties
        $reflection = new \ReflectionClass($message);

        $this->assertInstanceOf(CloudMessage::class, $message, 'The message is not an instance of CloudMessage.');
        $this->assertMessageTarget($message, $reflection);
        $this->assertMessageNotification($message, $reflection);
        $this->assertMessageData($message, $reflection);
    }

    /**
     * Assert the target of the message.
     */
    protected function assertMessageTarget(CloudMessage $message, $reflection): void
    {
        // Access the target property
        $targetProperty = $reflection->getProperty('target');
        $targetProperty->setAccessible(true);
        $target = $targetProperty->getValue($message);

        $this->assertNotNull($target, 'The target should not be null.');
        $this->assertEquals('token', $target->type(), 'The target type is not correct.');
        $this->assertEquals($this->data['token'], $target->value(), 'The target value does not match the provided token.');
    }

    /**
     * Assert the notification details of the message.
     */
    protected function assertMessageNotification(CloudMessage $message, $reflection): void
    {
        $notificationProperty = $reflection->getProperty('notification');
        $notificationProperty->setAccessible(true);
        $notification = $notificationProperty->getValue($message);

        $this->assertNotNull($notification, 'The notification should not be null.');
        $this->assertEquals($this->data['title'], $notification->title(), 'The notification title does not match.');
        $this->assertEquals($this->data['body'], $notification->body(), 'The notification body does not match.');
    }

    /**
     * Assert the extra data of the message.
     */
    protected function assertMessageData(CloudMessage $message): void
    {
        $dataArray = $message->jsonSerialize()['data'];

        foreach ($this->data['extraData'] as $key => $value) {
            $this->assertEquals($value, $dataArray[$key], 'The extra data for '.$key.' does not match.');
        }
    }

    public function testSendNotification()
    {
        $response = $this->firebaseService->sendNotification($this->data);
        $this->assertNotNull($response['name'], 'Response is empty');
    }

    public function testSendMulticast()
    {
        // Please replace Device-FCM-Token-1, Device-FCM-Token-2 with your testing devices fcm token
        unset($this->data['token']);
        $this->data['tokens'] = ['Device-FCM-Token-1', 'Device-FCM-Token-2'];

        $response = $this->firebaseService->sendMulticast($this->data);
        $this->assertEquals([], $response->unknownTokens(), 'Unknown Tokens');
        $this->assertFalse($response->hasFailures(), 'Has failures');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
