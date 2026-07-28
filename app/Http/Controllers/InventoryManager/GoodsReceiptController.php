<?php

namespace App\Http\Controllers\InventoryManager;

use App\Http\Controllers\Controller;
use App\Actions\Inventory\ProcessGoodsReceipt;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceipt;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class GoodsReceiptController extends Controller
{
    public function create(Request $request): Response
    {
        $openPos = PurchaseOrder::whereIn('status', ['ordered', 'partially_received', 'overdue'])
            ->with(['purchaseRequest.product', 'purchaseRequest.supplier'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, fn ($q, $s) => $q->where('po_number', 'like', "%{$s}%")
                ->orWhereHas('purchaseRequest.product', fn ($p) => $p->where('name', 'like', "%{$s}%")))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $openPos->getCollection()->transform(fn ($po) => $this->formatPoForReceipt($po));

        $today = Carbon::today();
        
        $stats = [
            'pending'       => PurchaseOrder::whereIn('status', ['ordered', 'partially_received'])->count(),
            'received_today'=> GoodsReceipt::whereDate('received_at', $today)->count(),
            'items_received'=> GoodsReceipt::whereDate('received_at', $today)->sum('quantity_received'),
            'total_value'   => 0, // Mocked or calculated through relation
        ];

        return Inertia::render('InventoryManager/GoodsReceipt/Create', [
            'openPos'    => $openPos,
            'stats'      => $stats,
            'filters'    => $request->only(['status', 'search']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'quantity_received' => 'required|integer|min:1',
            'quantity_damaged'  => 'nullable|integer|min:0',
            'batch_number'      => 'nullable|string',
            'expiration_date'   => 'nullable|date',
            'received_at'       => 'required|date|before_or_equal:today',
            'notes'             => 'nullable|string|max:2000',
        ]);

        $purchaseOrder = PurchaseOrder::whereIn('status', ['ordered', 'partially_received', 'overdue'])
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

        return redirect()->route('inventory-manager.goods-receipts.create')
            ->with('success', "Goods received successfully. Inventory has been updated.");
    }

    private function formatPoForReceipt(PurchaseOrder $po): array
    {
        $pr = $po->purchaseRequest;
        return [
            'id'               => $po->id,
            'po_number'        => $po->po_number,
            'po_date'          => $po->created_at->toDateString(),
            'product_name'     => $pr->product->name,
            'product_sku'      => $pr->product->sku,
            'supplier_name'    => $pr->supplier->name,
            'supplier_initial' => substr($pr->supplier->name, 0, 1),
            'quantity_ordered' => $po->quantity_ordered,
            'total_received'   => $po->total_received_qty,
            'remaining_qty'    => $po->remaining_qty,
            'total_cost'       => $po->total_cost,
            'expected_arrival_date' => $po->expected_arrival_date->toDateString(),
            'status'           => $po->status,
            'status_label'     => $po->status_label,
            'status_color'     => $po->status_color,
        ];
    }
}
