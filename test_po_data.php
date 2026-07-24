<?php

require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PurchaseOrder;

$pos = PurchaseOrder::with(['purchaseRequest.product', 'purchaseRequest.supplier', 'goodsReceipts'])
    ->latest()
    ->paginate(20);

echo "Count: " . count($pos->items()) . "\n";
foreach ($pos->items() as $po) {
    try {
        $pr = $po->purchaseRequest;
        $data = [
            'id'                   => $po->id,
            'po_number'            => $po->po_number,
            'product_name'         => $pr->product->name,
            'product_sku'          => $pr->product->sku,
            'supplier_name'        => $pr->supplier->name,
            'quantity_ordered'     => $po->quantity_ordered,
            'total_received'       => $po->total_received_qty,
            'remaining_qty'        => $po->remaining_qty,
            'unit_cost'            => $po->unit_cost,
            'total_cost'           => $po->total_cost,
            'expected_arrival_date' => $po->expected_arrival_date ? $po->expected_arrival_date->toDateString() : null,
            'status'               => $po->status,
            'status_label'         => $po->status_label,
            'status_color'         => $po->status_color,
            'created_at'           => $po->created_at->toDateString(),
        ];
        echo "PO {$po->po_number} formatted successfully.\n";
    } catch (\Exception $e) {
        echo "Error on PO {$po->id}: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
}
