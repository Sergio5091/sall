<?php

// Vérifier et créer le lien symbolique pour le storage

$publicStorage = __DIR__ . '/public/storage';
$storagePublic = __DIR__ . '/storage/app/public';

echo "=== Vérification du lien symbolique storage ===\n";

// Vérifier si le lien existe déjà
if (is_link($publicStorage)) {
    echo "✅ Le lien symbolique existe déjà\n";
    echo "Lien: " . readlink($publicStorage) . "\n";
} elseif (file_exists($publicStorage)) {
    echo "⚠️  Un fichier/répertoire existe déjà à la place du lien symbolique\n";
    echo "Supprimez le fichier/répertoire 'public/storage' manuellement et réessayez\n";
} else {
    echo "📁 Création du lien symbolique...\n";
    
    // Créer le lien symbolique
    if (symlink($storagePublic, $publicStorage)) {
        echo "✅ Lien symbolique créé avec succès!\n";
        echo "Lien: $publicStorage -> $storagePublic\n";
    } else {
        echo "❌ Erreur lors de la création du lien symbolique\n";
        echo "Vérifiez les permissions ou exécutez: php artisan storage:link\n";
    }
}

// Vérifier si les répertoires d'images existent
$eventsDir = $storagePublic . '/events';
$affichesDir = $eventsDir . '/affiches';
$bannieresDir = $eventsDir . '/bannieres';

echo "\n=== Vérification des répertoires d'images ===\n";

if (!is_dir($eventsDir)) {
    echo "📁 Création du répertoire events...\n";
    mkdir($eventsDir, 0755, true);
}

if (!is_dir($affichesDir)) {
    echo "📁 Création du répertoire affiches...\n";
    mkdir($affichesDir, 0755, true);
}

if (!is_dir($bannieresDir)) {
    echo "📁 Création du répertoire bannieres...\n";
    mkdir($bannieresDir, 0755, true);
}

echo "✅ Tous les répertoires sont prêts\n";

// Vérifier les permissions
echo "\n=== Vérification des permissions ===\n";
echo "storage/app/public: " . (is_readable($storagePublic) ? 'Lisible' : 'Non lisible') . "\n";
echo "public/storage: " . (is_readable($publicStorage) ? 'Lisible' : 'Non lisible') . "\n";

echo "\n=== Terminé ===\n";
