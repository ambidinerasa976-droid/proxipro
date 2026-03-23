<?php

namespace App\Notifications;

use App\Models\IdentityVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VerificationApproved extends Notification
{
    use Queueable;

    protected $verification;

    public function __construct(IdentityVerification $verification)
    {
        $this->verification = $verification;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'verification_approved',
            'verification_id' => $this->verification->id,
            'title' => 'Profil vérifié avec succès !',
            'message' => 'Félicitations ! Votre profil a été vérifié. Le badge « Vérifié » est maintenant visible sur votre profil.',
            'icon' => 'fas fa-check-circle',
            'color' => '#10b981',
            'action_url' => route('profile.show'),
            'action_label' => 'Voir mon profil',
        ];
    }
}
