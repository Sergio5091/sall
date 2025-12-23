<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== SALLES STATUTS EN BASE DE DONNÉES ===\n\n";

$salles = \App\Models\Salle::select('id', 'nom', 'statut', 'valide')->get();

foreach($salles as $salle) {
    echo "ID: {$salle->id} | Nom: {$salle->nom} | Statut: '{$salle->statut}' | Validé: " . ($salle->valide ? 'true' : 'false') . "\n";
}

echo "\n=== RÉSUMÉ DES STATUTS ===\n";
$stats = [
    'actif' => 0,
    'inactif' => 0,
    'maintenance' => 0,
    'null/autre' => 0
];

foreach($salles as $salle) {
    if ($salle->statut === 'actif') $stats['actif']++;
    elseif ($salle->statut === 'inactif') $stats['inactif']++;
    elseif ($salle->statut === 'maintenance') $stats['maintenance']++;
    else $stats['null/autre']++;
}

foreach($stats as $statut => $count) {
    echo "{$statut}: {$count}\n";
}

echo "\n=== SALLES NON VALIDÉES ===\n";
$nonValides = $salles->where('valide', false);
foreach($nonValides as $salle) {
    echo "ID: {$salle->id} | Nom: {$salle->nom} | Statut: '{$salle->statut}'\n";
}
