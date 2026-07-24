<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Supplier;
use App\Models\Product;

$s1 = Supplier::find(1);
if ($s1) {
    $s1->name = 'SKIMS Beauty Factory';
    $s1->contact_name = 'Sarah Johnson';
    $s1->contact_email = 'sarah.j@skims-beauty.example';
    $s1->source_platform = 'local_factory';
    $s1->notes = 'In-house manufacturing for all SKIMS beauty essentials.';
    $s1->save();
}

$s2 = Supplier::find(2);
if ($s2) {
    $s2->name = 'SKIMS Hygiene Labs';
    $s2->contact_name = 'Dr. Emma Stone';
    $s2->contact_email = 'emma.s@skims-hygiene.example';
    $s2->source_platform = 'local_factory';
    $s2->notes = 'In-house manufacturing for all SKIMS hygiene products.';
    $s2->save();
}

$p1 = Product::find(1);
if ($p1) {
    $p1->name = 'SKIMS Velvet Rose Moisturizer';
    $p1->sku = 'SKM-BTY-MOIST-01';
    $p1->description = 'A lightweight, fast-absorbing daily moisturizer infused with rose hip oil and hyaluronic acid. Leaves skin soft, dewy, and hydrated all day.';
    $p1->price = 42.00;
    $p1->save();
}

$p2 = Product::find(2);
if ($p2) {
    $p2->name = 'SKIMS Cloud Cleanser Foam';
    $p2->sku = 'SKM-BTY-CLN-01';
    $p2->description = 'Gentle foaming cleanser with chamomile extract. Removes makeup and impurities without stripping natural oils.';
    $p2->price = 28.00;
    $p2->save();
}

$p3 = Product::find(3);
if ($p3) {
    $p3->name = 'SKIMS Silk Serum Drops';
    $p3->sku = 'SKM-BTY-SRM-01';
    $p3->description = 'Concentrated vitamin C serum with niacinamide for brighter, more even-toned skin. Silky texture absorbs instantly.';
    $p3->price = 58.00;
    $p3->save();
}

echo "Database updated successfully.\n";
