<?php

require_once 'vendor/autoload.php';

use App\Models\Salle;
use Illuminate\Http\Request;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$controller = new App\Http\Controllers\Admin\SalleController();
$request = new Request();

try {
    $result = $controller->index($request);
    echo "Contrôleur exécuté avec succès\n";

    // Récupérer les données directement
    $query = Salle::with('promoter');
    $salles = $query->orderBy('created_at', 'desc')->paginate(10);

    echo "Nombre de salles: " . $salles->count() . "\n";
    echo "Première salle ID: " . ($salles->first()?->id ?? 'NULL') . "\n";
    echo "Données de pagination:\n";
    print_r($salles->toArray());

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}