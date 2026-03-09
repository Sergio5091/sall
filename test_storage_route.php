<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Salle;

echo "=== Test de la route storage ===\n";

// Test 1: Vérifier une salle avec image locale
$salle = Salle::where('valide', 1)->where('statut', 'actif')->first();

if ($salle) {
    echo "\nSalle trouvée: " . $salle->nom . "\n";
    echo "Image URL (brute): " . ($salle->image_url ?? 'null') . "\n";
    
    $imageUrl = $salle->image_url;
    
    // Simuler la logique du contrôleur
    if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
        $fullUrl = url('storage/' . $imageUrl);
        echo "URL complète: " . $fullUrl . "\n";
        
        // Vérifier si le fichier existe
        $filePath = storage_path('app/public/' . $imageUrl);
        echo "Chemin du fichier: " . $filePath . "\n";
        echo "Fichier existe: " . (file_exists($filePath) ? 'OUI' : 'NON') . "\n";
    } else {
        echo "URL externe ou nulle\n";
    }
} else {
    echo "Aucune salle trouvée\n";
}

echo "\n=== Test terminé ===\n";
