<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseOrderController extends Controller
{
    public function index(Request $request): Response
    {
        $supplierId = $request->user()->supplier_id;

        $orders = PurchaseOrder::with(['purchaseRequest.product'])
            ->whereHas('purchaseRequest', function ($query) use ($supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->latest()
            ->get();

        return Inertia::render('Supplier/PurchaseOrders/Index', [
            'orders' => $orders->map(function ($po) {
                return [
                    'id'               => $po->id,
                    'po_number'        => $po->po_number,
                    'product_name'     => $po->purchaseRequest->product->name,
                    'quantity_ordered' => $po->quantity_ordered,
                    'total_cost'       => $po->total_cost,
                    'expected_arrival' => $po->expected_arrival_date?->toDateString(),
                    'status'           => $po->status,
                    'status_label'     => $po->status_label,
                ];
            }),
        ]);
    }

    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $supplierId = $request->user()->supplier_id;

        // Ensure the PO belongs to this supplier
        if ($purchaseOrder->purchaseRequest->supplier_id !== $supplierId) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:ordered,partially_received,shipped,received,delivered',
        ]);

        $purchaseOrder->update(['status' => $validated['status']]);

        return back()->with('success', 'Purchase Order status updated.');
    }
}
