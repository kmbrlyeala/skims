<?php

namespace App\Http\Controllers\Admin\SupplyChain;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseOrderController extends Controller
{
    public function index(Request $request): Response
    {
        $pos = PurchaseOrder::with(['purchaseRequest.product.inventory', 'purchaseRequest.supplier', 'goodsReceipts'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, fn ($q, $s) => $q->where('po_number', 'like', "%{$s}%")
                ->orWhereHas('purchaseRequest.product', fn ($p) => $p->where('name', 'like', "%{$s}%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $pos->getCollection()->transform(fn ($po) => $this->formatPo($po));

        return Inertia::render('Admin/SupplyChain/PurchaseOrders/Index', [
            'purchaseOrders' => $pos,
            'filters'        => $request->only(['status', 'search']),
            'routePrefix'    => $request->user()->hasRole('admin') ? 'admin' : 'inventory-manager',
        ]);
    }

    public function show(PurchaseOrder $purchaseOrder): Response
    {
        $purchaseOrder->load([
            'purchaseRequest.product.inventory',
            'purchaseRequest.supplier',
            'purchaseRequest.requester',
            'purchaseRequest.approver',
            'goodsReceipts.receiver',
        ]);

        $pr      = $purchaseOrder->purchaseRequest;
        $product = $pr->product;

        return Inertia::render('Admin/SupplyChain/PurchaseOrders/Show', [
            'purchaseOrder' => [
                ...$this->formatPo($purchaseOrder),
                'purchase_request' => [
                    'id'           => $pr->id,
                    'requester'    => $pr->requester->name,
                    'approver'     => $pr->approver?->name,
                    'approved_at'  => $pr->approved_at?->toDateString(),
                    'notes'        => $pr->notes,
                ],
                'product' => [
                    'id'          => $product->id,
                    'name'        => $product->name,
                    'sku'         => $product->sku,
                    'on_hand_qty' => $product->inventory?->on_hand_qty ?? 0,
                    'incoming_qty' => $product->inventory?->incoming_qty ?? 0,
                ],
                'goods_receipts' => $purchaseOrder->goodsReceipts->map(fn ($gr) => [
                    'id'                => $gr->id,
                    'quantity_received' => $gr->quantity_received,
                    'quantity_damaged'  => $gr->quantity_damaged,
                    'net_received'      => $gr->net_received,
                    'receiver'          => $gr->receiver->name,
                    'received_at'       => $gr->received_at->toDateString(),
                    'notes'             => $gr->notes,
                ]),
            ],
            'routePrefix' => $request->user()->hasRole('admin') ? 'admin' : 'inventory-manager',
        ]);
    }

    private function formatPo(PurchaseOrder $po): array
    {
        $pr = $po->purchaseRequest;
        return [
            'id'                   => $po->id,
            'po_number'            => $po->po_number,
            'product_name'         => $pr->product->name,
            'product_sku'          => $pr->product->sku,
            'product_on_hand'      => $pr->product->inventory?->on_hand_qty ?? 0,
            'product_incoming'     => $pr->product->inventory?->incoming_qty ?? 0,
            'supplier_name'        => $pr->supplier->name,
            'quantity_ordered'     => $po->quantity_ordered,
            'total_received'       => $po->total_received_qty,
            'remaining_qty'        => $po->remaining_qty,
            'unit_cost'            => $po->unit_cost,
            'total_cost'           => $po->total_cost,
            'expected_arrival_date' => $po->expected_arrival_date->toDateString(),
            'status'               => $po->status,
            'status_label'         => $po->status_label,
            'status_color'         => $po->status_color,
            'created_at'           => $po->created_at->toDateString(),
        ];
    }
}
