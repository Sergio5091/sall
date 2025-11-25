<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Vérification de la table events ===\n";

try {
    // Vérifier si la table existe
    $tables = DB::select('SHOW TABLES');
    $eventsExists = false;
    
    echo "\nTables dans la base de données:\n";
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "- {$tableName}\n";
        if ($tableName === 'events') {
            $eventsExists = true;
        }
    }
    
    if ($eventsExists) {
        echo "\n✅ La table 'events' existe bien!\n";
        
        // Vérifier la structure de la table
        $columns = DB::select("DESCRIBE events");
        echo "\nStructure de la table events:\n";
        foreach ($columns as $column) {
            echo "- {$column->Field} ({$column->Type})\n";
        }
        
        // Vérifier s'il y a des events
        $count = DB::table('events')->count();
        echo "\nNombre d'events dans la table: {$count}\n";
        
    } else {
        echo "\n❌ La table 'events' n'existe pas!\n";
        echo "Solution: Exécutez 'php artisan migrate --force'\n";
    }
    
} catch (Exception $e) {
    echo "\n❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Fin de la vérification ===\n";
