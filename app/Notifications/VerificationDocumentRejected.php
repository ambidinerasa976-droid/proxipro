<?php

namespace App\Notifications;

use App\Models\IdentityVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VerificationDocumentRejected extends Notification
{
    use Queueable;

    protected $verification;
    protected $rejectedDocs;
    protected $adminMessage;

    public function __construct(IdentityVerification $verification, array $rejectedDocs, ?string $adminMessage = null)
    {
        $this->verification = $verification;
        $this->rejectedDocs = $rejectedDocs;
        $this->adminMessage = $adminMessage;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $docLabels = collect($this->rejectedDocs)->pluck('label')->implode(', ');
        
        return [
            'type' => 'verification_rejected',
            'verification_id' => $this->verification->id,
            'title' => 'Document(s) de vérification rejeté(s)',
            'message' => 'Votre demande de vérification nécessite des corrections. Document(s) concerné(s) : ' . $docLabels,
            'admin_message' => $this->adminMessage,
            'rejected_documents' => $this->rejectedDocs,
            'icon' => 'fas fa-exclamation-triangle',
            'color' => '#ef4444',
            'action_url' => route('verification.index'),
            'action_label' => 'Corriger mes documents',
        ];
    }
}
