<?php

namespace App\Actions\PurchaseRequest;

use App\Models\Inventory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApprovePurchaseRequest
{
    /**
     * Approve a pending PR (manager only).
     *
     * Steps:
     *  1. Validate MOQ rule (or pass if override flag is set)
     *  2. Change PR status to 'approved'
     *  3. Create linked PurchaseOrder (status = 'ordered')
     *  4. Add qty_ordered to inventory.incoming_qty
     *
     * @param  PurchaseRequest $pr
     * @param  User            $approver
     * @param  bool            $overrideMoq  Manager explicitly overrides MOQ check
     * @return PurchaseOrder
     * @throws ValidationException
     */
    public function handle(PurchaseRequest $pr, User $approver, bool $overrideMoq = false): PurchaseOrder
    {
        return DB::transaction(function () use ($pr, $approver, $overrideMoq) {
            // 1. MOQ validation
            if (! $overrideMoq) {
                $supplierProduct = SupplierProduct::where('supplier_id', $pr->supplier_id)
                    ->where('product_id', $pr->product_id)
                    ->first();

                if ($supplierProduct && $pr->quantity_requested < $supplierProduct->moq) {
                    throw ValidationException::withMessages([
                        'quantity_requested' => "Quantity {$pr->quantity_requested} is below supplier MOQ of {$supplierProduct->moq}. Enable MOQ override to proceed.",
                    ]);
                }
            }

            // 2. Approve the PR
            $pr->update([
                'status'      => 'approved',
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);

            // 3. Create the Purchase Order
            $po = PurchaseOrder::create([
                'purchase_request_id'  => $pr->id,
                'quantity_ordered'     => $pr->quantity_requested,
                'unit_cost'            => $pr->unit_cost,
                'total_cost'           => $pr->unit_cost * $pr->quantity_requested,
                'expected_arrival_date' => $pr->expected_delivery_date
                    ?? now()->addDays($pr->supplier->lead_time_days ?? 14)->toDateString(),
                'status'               => 'ordered',
            ]);

            // 4. Update incoming_qty in inventory
            $inventory = Inventory::firstOrCreate(
                ['product_id' => $pr->product_id],
                ['on_hand_qty' => 0, 'incoming_qty' => 0, 'reorder_point' => 0]
            );
            $inventory->addIncoming($pr->quantity_requested);

            return $po;
        });
    }
}
