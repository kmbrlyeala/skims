<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $supplierId = $request->user()->supplier_id;

        // Fetch POs that are preparing, shipped, or partially received/received
        $deliveries = PurchaseOrder::with(['purchaseRequest.product'])
            ->whereHas('purchaseRequest', function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId);
            })
            ->whereIn('status', ['preparing', 'shipped', 'partially_received', 'received'])
            ->latest()
            ->get()
            ->map(function ($po) {
                return [
                    'id' => $po->id,
                    'po' => $po->po_number,
                    'date' => $po->updated_at->format('Y-m-d'),
                    'tracking' => $po->tracking_number,
                    'status' => $po->status,
                ];
            });

        return Inertia::render('Supplier/Deliveries/Index', [
            'deliveries' => $deliveries
        ]);
    }

    public function ship(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'tracking_number' => 'required|string',
        ]);

        $purchaseOrder->status = 'shipped';
        $purchaseOrder->tracking_number = $request->tracking_number;
        
        // Auto-generate invoice data when shipped
        if (! $purchaseOrder->invoice_number) {
            $purchaseOrder->invoice_number = 'INV-' . strtoupper(Str::random(6));
            $purchaseOrder->invoice_amount = $purchaseOrder->total_cost;
            $purchaseOrder->invoice_due_date = now()->addDays(30);
            $purchaseOrder->payment_status = 'unpaid';
        }

        $purchaseOrder->save();

        return redirect()->back()->with('success', 'Delivery marked as shipped.');
    }
}
