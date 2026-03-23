<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'position',
        'priority',
        'is_active',
        'starts_at',
        'ends_at',
        'clicks',
        'impressions',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Récupérer les publicités actives pour une position donnée
     */
    public static function getActive(string $position = 'sidebar', int $limit = 3)
    {
        return self::where('is_active', true)
            ->where('position', $position)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->orderBy('priority', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Incrémenter le compteur de clics
     */
    public function incrementClicks()
    {
        $this->increment('clicks');
    }

    /**
     * Incrémenter le compteur d'impressions
     */
    public function incrementImpressions()
    {
        $this->increment('impressions');
    }
}
