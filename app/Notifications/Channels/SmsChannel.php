<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

/**
 * Placeholder channel: no SMS gateway is wired up yet. Logs the intended
 * message instead of pretending delivery succeeded — swap for a real
 * provider (Orange, Twilio, ...) once one is chosen.
 */
class SmsChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toSms')) {
            return;
        }

        $phone = $notifiable->routeNotificationForSms() ?? null;

        if (! $phone) {
            return;
        }

        $message = $notification->toSms($notifiable);

        Log::info('[SMS placeholder] Message not sent — no gateway configured.', [
            'to' => $phone,
            'message' => $message,
        ]);
    }
}
