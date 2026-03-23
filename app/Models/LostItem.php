<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'category',
        'location',
        'date',
        'contact_phone',
        'reward',
        'images',
        'status',
        'views',
    ];

    protected $casts = [
        'images' => 'array',
        'date' => 'date',
        'reward' => 'decimal:2',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les objets perdus
     */
    public function scopeLost($query)
    {
        return $query->where('type', 'lost');
    }

    /**
     * Scope pour les objets trouvés
     */
    public function scopeFound($query)
    {
        return $query->where('type', 'found');
    }

    /**
     * Scope pour les objets actifs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
