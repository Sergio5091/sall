<?php

use App\Models\Salle;
use App\Models\User;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Vérification des salles ===\n";

// Afficher tous les utilisateurs
echo "\n1. Utilisateurs dans la base :\n";
$users = User::all();
foreach ($users as $user) {
    echo "ID: {$user->id}, Nom: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
}

// Afficher toutes les salles
echo "\n2. Salles dans la base :\n";
$salles = Salle::all();
foreach ($salles as $salle) {
    echo "ID: {$salle->id}, Nom: {$salle->nom}, Promoter ID: {$salle->promoter_id}\n";
}

// Vérifier l'utilisateur connecté actuel
echo "\n3. Salles par utilisateur :\n";
foreach ($users as $user) {
    $userSalles = Salle::where('promoter_id', $user->id)->get();
    echo "User {$user->id} ({$user->name}): {$userSalles->count()} salle(s)\n";
    foreach ($userSalles as $salle) {
        echo "  - {$salle->nom}\n";
    }
}

echo "\n=== Fin de la vérification ===\n";
