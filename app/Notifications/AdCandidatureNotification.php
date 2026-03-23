<?php

namespace App\Notifications;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdCandidatureNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Ad $ad,
        protected User $candidate,
        protected ?string $message = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("📩 Nouvelle candidature pour votre annonce : {$this->ad->title}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("**{$this->candidate->name}** est intéressé(e) par votre annonce « {$this->ad->title} ».");

        if ($this->message) {
            $mail->line("**Message :** {$this->message}");
        }

        $mail->action('Voir l\'annonce', url(route('ads.show', $this->ad)))
             ->line('Vous pouvez contacter cette personne via la messagerie de la plateforme.')
             ->line('Merci de votre confiance !');

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'ad_candidature',
            'title' => '📩 Nouvelle candidature',
            'message' => "{$this->candidate->name} est intéressé(e) par votre annonce « {$this->ad->title} »",
            'candidate_message' => $this->message,
            'icon' => 'fas fa-hand-paper',
            'color' => '#3b82f6',
            'action_url' => route('ads.show', $this->ad),
            'ad_id' => $this->ad->id,
            'candidate_id' => $this->candidate->id,
            'candidate_name' => $this->candidate->name,
            'candidate_avatar' => $this->candidate->avatar,
        ];
    }
}
