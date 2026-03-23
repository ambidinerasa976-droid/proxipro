<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreeQuoteUsage extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'fingerprint',
        'document_type',
    ];

    /**
     * Vérifie si une IP ou un fingerprint a déjà utilisé l'essai gratuit.
     */
    public static function hasUsedFreeTrial(?int $userId, string $ip, ?string $fingerprint): bool
    {
        return self::where(function ($query) use ($userId, $ip, $fingerprint) {
            $query->where('ip_address', $ip);

            if ($fingerprint) {
                $query->orWhere('fingerprint', $fingerprint);
            }

            if ($userId) {
                $query->orWhere('user_id', $userId);
            }
        })->exists();
    }

    /**
     * Enregistre l'utilisation d'un essai gratuit.
     */
    public static function recordUsage(?int $userId, string $ip, ?string $fingerprint, string $documentType): self
    {
        return self::create([
            'user_id' => $userId,
            'ip_address' => $ip,
            'fingerprint' => $fingerprint,
            'document_type' => $documentType,
        ]);
    }
}
