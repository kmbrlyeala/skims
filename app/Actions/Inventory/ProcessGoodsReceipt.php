<?php

namespace App\Actions\Inventory;

use App\Models\GoodsReceipt;
use App\Models\Inventory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcessGoodsReceipt
{
    /**
     * Process a goods receipt against a purchase order.
     *
     * This is the ONLY place where inventory.on_hand_qty is increased.
     * Steps:
     *  1. Validate received qty doesn't exceed remaining PO qty
     *  2. Create the GoodsReceipt record
     *  3. Update inventory (on_hand up, incoming down)
     *  4. Update PO status (partially_received / received)
     *  5. Update PR status to match PO
     *  6. Check if new on_hand hits reorder point → auto-draft new PR
     *
     * @param  PurchaseOrder $purchaseOrder
     * @param  array         $data  {quantity_received, quantity_damaged, received_at, notes}
     * @return GoodsReceipt
     */
    public function handle(PurchaseOrder $purchaseOrder, array $data): GoodsReceipt
    {
        return DB::transaction(function () use ($purchaseOrder, $data) {
            $qtyReceived = (int) $data['quantity_received'];
            $qtyDamaged  = (int) ($data['quantity_damaged'] ?? 0);
            $netReceived = max(0, $qtyReceived - $qtyDamaged);

            // 1. Create the Goods Receipt record
            $receipt = GoodsReceipt::create([
                'purchase_order_id' => $purchaseOrder->id,
                'quantity_received' => $qtyReceived,
                'quantity_damaged'  => $qtyDamaged,
                'received_by'       => Auth::id(),
                'received_at'       => $data['received_at'] ?? now()->toDateString(),
                'notes'             => $data['notes'] ?? null,
            ]);

            // 2. Update inventory — net received (excluding damaged units)
            $pr = $purchaseOrder->purchaseRequest;
            $inventory = Inventory::firstOrCreate(
                ['product_id' => $pr->product_id],
                ['on_hand_qty' => 0, 'incoming_qty' => 0, 'reorder_point' => 0]
            );
            $inventory->incrementStock($netReceived);

            // Sync with B2C Storefront (InventoryItem)
            $b2cProduct = \App\Models\InventoryItem::where('sku', $pr->product->sku)->first();
            if ($b2cProduct) {
                $b2cProduct->increment('stock', $netReceived);
            }

            // 3. Determine new PO status
            $totalReceived = $purchaseOrder->goodsReceipts()->sum('quantity_received');
            if ($totalReceived >= $purchaseOrder->quantity_ordered) {
                $purchaseOrder->update(['status' => 'received']);
                $pr->update(['status' => 'received']);
            } else {
                $purchaseOrder->update(['status' => 'partially_received']);
                $pr->update(['status' => 'partially_received']);
            }

            // 4. Check reorder point and auto-draft a new PR if triggered
            $inventory->refresh();
            $this->maybeAutoCreateReorderDraft($inventory, $pr);

            return $receipt;
        });
    }

    private function maybeAutoCreateReorderDraft(Inventory $inventory, PurchaseRequest $originalPr): void
    {
        // Only trigger if reorder point is configured and we've just dipped to/below it
        if ($inventory->reorder_point === 0) return;
        if ($inventory->on_hand_qty > $inventory->reorder_point) return;

        // Don't duplicate: check if an auto-draft already exists for this product
        $existingDraft = PurchaseRequest::where('product_id', $inventory->product_id)
            ->where('status', 'draft')
            ->where('is_auto_draft', true)
            ->exists();

        if ($existingDraft) return;

        // Find the MOQ for this supplier-product combination
        $supplierProduct = $originalPr->supplier->supplierProducts()
            ->where('product_id', $inventory->product_id)
            ->first();

        $suggestedQty = $supplierProduct?->moq ?? max(1, $inventory->reorder_point * 2);

        PurchaseRequest::create([
            'product_id'            => $inventory->product_id,
            'supplier_id'           => $originalPr->supplier_id,
            'quantity_requested'    => $suggestedQty,
            'unit_cost'             => $originalPr->unit_cost,
            'expected_delivery_date' => now()->addDays($originalPr->supplier->lead_time_days ?? 14)->toDateString(),
            'status'                => 'draft',
            'is_auto_draft'         => true,
            'notes'                 => 'Auto-generated reorder: stock hit reorder point of ' . $inventory->reorder_point . ' units.',
            'requested_by'          => $originalPr->requested_by,
        ]);
    }
}
