<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Exception;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        try {
            // التأكد من وجود ملف Firebase credentials
            $credentialsPath = config('services.firebase.credentials');
            
            if (!$credentialsPath || !file_exists($credentialsPath)) {
                Log::warning('Firebase credentials file not found');
                return;
            }

            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $this->messaging = $factory->createMessaging();
        } catch (Exception $e) {
            Log::error('Firebase initialization error: ' . $e->getMessage());
        }
    }

    /**
     * إرسال إشعار Firebase
     *
     * @param string $fcmToken
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendNotification($fcmToken, $title, $body, $data = [])
    {
        if (!$this->messaging || !$fcmToken) {
            Log::warning('Firebase messaging not initialized or FCM token is null');
            return false;
        }

        try {
            $notification = Notification::create($title, $body);

            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification($notification)
                ->withData($data)
                ->withAndroidConfig(
                    AndroidConfig::fromArray([
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => 'order_messages',
                        ],
                    ])
                )
                ->withApnsConfig(
                    ApnsConfig::fromArray([
                        'payload' => [
                            'aps' => [
                                'sound' => 'default',
                                'badge' => 1,
                            ],
                        ],
                    ])
                );

            $this->messaging->send($message);
            
            Log::info("Firebase notification sent successfully to token: {$fcmToken}");
            return true;
        } catch (Exception $e) {
            Log::error("Firebase notification error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * إرسال إشعار رسالة جديدة للسائق
     *
     * @param object $driver
     * @param object $order
     * @param string $message
     * @return bool
     */
    public function sendNewMessageToDriver($driver, $order, $message)
    {
        if (!$driver || !$driver->fcm_token) {
            return false;
        }

        $title = "رسالة جديدة من العميل";
        $body = substr($message, 0, 100) . (strlen($message) > 100 ? '...' : '');

        $data = [
            'type' => 'new_message',
            'order_id' => (string) $order->id,
            'order_number' => $order->order_number,
            'sender_type' => 'user',
            'message_preview' => $body,
        ];

        return $this->sendNotification($driver->fcm_token, $title, $body, $data);
    }

    /**
     * إرسال إشعار رسالة جديدة للعميل/المستخدم
     *
     * @param object $user
     * @param object $order
     * @param string $message
     * @return bool
     */
    public function sendNewMessageToUser($user, $order, $message)
    {
        if (!$user || !$user->fcm_token) {
            return false;
        }

        $title = "رسالة جديدة من السائق";
        $body = substr($message, 0, 100) . (strlen($message) > 100 ? '...' : '');

        $data = [
            'type' => 'new_message',
            'order_id' => (string) $order->id,
            'order_number' => $order->order_number,
            'sender_type' => 'driver',
            'message_preview' => $body,
        ];

        return $this->sendNotification($user->fcm_token, $title, $body, $data);
    }

    /**
     * إرسال إشعارات متعددة
     *
     * @param array $tokens
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendMultipleNotifications(array $tokens, $title, $body, $data = [])
    {
        $results = [];
        
        foreach ($tokens as $token) {
            $results[$token] = $this->sendNotification($token, $title, $body, $data);
        }

        return $results;
    }

    /**
     * إرسال إشعار لموضوع معين (topic)
     *
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToTopic($topic, $title, $body, $data = [])
    {
        if (!$this->messaging) {
            return false;
        }

        try {
            $notification = Notification::create($title, $body);

            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification($notification)
                ->withData($data);

            $this->messaging->send($message);
            
            Log::info("Firebase notification sent to topic: {$topic}");
            return true;
        } catch (Exception $e) {
            Log::error("Firebase topic notification error: " . $e->getMessage());
            return false;
        }
    }
}
