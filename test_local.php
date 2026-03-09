<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST LOCAL COMPLET ===\n";

// Test 1: Vérifier le fichier image
$testFile = 'salles/bannieres/UlvdiI7BZRq0VtH7fDnrucL5C8MCRk1Jyyub7Mz.jpg';
$fullPath = __DIR__ . '/storage/app/public/' . $testFile;

echo "1. Fichier image:\n";
echo "   Chemin: " . $fullPath . "\n";
echo "   Existe: " . (file_exists($fullPath) ? '✅ OUI' : '❌ NON') . "\n";

if (file_exists($fullPath)) {
    echo "   Taille: " . filesize($fullPath) . " octets\n";
}

// Test 2: Simuler la route storage
echo "\n2. Test route storage:\n";
$path = $testFile;
$filePath = storage_path('app/public/' . $path);

if (file_exists($filePath)) {
    echo "   ✅ Route trouverait le fichier\n";
    echo "   URL locale: http://localhost/storage/" . $path . "\n";
} else {
    echo "   ❌ Route ne trouverait pas le fichier\n";
}

// Test 3: Simuler le contrôleur
echo "\n3. Test contrôleur:\n";
use App\Models\Salle;
$salle = Salle::where('valide', 1)->where('statut', 'actif')->first();

if ($salle) {
    $imageUrl = $salle->image_url;
    echo "   Image BDD: " . ($imageUrl ?? 'NULL') . "\n";
    
    if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
        $finalUrl = 'https://youpihub.com/storage/' . $imageUrl;
        echo "   URL finale: " . $finalUrl . "\n";
    }
}

// Test 4: Démarrer le serveur et tester
echo "\n4. Instructions:\n";
echo "   Lancez: php artisan serve\n";
echo "   Testez: http://localhost/storage/" . $testFile . "\n";
echo "   Devrait afficher l'image si tout fonctionne\n";

echo "\n=== FIN TEST ===\n";
