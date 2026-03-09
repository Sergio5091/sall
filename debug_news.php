<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG NEWS IMAGES ===\n";

use App\Models\News;

// Récupérer la dernière news
$news = News::where('is_active', true)->orderBy('created_at', 'desc')->first();

if ($news) {
    echo "News trouvée:\n";
    echo "ID: " . $news->id . "\n";
    echo "Titre: " . $news->title . "\n";
    echo "Image BDD: " . ($news->image ?? 'NULL') . "\n";
    
    // Test de la logique du contrôleur
    $imagePath = $news->image ? 'news/' . $news->image : null;
    echo "Chemin relatif: " . ($imagePath ?? 'NULL') . "\n";
    
    $fullPath = $imagePath ? public_path('uploads/' . $imagePath) : null;
    echo "Chemin complet: " . ($fullPath ?? 'NULL') . "\n";
    echo "Fichier existe: " . ($fullPath && file_exists($fullPath) ? 'OUI' : 'NON') . "\n";
    
    if ($imagePath && $fullPath && file_exists($fullPath)) {
        $imageUrl = url('uploads/' . $imagePath);
        echo "URL finale: " . $imageUrl . "\n";
    } else {
        $imageUrl = 'https://picsum.photos/seed/news-' . $news->id . '/400/300.jpg';
        echo "URL fallback: " . $imageUrl . "\n";
    }
    
    // Vérifier le contenu du dossier uploads/news
    echo "\nContenu de public/uploads/news/:\n";
    $newsDir = 'public/uploads/news/';
    if (is_dir($newsDir)) {
        $files = scandir($newsDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "- " . $file . "\n";
            }
        }
    } else {
        echo "Dossier n'existe pas\n";
    }
} else {
    echo "Aucune news trouvée\n";
}

echo "\n=== FIN DEBUG ===\n";
