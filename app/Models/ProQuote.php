<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProQuote extends Model
{
    protected $fillable = [
        'user_id', 'client_id', 'quote_number', 'client_name', 'client_email',
        'client_phone', 'client_address', 'subject', 'description', 'items',
        'subtotal', 'tax_rate', 'tax_amount', 'total', 'status',
        'valid_until', 'notes', 'conditions',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'valid_until' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(ProClient::class, 'client_id');
    }

    public function invoice()
    {
        return $this->hasOne(ProInvoice::class, 'quote_id');
    }

    public static function generateNumber($userId): string
    {
        $year = date('Y');
        $prefix = 'DEV-' . $year . '-';

        $last = self::where('quote_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('quote_number');

        $next = 1;
        if ($last) {
            $next = (int) substr($last, strlen($prefix)) + 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'draft' => 'bg-secondary',
            'sent' => 'bg-primary',
            'accepted' => 'bg-success',
            'refused' => 'bg-danger',
            'expired' => 'bg-warning',
            default => 'bg-secondary',
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'draft' => 'Brouillon',
            'sent' => 'Envoyé',
            'accepted' => 'Accepté',
            'refused' => 'Refusé',
            'expired' => 'Expiré',
            default => 'Inconnu',
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'sent', 'pending' => 'warning',
            'accepted' => 'success',
            'refused', 'rejected' => 'danger',
            'expired' => 'secondary',
            default => 'secondary',
        };
    }
}
