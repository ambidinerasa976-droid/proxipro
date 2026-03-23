<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProInvoice extends Model
{
    protected $fillable = [
        'user_id', 'client_id', 'quote_id', 'invoice_number', 'client_name',
        'client_email', 'client_phone', 'client_address', 'subject', 'description',
        'items', 'subtotal', 'tax_rate', 'tax_amount', 'total', 'status',
        'due_date', 'paid_at', 'payment_method', 'notes', 'payment_terms',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(ProClient::class, 'client_id');
    }

    public function quote()
    {
        return $this->belongsTo(ProQuote::class, 'quote_id');
    }

    public static function generateNumber($userId): string
    {
        $year = date('Y');
        $prefix = 'FAC-' . $year . '-';

        $last = self::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('invoice_number');

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
            'paid' => 'bg-success',
            'overdue' => 'bg-danger',
            'cancelled' => 'bg-warning',
            default => 'bg-secondary',
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'draft' => 'Brouillon',
            'sent' => 'Envoyée',
            'paid' => 'Payée',
            'overdue' => 'En retard',
            'cancelled' => 'Annulée',
            default => 'Inconnu',
        };
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'paid' && $this->due_date && $this->due_date->isPast();
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'sent' => 'warning',
            'paid' => 'success',
            'overdue' => 'danger',
            'cancelled' => 'secondary',
            default => 'secondary',
        };
    }
}
