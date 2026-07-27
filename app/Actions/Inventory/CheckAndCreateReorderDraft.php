<?php

namespace App\Actions\Inventory;

use App\Models\Inventory;
use App\Models\PurchaseRequest;
use App\Models\User;

class CheckAndCreateReorderDraft
{
    /**
     * Check if inventory is at or below reorder point, and if so, auto-generate a draft PR.
     *
     * @param Inventory $inventory
     * @return void
     */
    public function handle(Inventory $inventory): void
    {
        // 1. Only trigger if reorder point is configured and we dipped to/below it
        if ($inventory->reorder_point === 0) return;
        if ($inventory->on_hand_qty > $inventory->reorder_point) return;

        // 2. Prevent duplicates: check if an auto-draft already exists for this product
        $existingDraft = PurchaseRequest::where('product_id', $inventory->product_id)
            ->whereIn('status', ['draft', 'pending_approval']) // Also consider pending so we don't spam
            ->where('is_auto_draft', true)
            ->exists();

        if ($existingDraft) return;

        // 3. Get supplier details
        $product = $inventory->product;
        $supplierProduct = $product->supplierProducts()->first();
        
        if (!$supplierProduct) return;

        $suggestedQty = $supplierProduct->moq ?? max(1, $inventory->reorder_point * 2);
        
        // Find an admin user to set as requester, or fallback to 1
        $admin = User::where('role', 'admin')->first();

        // 4. Create the auto-draft
        PurchaseRequest::create([
            'product_id'            => $product->id,
            'supplier_id'           => $supplierProduct->supplier_id,
            'quantity_requested'    => $suggestedQty,
            'unit_cost'             => $supplierProduct->unit_cost ?? 0,
            'expected_delivery_date' => now()->addDays(14)->toDateString(),
            'status'                => 'pending_approval', // Jump straight to pending approval so it's a real notification to Admin
            'is_auto_draft'         => true,
            'notes'                 => 'Auto-generated reorder: stock hit reorder point of ' . $inventory->reorder_point . ' units.',
            'requested_by'          => $admin ? $admin->id : 1,
        ]);
    }
}
