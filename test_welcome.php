<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\WelcomeController;

echo "=== Test WelcomeController ===\n";

try {
    $controller = new WelcomeController();
    $response = $controller->index();
    echo "✅ WelcomeController fonctionne correctement\n";
    echo "Response type: " . get_class($response) . "\n";
} catch (Exception $e) {
    echo "❌ Erreur dans WelcomeController: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
