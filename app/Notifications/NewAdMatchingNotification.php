<?php

namespace App\Notifications;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAdMatchingNotification extends Notification
{
    use Queueable;

    protected Ad $ad;
    protected User $publisher;

    public function __construct(Ad $ad, User $publisher)
    {
        $this->ad = $ad;
        $this->publisher = $publisher;
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->pro_notifications_email) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $serviceType = $this->ad->service_type === 'demande' ? 'demande de service' : 'offre de service';
        $adUrl = url('/ads/' . $this->ad->id);

        return (new MailMessage)
            ->subject('📌 Nouvelle ' . $serviceType . ' dans votre domaine — ' . $this->ad->category)
            ->greeting('Bonjour ' . $notifiable->name . ' 👋')
            ->line('Une nouvelle publication correspondant à votre domaine d\'activité vient d\'être ajoutée sur ProxiPro.')
            ->line('')
            ->line('**' . $this->ad->title . '**')
            ->line('📂 Catégorie : ' . $this->ad->category)
            ->line('📍 Lieu : ' . ($this->ad->location ?? 'Non précisé'))
            ->line(($this->ad->price ? '💰 Budget : ' . number_format($this->ad->price, 0, ',', ' ') . ' €' : ''))
            ->line('')
            ->line('Publié par **' . $this->publisher->name . '**')
            ->action('Voir l\'annonce et postuler', $adUrl)
            ->line('')
            ->line('Vous recevez cet email car vos compétences correspondent à la catégorie « ' . $this->ad->category . ' ». Vous pouvez gérer vos préférences de notification depuis votre Espace Pro.')
            ->salutation('L\'équipe ProxiPro');
    }

    public function toArray(object $notifiable): array
    {
        $serviceType = $this->ad->service_type === 'demande' ? 'demande' : 'offre';

        return [
            'type' => 'new_ad_matching',
            'icon' => 'fas fa-bullhorn',
            'color' => '#3a86ff',
            'title' => 'Nouvelle ' . $serviceType . ' : ' . $this->ad->category,
            'message' => $this->publisher->name . ' a publié « ' . \Illuminate\Support\Str::limit($this->ad->title, 60) . ' » à ' . ($this->ad->location ?? 'lieu non précisé'),
            'action_url' => '/ads/' . $this->ad->id,
            'ad_id' => $this->ad->id,
            'publisher_id' => $this->publisher->id,
        ];
    }
}
