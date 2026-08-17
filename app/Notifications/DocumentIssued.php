<?php

namespace App\Notifications;

use App\Models\Document;
use App\Notifications\Concerns\RespectsNotificationSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentIssued extends Notification
{
    use Queueable, RespectsNotificationSettings;

    public function __construct(private readonly Document $document) {}

    public function via(object $notifiable): array
    {
        return $this->enabledChannels();
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Un document est disponible')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le document « {$this->document->template->name} » (référence {$this->document->reference}) est maintenant disponible dans votre espace étudiant.")
            ->action('Télécharger le document', route('documents.pdf', $this->document))
            ->line('Merci.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Document disponible',
            'body' => $this->document->template->name.' — '.$this->document->reference,
            'url' => route('documents.pdf', $this->document),
        ];
    }

    public function toWhatsApp(object $notifiable): string
    {
        return "Votre document {$this->document->template->name} ({$this->document->reference}) est disponible dans votre espace étudiant.";
    }

    public function toSms(object $notifiable): string
    {
        return $this->toWhatsApp($notifiable);
    }
}
