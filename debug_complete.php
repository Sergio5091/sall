<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG COMPLET ===\n";

// 1. Configuration
echo "APP_ENV: " . app()->environment() . "\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "URL helper: " . url('storage/test.jpg') . "\n";
echo "Asset helper: " . asset('storage/test.jpg') . "\n";

// 2. Test de salle
use App\Models\Salle;
$salle = Salle::where('valide', 1)->where('statut', 'actif')->first();

if ($salle) {
    echo "\n--- SALLE TROUVÉE ---\n";
    echo "ID: " . $salle->id . "\n";
    echo "Nom: " . $salle->nom . "\n";
    echo "Image URL BDD: " . ($salle->image_url ?? 'NULL') . "\n";
    
    // Test de la logique du contrôleur
    $imageUrl = $salle->image_url;
    if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
        $baseUrl = config('app.url') === 'http://localhost' 
            ? 'https://youpihub.com'
            : config('app.url');
        $finalUrl = $baseUrl . '/storage/' . $imageUrl;
        echo "URL finale: " . $finalUrl . "\n";
        
        // Vérifier si le fichier existe
        $filePath = storage_path('app/public/' . $imageUrl);
        echo "Chemin fichier: " . $filePath . "\n";
        echo "Fichier existe: " . (file_exists($filePath) ? 'OUI' : 'NON') . "\n";
    }
}

// 3. Test de la route
echo "\n--- TEST ROUTE ---\n";
$testPath = 'salles/bannieres/test.jpg';
$routeUrl = url('storage/' . $testPath);
echo "URL route: " . $routeUrl . "\n";

// Simuler un appel à la route
try {
    $request = \Illuminate\Http\Request::create($routeUrl);
    $response = $app->handle($request);
    echo "Statut réponse: " . $response->getStatusCode() . "\n";
} catch (Exception $e) {
    echo "Erreur route: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DEBUG ===\n";
