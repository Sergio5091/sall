<?php

// Test simple pour vérifier si les fichiers existent
$testFile = 'salles/bannieres/UlvdiI7BZRq0VtH7fDnrucL5C8MCRk1Jyyub7Mz.jpg';
$fullPath = __DIR__ . '/storage/app/public/' . $testFile;

echo "Test de fichier image\n";
echo "Chemin: " . $fullPath . "\n";
echo "Existe: " . (file_exists($fullPath) ? 'OUI' : 'NON') . "\n";

if (file_exists($fullPath)) {
    echo "Taille: " . filesize($fullPath) . " octets\n";
    echo "Permissions: " . substr(sprintf('%o', fileperms($fullPath)), -4) . "\n";
}

// Test URL
echo "\nURL attendue: https://youpihub.com/storage/" . $testFile . "\n";
