<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Salle;

echo "=== Test des images des salles ===\n";

$salles = Salle::where('valide', 1)->where('statut', 'actif')->take(3)->get();

foreach ($salles as $salle) {
    echo "\nNom: " . $salle->nom . "\n";
    echo "Image Couverture: " . ($salle->image_couverture ?? 'null') . "\n";
    echo "Image URL (brute): " . ($salle->getAttribute('image_url') ?? 'null') . "\n";
    echo "Image URL (accesseur): " . $salle->image_url . "\n";
    echo "-------------------\n";
}
