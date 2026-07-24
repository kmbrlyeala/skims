<?php

namespace App\Http\Controllers\InventoryManager;

use App\Http\Controllers\Controller;
use App\Actions\Inventory\ProcessGoodsReceipt;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class GoodsReceiptController extends Controller
{
    public function create(Request $request): Response
    {
        // If a specific PO is pre-selected (e.g. from PO show page)
        $selectedPo = null;
        if ($request->po_id) {
            $po = PurchaseOrder::open()
                ->with(['purchaseRequest.product', 'purchaseRequest.supplier'])
                ->find($request->po_id);

            if ($po) {
                $selectedPo = $this->formatPoForReceipt($po);
            }
        }

        $openPos = PurchaseOrder::open()
            ->with(['purchaseRequest.product', 'purchaseRequest.supplier'])
            ->get()
            ->map(fn ($po) => $this->formatPoForReceipt($po));

        return Inertia::render('InventoryManager/GoodsReceipt/Create', [
            'openPos'    => $openPos,
            'selectedPo' => $selectedPo,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'quantity_received' => 'required|integer|min:1',
            'quantity_damaged'  => 'nullable|integer|min:0',
            'received_at'       => 'required|date|before_or_equal:today',
            'notes'             => 'nullable|string|max:2000',
        ]);

        $purchaseOrder = PurchaseOrder::open()
            ->with('purchaseRequest.product', 'purchaseRequest.supplier', 'goodsReceipts')
            ->findOrFail($validated['purchase_order_id']);

        // Validate received qty doesn't exceed remaining
        $remaining = $purchaseOrder->remaining_qty;
        if ($validated['quantity_received'] > $remaining) {
            return redirect()->back()->withErrors([
                'quantity_received' => "Cannot receive {$validated['quantity_received']} units. Only {$remaining} remaining on this PO.",
            ]);
        }

        app(ProcessGoodsReceipt::class)->handle($purchaseOrder, $validated);

        return redirect()->route('inventory-manager.supply-inventory.index')
            ->with('success', "Goods received successfully. Inventory has been updated.");
    }

    private function formatPoForReceipt(PurchaseOrder $po): array
    {
        $pr = $po->purchaseRequest;
        return [
            'id'               => $po->id,
            'po_number'        => $po->po_number,
            'product_name'     => $pr->product->name,
            'product_sku'      => $pr->product->sku,
            'supplier_name'    => $pr->supplier->name,
            'quantity_ordered' => $po->quantity_ordered,
            'total_received'   => $po->total_received_qty,
            'remaining_qty'    => $po->remaining_qty,
            'expected_arrival_date' => $po->expected_arrival_date->toDateString(),
            'status'           => $po->status,
            'status_label'     => $po->status_label,
        ];
    }
}
