<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AdminOrderController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Order::with(['customer:id,name,email', 'items.product'])
            ->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Orders', [
            'orders'  => $orders,
            'filters' => $request->only(['status']),
        ]);
    }

    /**
     * Cancel an order and restore stock to inventory.
     */
    public function cancel(Request $request, Order $order)
    {
        if ($order->status === Order::STATUS_CANCELLED) {
            return redirect()->back()->with('error', 'This order is already cancelled.');
        }

        if ($order->status === Order::STATUS_DELIVERED) {
            return redirect()->back()->with('error', 'Cannot cancel a delivered order.');
        }

        DB::transaction(function () use ($order) {
            // Restore stock for each order item to the unified inventory
            foreach ($order->items as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->increment('on_hand_qty', $item->quantity);
                }
            }

            $order->update(['status' => Order::STATUS_CANCELLED]);
        });

        return redirect()->back()->with('success', 'Order cancelled and stock restored.');
    }
}
