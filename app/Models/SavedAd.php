<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedAd extends Model
{
    protected $fillable = [
        'user_id',
        'ad_id',
    ];

    /**
     * L'utilisateur qui a sauvegardé l'annonce
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * L'annonce sauvegardée
     */
    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }
}
