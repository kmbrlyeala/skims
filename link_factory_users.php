<?php

require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Supplier;

// Beauty Factory
$user1 = User::where('email', 'beautyfactory@skims.local')->first();
$supplier1 = Supplier::where('name', 'SKIMS Beauty Factory')->first();

if ($user1 && $supplier1) {
    $user1->supplier_id = $supplier1->id;
    $user1->save();
    echo "Linked User 1 to Supplier ID: " . $supplier1->id . "\n";
}

// Hygiene Labs
$user2 = User::where('email', 'hygienelabs@skims.local')->first();
$supplier2 = Supplier::where('name', 'SKIMS Hygiene Labs')->first();

if ($user2 && $supplier2) {
    $user2->supplier_id = $supplier2->id;
    $user2->save();
    echo "Linked User 2 to Supplier ID: " . $supplier2->id . "\n";
}

echo "Done\n";
