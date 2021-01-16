<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Notification as FCMNotification;
use Exception;
use Illuminate\Support\Facades\Log;

class FcmChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $payload = $notification->toFcm($notifiable);

        $client = new Client();
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        $client->setApiKey(env('FCM_SERVER_KEY'));

        $message = new Message();
        $message->setPriority($payload['priority']);
        
        foreach ($notifiable->devices()->withPushToken()->get() as $device) {
            $message->addRecipient(new Device($device->push_token));
        }

        $notification = new FCMNotification($payload['title'], $payload['body']);
        $notification->setSound('default');
        $notification->setBadge(1);

        $message->setNotification($notification)
        ->setData(['sound' => 'default']);

        try {
            $client->send($message);
        } catch (Exception $e) {
            Log::error($e->getMessage());   
        }
    }
}