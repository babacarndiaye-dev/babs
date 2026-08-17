<?php

namespace App\Notifications\Concerns;

use App\Notifications\Channels\SmsChannel;
use App\Notifications\Channels\WhatsAppChannel;

trait RespectsNotificationSettings
{
    /**
     * In-app (database) notifications are always recorded; mail/WhatsApp/SMS
     * are only dispatched when the corresponding "Paramètres > Notifications"
     * toggle is on.
     *
     * @return array<int, string>
     */
    protected function enabledChannels(): array
    {
        $channels = ['database'];

        if (setting('notifications.email_enabled', true)) {
            $channels[] = 'mail';
        }

        if (setting('notifications.whatsapp_enabled', false)) {
            $channels[] = WhatsAppChannel::class;
        }

        if (setting('notifications.sms_enabled', false)) {
            $channels[] = SmsChannel::class;
        }

        return $channels;
    }
}
