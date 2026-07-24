<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\InventoryItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $orders = Order::with('items.inventoryItem:id,name,image_url')
            ->where('customer_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Orders', [
            'orders' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Direct "Buy Now" purchase
        if ($request->has('inventory_item_id')) {
            $validated = $request->validate([
                'inventory_item_id' => ['required', 'exists:inventory_items,id'],
                'quantity'          => ['required', 'integer', 'min:1'],
            ]);

            $product = InventoryItem::findOrFail($validated['inventory_item_id']);

            if ($product->status !== 'active' || $product->stock < $validated['quantity']) {
                return redirect()->back()->with('error', 'Not enough stock available.');
            }

            $total = $validated['quantity'] * $product->price;

            $order = DB::transaction(function () use ($user, $product, $validated, $total) {
                $newOrder = Order::create([
                    'customer_id' => $user->id,
                    'status'      => Order::STATUS_PENDING,
                    'total'       => $total,
                ]);

                OrderItem::create([
                    'order_id'          => $newOrder->id,
                    'inventory_item_id' => $product->id,
                    'supplier_id'       => $product->supplier_id,
                    'quantity'          => $validated['quantity'],
                    'price'             => $product->price,
                ]);

                $product->decrement('stock', $validated['quantity']);

                return $newOrder;
            });

            return redirect()->route('customer.orders.show', $order->id)->with('success', 'Order placed successfully!');
        }

        // Cart Checkout
        $cartItems = Cart::with('inventoryItem')
            ->where('customer_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Verify stock availability
        foreach ($cartItems as $cartItem) {
            if ($cartItem->inventoryItem->stock < $cartItem->quantity) {
                return redirect()->back()->with(
                    'error',
                    "Not enough stock for {$cartItem->inventoryItem->name}."
                );
            }
        }

        $total = $cartItems->sum(fn ($item) => $item->quantity * $item->inventoryItem->price);

        $order = DB::transaction(function () use ($user, $cartItems, $total) {
            $newOrder = Order::create([
                'customer_id' => $user->id,
                'status'      => Order::STATUS_PENDING,
                'total'       => $total,
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id'          => $newOrder->id,
                    'inventory_item_id' => $cartItem->inventory_item_id,
                    'supplier_id'       => $cartItem->inventoryItem->supplier_id,
                    'quantity'          => $cartItem->quantity,
                    'price'             => $cartItem->inventoryItem->price,
                ]);

                // Decrement stock
                $cartItem->inventoryItem->decrement('stock', $cartItem->quantity);
            }

            // Clear the cart
            Cart::where('customer_id', $user->id)->delete();

            return $newOrder;
        });

        return redirect()->route('customer.orders.show', $order->id)->with('success', 'Order placed successfully!');
    }

    public function show(Request $request, Order $order): Response
    {
        if ($order->customer_id !== $request->user()->id) {
            abort(403);
        }

        $order->load(['items.inventoryItem:id,name,image_url,sku', 'items.supplier:id,name']);

        return Inertia::render('Customer/OrderDetail', [
            'order' => $order,
        ]);
    }
}
