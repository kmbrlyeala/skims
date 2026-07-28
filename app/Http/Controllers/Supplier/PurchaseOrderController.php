<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $supplierId = $request->user()->supplier_id;

        $orders = PurchaseOrder::with(['purchaseRequest.product'])
            ->whereHas('purchaseRequest', function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId);
            })
            ->latest()
            ->get()
            ->map(function ($po) {
                return [
                    'id' => $po->id,
                    'po_number' => $po->po_number,
                    'product' => $po->purchaseRequest->product->name,
                    'qty' => $po->quantity_ordered,
                    'value' => number_format($po->total_cost, 2),
                    'date' => $po->created_at->format('Y-m-d'),
                    'status' => $po->status, // pending, confirmed, preparing, shipped, rejected, etc.
                    'reject_reason' => $po->reject_reason,
                ];
            });

        return Inertia::render('Supplier/PurchaseOrders/Index', [
            'orders' => $orders
        ]);
    }

    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'status' => 'required|string|in:ordered,confirmed,preparing,shipped,rejected',
            'reject_reason' => 'required_if:status,rejected|nullable|string',
        ]);

        $purchaseOrder->status = $request->status;
        
        if ($request->status === 'rejected') {
            $purchaseOrder->reject_reason = $request->reject_reason;
        }

        $purchaseOrder->save();

        return redirect()->back()->with('success', 'Order status updated.');
    }
}
