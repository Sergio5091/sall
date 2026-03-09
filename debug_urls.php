<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Debug URLs ===\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "URL générée: " . url('storage/test.jpg') . "\n";

// Test avec une vraie salle
use App\Models\Salle;
$salle = Salle::first();
if ($salle) {
    echo "\nSalle: " . $salle->nom . "\n";
    echo "Image URL BDD: " . $salle->image_url . "\n";
    
    $imageUrl = $salle->image_url;
    if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
        $baseUrl = config('app.url') === 'http://localhost' 
            ? 'https://votredomaine.com'
            : config('app.url');
        $finalUrl = $baseUrl . '/storage/' . $imageUrl;
        echo "URL finale: " . $finalUrl . "\n";
    }
}

echo "\n=== Fin debug ===\n";
