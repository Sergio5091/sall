<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sql = file_get_contents('add_missing_tables.sql');

// Remove comments and split by semicolon
$statements = array_filter(array_map('trim', explode(';', $sql)));
$statements = array_filter($statements, function($stmt) {
    return !empty($stmt) && !str_starts_with($stmt, '--');
});

foreach ($statements as $statement) {
    if (!empty($statement)) {
        try {
            DB::statement($statement);
        } catch (Exception $e) {
            echo "Error executing: " . substr($statement, 0, 50) . "...\n";
            echo "Error: " . $e->getMessage() . "\n";
            // Continue with next statement
        }
    }
}

echo "Missing tables created successfully!\n";