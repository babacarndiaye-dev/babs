<?php

namespace App\Notifications;

use App\Models\Application;
use App\Notifications\Concerns\RespectsNotificationSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification
{
    use Queueable, RespectsNotificationSettings;

    private const LABELS = [
        'nouveau' => 'Nouveau',
        'en_etude' => 'En étude',
        'incomplet' => 'Incomplet',
        'pieces_complementaires_demandees' => 'Pièces complémentaires demandées',
        'admis' => 'Admis(e)',
        'refuse' => 'Refusé(e)',
        'inscrit' => 'Inscrit(e)',
    ];

    public function __construct(private readonly Application $application) {}

    public function via(object $notifiable): array
    {
        return $this->enabledChannels();
    }

    public function toMail(object $notifiable): MailMessage
    {
        $label = self::LABELS[$this->application->status] ?? $this->application->status;

        return (new MailMessage)
            ->subject("Mise à jour de votre candidature — {$this->application->application_number}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le statut de votre candidature {$this->application->application_number} a été mis à jour : **{$label}**.")
            ->when($this->application->status === 'admis', fn ($mail) => $mail->line('Félicitations ! Consultez votre espace candidat pour la suite du processus.'))
            ->action('Suivre ma candidature', route('candidate.dashboard'))
            ->line('Merci de votre confiance.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Candidature mise à jour',
            'body' => 'Statut : '.(self::LABELS[$this->application->status] ?? $this->application->status),
            'url' => route('candidate.dashboard'),
        ];
    }

    public function toWhatsApp(object $notifiable): string
    {
        return "Votre candidature {$this->application->application_number} est maintenant : ".(self::LABELS[$this->application->status] ?? $this->application->status);
    }

    public function toSms(object $notifiable): string
    {
        return $this->toWhatsApp($notifiable);
    }
}
