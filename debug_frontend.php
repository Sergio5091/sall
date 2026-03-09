<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG FRONTEND DATA ===\n";

use App\Http\Controllers\WelcomeController;
use App\Models\Salle;

// Simuler l'appel au contrôleur
$controller = new WelcomeController();
$response = $controller->index();

echo "Type de réponse: " . get_class($response) . "\n";

// Extraire les données envoyées au frontend
$data = $response->getData();
if (isset($data['props']['popularRooms'])) {
    echo "\n--- SALLES POPULAIRES ---\n";
    foreach ($data['props']['popularRooms'] as $i => $room) {
        if ($i < 3) { // Limiter à 3 pour la lisibilité
            echo "Salle " . ($i+1) . ":\n";
            echo "  Nom: " . $room['name'] . "\n";
            echo "  Image: " . $room['image'] . "\n";
            echo "  Image commence par http: " . (str_starts_with($room['image'], 'http') ? 'OUI' : 'NON') . "\n";
            echo "  Image contient storage: " . (str_contains($room['image'], 'storage') ? 'OUI' : 'NON') . "\n";
            echo "  ---\n";
        }
    }
} else {
    echo "ERREUR: Impossible de trouver les popularRooms dans la réponse\n";
}

echo "\n=== FIN DEBUG ===\n";
