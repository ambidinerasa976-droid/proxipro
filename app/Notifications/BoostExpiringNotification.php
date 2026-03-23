<?php

namespace App\Notifications;

use App\Models\Ad;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BoostExpiringNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Ad $ad,
        protected string $type, // 'boost' or 'urgent'
        protected int $hoursLeft
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $label = $this->type === 'urgent' ? 'mode Urgent' : 'boost';
        $icon = $this->type === 'urgent' ? '🔥' : '🚀';
        $timeText = $this->hoursLeft <= 24
            ? "moins de 24 heures"
            : "{$this->hoursLeft} heures";

        return (new MailMessage)
            ->subject("{$icon} Votre {$label} expire bientôt — {$this->ad->title}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le {$label} de votre annonce « {$this->ad->title} » expire dans {$timeText}.")
            ->line("Renouvelez-le maintenant pour continuer à bénéficier d'une visibilité maximale.")
            ->action('Renouveler mon boost', url(route('boost.show', $this->ad)))
            ->line('Merci de votre confiance !');
    }

    public function toArray(object $notifiable): array
    {
        $label = $this->type === 'urgent' ? 'mode Urgent' : 'boost';

        return [
            'title' => $this->type === 'urgent'
                ? "🔥 Mode Urgent expirant"
                : "🚀 Boost expirant",
            'message' => $this->hoursLeft <= 24
                ? "Le {$label} de « {$this->ad->title} » expire dans moins de 24h !"
                : "Le {$label} de « {$this->ad->title} » expire dans {$this->hoursLeft}h.",
            'icon' => $this->type === 'urgent' ? 'fas fa-fire' : 'fas fa-rocket',
            'color' => $this->type === 'urgent' ? '#ef4444' : '#f59e0b',
            'action_url' => route('boost.show', $this->ad),
            'ad_id' => $this->ad->id,
            'type' => $this->type,
            'hours_left' => $this->hoursLeft,
        ];
    }
}
