<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test URL helpers ===\n";

$path = 'salles/bannieres/UlvdiI7BZRq0VtH7fDnrucL5C8MCRk1Jyyub7Mz.jpg';

echo "url('storage/' . \$path): " . url('storage/' . $path) . "\n";
echo "asset('storage/' . \$path): " . asset('storage/' . $path) . "\n";
echo "config('app.url'): " . config('app.url') . "\n";

// Test si le lien symbolique existe
echo "\nLien symbolique public/storage: " . (is_link(public_path('storage')) ? 'OUI' : 'NON') . "\n";
