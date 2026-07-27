<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SupplierOrderController extends Controller
{
    public function index(Request $request): Response
    {
        $supplierId = $request->user()->id;

        $orderItems = OrderItem::with(['order.customer:id,name,email', 'inventoryItem:id,name,sku'])
            ->where('supplier_id', $supplierId)
            ->latest()
            ->paginate(15);

        return Inertia::render('Supplier/Orders', [
            'orderItems' => $orderItems,
        ]);
    }

    public function updateStatus(Request $request, OrderItem $orderItem)
    {
        // Ensure the supplier owns this order item
        if ($orderItem->supplier_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:processing,shipped,delivered'],
        ]);

        $newStatus = $validated['status'];

        // Validate proper status transitions (prevent skipping steps)
        $currentOrderStatus = $orderItem->order->status;
        $allowedTransitions = [
            'pending'    => ['processing'],
            'processing' => ['shipped'],
            'shipped'    => ['delivered'],
        ];

        if (!isset($allowedTransitions[$currentOrderStatus]) || !in_array($newStatus, $allowedTransitions[$currentOrderStatus])) {
            return redirect()->back()->with('error', "Cannot change status from '{$currentOrderStatus}' to '{$newStatus}'.");
        }

        // Update the order status — since order_items don't have individual status columns,
        // update the parent order. In multi-supplier scenarios, use the lowest common status.
        $order = $orderItem->order;
        $order->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Order status updated.');
    }
}

