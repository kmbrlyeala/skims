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
    public function handle(PurchaseRequest $pr, User $approver, bool $overrideMoq = false): PurchaseRequest
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

            // 2. Approve the PR internally and send to factory
            $pr->update([
                'status'      => 'pending_factory_approval',
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);

            return $pr;
        });
    }
}
