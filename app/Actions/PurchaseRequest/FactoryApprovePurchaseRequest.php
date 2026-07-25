<?php

namespace App\Actions\PurchaseRequest;

use App\Models\Inventory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\DB;

class FactoryApprovePurchaseRequest
{
    /**
     * Factory approves the PR and sets ETA.
     *
     * Steps:
     *  1. Change PR status to 'approved' and update expected_delivery_date.
     *  2. Create linked PurchaseOrder (status = 'ordered')
     *  3. Add qty_ordered to inventory.incoming_qty
     */
    public function handle(PurchaseRequest $pr, string $expectedDeliveryDate): PurchaseOrder
    {
        return DB::transaction(function () use ($pr, $expectedDeliveryDate) {
            // 1. Approve the PR
            $pr->update([
                'status'                 => 'approved',
                'expected_delivery_date' => $expectedDeliveryDate,
            ]);

            // 2. Create the Purchase Order
            $po = PurchaseOrder::create([
                'purchase_request_id'  => $pr->id,
                'quantity_ordered'     => $pr->quantity_requested,
                'unit_cost'            => $pr->unit_cost,
                'total_cost'           => $pr->unit_cost * $pr->quantity_requested,
                'expected_arrival_date' => $expectedDeliveryDate,
                'status'               => 'ordered',
            ]);

            // 3. Update incoming_qty in inventory
            $inventory = Inventory::firstOrCreate(
                ['product_id' => $pr->product_id],
                ['on_hand_qty' => 0, 'incoming_qty' => 0, 'reorder_point' => 0]
            );
            $inventory->addIncoming($pr->quantity_requested);

            return $po;
        });
    }
}
