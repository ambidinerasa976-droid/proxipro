<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('storage_url')) {
    /**
     * Génère l'URL publique d'un fichier stocké sur le disque "public".
     * Fonctionne avec le disque local (dev) ET Cloudflare R2/S3 (production).
     */
    function storage_url(?string $path): string
    {
        if (empty($path)) {
            return asset('images/default-avatar.png');
        }

        return Storage::disk('public')->url($path);
    }
}
