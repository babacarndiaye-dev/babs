<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

/**
 * Placeholder channel: no WhatsApp Business API is wired up yet. When the
 * setting is on, this logs what would have been sent instead of silently
 * pretending delivery succeeded — swap the log call for a real API client
 * once credentials exist.
 */
class WhatsAppChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $phone = $notifiable->routeNotificationForWhatsApp() ?? null;

        if (! $phone) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);

        Log::info('[WhatsApp placeholder] Message not sent — no gateway configured.', [
            'to' => $phone,
            'message' => $message,
        ]);
    }
}
