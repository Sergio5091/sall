<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\News;

echo "=== Vérification News ===\n";

$newsList = News::where('is_active', true)->orderBy('created_at', 'desc')->take(3)->get();

foreach ($newsList as $news) {
    echo "\nNews ID: " . $news->id . "\n";
    echo "Titre: " . $news->title . "\n";
    echo "Image en BDD: '" . ($news->image ?? 'NULL') . "'\n";
    
    if ($news->image) {
        $expectedFile = 'public/uploads/news/' . $news->image;
        echo "Fichier attendu: " . $expectedFile . "\n";
        echo "Existe: " . (file_exists($expectedFile) ? '✅ OUI' : '❌ NON') . "\n";
    }
}

echo "\n=== Fichiers dans uploads/news ===\n";
$files = glob('public/uploads/news/*');
foreach ($files as $file) {
    echo "- " . basename($file) . "\n";
}
