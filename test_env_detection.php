<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test de détection d'environnement ===\n";

echo "Environnement: " . app()->environment() . "\n";
echo "Est local: " . (app()->environment('local') ? 'OUI' : 'NON') . "\n";

$storageLink = public_path('storage');
echo "Chemin du lien storage: " . $storageLink . "\n";
echo "Lien symbolique existe: " . (is_link($storageLink) ? 'OUI' : 'NON') . "\n";

if (is_link($storageLink)) {
    echo "Cible du lien: " . readlink($storageLink) . "\n";
}

// Test avec une salle
use App\Models\Salle;
$salle = Salle::where('valide', 1)->where('statut', 'actif')->first();

if ($salle) {
    echo "\nTest avec salle: " . $salle->nom . "\n";
    $imageUrl = $salle->image_url;
    
    if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
        if (app()->environment('local') && is_link(public_path('storage'))) {
            $finalUrl = '/storage/' . $imageUrl;
            echo "Mode local + storage:link: " . $finalUrl . "\n";
        } else {
            $finalUrl = url('storage/' . $imageUrl);
            echo "Mode production/route PHP: " . $finalUrl . "\n";
        }
    }
}

echo "\n=== Test terminé ===\n";
