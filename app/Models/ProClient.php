<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProClient extends Model
{
    protected $fillable = [
        'provider_id', 'client_user_id', 'name', 'email', 'phone',
        'address', 'city', 'company', 'notes', 'status', 'source',
        'total_revenue', 'total_projects', 'last_interaction_at',
    ];

    protected $casts = [
        'total_revenue' => 'decimal:2',
        'last_interaction_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function clientUser()
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }

    public function quotes()
    {
        return $this->hasMany(ProQuote::class, 'client_id');
    }

    public function invoices()
    {
        return $this->hasMany(ProInvoice::class, 'client_id');
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'prospect' => 'Prospect',
            'active' => 'Actif',
            'completed' => 'Terminé',
            'archived' => 'Archivé',
            default => 'Inconnu',
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'prospect' => 'warning',
            'active' => 'success',
            'completed' => 'primary',
            'archived' => 'secondary',
            default => 'secondary',
        };
    }
}
