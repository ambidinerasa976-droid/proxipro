<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'description',
        'status',
        'stripe_session_id',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * L'utilisateur associé à la transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transactions de type achat de points
     */
    public function scopePoints($query)
    {
        return $query->where('type', 'POINTS');
    }

    /**
     * Transactions de type crédits documents (devis/factures)
     */
    public function scopeDocumentCredits($query)
    {
        return $query->where('type', 'DOCUMENT_CREDITS');
    }

    /**
     * Transactions de type abonnement
     */
    public function scopeSubscription($query)
    {
        return $query->where('type', 'SUBSCRIPTION');
    }

    /**
     * Transactions complétées
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
