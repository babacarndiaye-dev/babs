<?php

namespace App\Notifications;

use App\Models\ReportCard;
use App\Notifications\Concerns\RespectsNotificationSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportCardPublished extends Notification
{
    use Queueable, RespectsNotificationSettings;

    public function __construct(private readonly ReportCard $reportCard) {}

    public function via(object $notifiable): array
    {
        return $this->enabledChannels();
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Votre bulletin — {$this->reportCard->period}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre bulletin pour la période « {$this->reportCard->period} » est disponible.")
            ->when($this->reportCard->general_average !== null, fn ($mail) => $mail->line("Moyenne générale : {$this->reportCard->general_average}/20."))
            ->action('Voir mon bulletin', route('report-cards.pdf', $this->reportCard))
            ->line('Bonne continuation.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Bulletin publié',
            'body' => $this->reportCard->period.' — Moyenne '.($this->reportCard->general_average ?? '—').'/20',
            'url' => route('report-cards.pdf', $this->reportCard),
        ];
    }

    public function toWhatsApp(object $notifiable): string
    {
        return "Votre bulletin ({$this->reportCard->period}) est disponible dans votre espace étudiant.";
    }

    public function toSms(object $notifiable): string
    {
        return $this->toWhatsApp($notifiable);
    }
}
