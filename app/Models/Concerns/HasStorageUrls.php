<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

trait HasStorageUrls
{
    /**
     * Retourne une URL complète pour un chemin de fichier stocké.
     * Si la valeur est déjà une URL absolue, la retourne telle quelle.
     */
    public function storageUrl(?string $path, ?string $fallback = null): ?string
    {
        if (!$path) {
            return $fallback;
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $disk = config('filesystems.default');

        try {
            return Storage::disk($disk)->url($path);
        } catch (\Exception $e) {
            // En cas d'erreur (disk non configuré), fallback vers l'ancienne URL publique
            return $fallback ?? '/storage/' . ltrim($path, '/');
        }
    }
}
