<?php

echo "=== Migration des images vers public/uploads ===\n";

// Créer les dossiers nécessaires
$directories = [
    'public/uploads',
    'public/uploads/salles',
    'public/uploads/salles/bannieres',
    'public/uploads/salles/galerie',
    'public/uploads/events',
    'public/uploads/events/affiches',
    'public/uploads/news',
    'public/uploads/products'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "✅ Créé: $dir\n";
    } else {
        echo "ℹ️  Existe déjà: $dir\n";
    }
}

// Copier les images de storage vers public
$sourceBase = 'storage/app/public';
$targetBase = 'public/uploads';

// Copier les images des salles
if (is_dir($sourceBase . '/salles/bannieres')) {
    $files = glob($sourceBase . '/salles/bannieres/*');
    foreach ($files as $file) {
        $filename = basename($file);
        $target = $targetBase . '/salles/bannieres/' . $filename;
        if (!file_exists($target)) {
            copy($file, $target);
            echo "✅ Copié: salles/bannieres/$filename\n";
        }
    }
}

// Copier les images des événements
if (is_dir($sourceBase . '/events/affiches')) {
    $files = glob($sourceBase . '/events/affiches/*');
    foreach ($files as $file) {
        $filename = basename($file);
        $target = $targetBase . '/events/affiches/' . $filename;
        if (!file_exists($target)) {
            copy($file, $target);
            echo "✅ Copié: events/affiches/$filename\n";
        }
    }
}

// Copier les images des news
if (is_dir($sourceBase . '/news')) {
    $files = glob($sourceBase . '/news/*');
    foreach ($files as $file) {
        $filename = basename($file);
        $target = $targetBase . '/news/' . $filename;
        if (!file_exists($target)) {
            copy($file, $target);
            echo "✅ Copié: news/$filename\n";
        }
    }
}

echo "\n=== Migration terminée ===\n";
echo "📁 Les images sont maintenant dans: public/uploads/\n";
echo "🌐 URLs: http://youpihub.com/uploads/...\n";
