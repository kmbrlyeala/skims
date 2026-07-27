<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;

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
        if ($request->has('product_id')) {
            $validated = $request->validate([
                'product_id' => ['required', 'exists:products,id'],
                'quantity'   => ['required', 'integer', 'min:1'],
            ]);

            $product = \App\Models\Product::with('inventory')->findOrFail($validated['product_id']);

            if (!$product->is_active || $product->live_stock < $validated['quantity']) {
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
                    'product_id'        => $product->id,
                    'quantity'          => $validated['quantity'],
                    'price'             => $product->price,
                ]);

                // Decrement stock
                if ($product->inventory) {
                    $product->inventory->decrement('on_hand_qty', $validated['quantity']);
                    
                    // Trigger Auto-Reorder Check
                    app(\App\Actions\Inventory\CheckAndCreateReorderDraft::class)
                        ->handle($product->inventory->fresh());
                }

                return $newOrder;
            });

            return redirect()->route('customer.orders.show', $order->id)->with('success', 'Order placed successfully!');
        }

        // Cart Checkout
        $cartItems = Cart::with('product.inventory')
            ->where('customer_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Verify stock availability
        foreach ($cartItems as $cartItem) {
            $stock = $cartItem->product->inventory ? $cartItem->product->inventory->on_hand_qty : 0;
            if ($stock < $cartItem->quantity) {
                return redirect()->back()->with(
                    'error',
                    "Not enough stock for {$cartItem->product->name}."
                );
            }
        }

        $total = $cartItems->sum(fn ($item) => $item->quantity * $item->product->price);

        $order = DB::transaction(function () use ($user, $cartItems, $total) {
            $newOrder = Order::create([
                'customer_id' => $user->id,
                'status'      => Order::STATUS_PENDING,
                'total'       => $total,
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id'          => $newOrder->id,
                    'product_id'        => $cartItem->product_id,
                    'quantity'          => $cartItem->quantity,
                    'price'             => $cartItem->product->price,
                ]);

                // Sync with Supply Chain Inventory and Trigger Auto-Reorder
                $b2bProduct = $cartItem->product;
                if ($b2bProduct && $b2bProduct->inventory) {
                    $b2bProduct->inventory->decrement('on_hand_qty', $cartItem->quantity);
                    
                    // Trigger Auto-Reorder Check
                    app(\App\Actions\Inventory\CheckAndCreateReorderDraft::class)
                        ->handle($b2bProduct->inventory->fresh());
                }
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

        $order->load(['items.product:id,name,photos,sku']);

        return Inertia::render('Customer/OrderDetail', [
            'order' => $order,
        ]);
    }
}
