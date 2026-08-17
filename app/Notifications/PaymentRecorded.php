<?php

namespace App\Notifications;

use App\Models\Payment;
use App\Notifications\Concerns\RespectsNotificationSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRecorded extends Notification
{
    use Queueable, RespectsNotificationSettings;

    public function __construct(private readonly Payment $payment) {}

    public function via(object $notifiable): array
    {
        return $this->enabledChannels();
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amount = number_format((float) $this->payment->amount, 0, ',', ' ').' '.setting('finance.currency');

        return (new MailMessage)
            ->subject('Paiement enregistré')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Un paiement de {$amount} a été enregistré sur la facture {$this->payment->invoice->invoice_number}.")
            ->action('Voir mes paiements', route('student.payments'))
            ->line('Merci.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Paiement enregistré',
            'body' => number_format((float) $this->payment->amount, 0, ',', ' ').' '.setting('finance.currency').' — Facture '.$this->payment->invoice->invoice_number,
            'url' => route('student.payments'),
        ];
    }

    public function toWhatsApp(object $notifiable): string
    {
        return 'Paiement de '.number_format((float) $this->payment->amount, 0, ',', ' ').' '.setting('finance.currency').' bien reçu. Merci.';
    }

    public function toSms(object $notifiable): string
    {
        return $this->toWhatsApp($notifiable);
    }
}
