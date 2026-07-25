<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $orders = Order::with('items.product')
            ->where('customer_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        // Transform for frontend
        $orders->getCollection()->transform(fn ($order) => [
            'id'         => $order->id,
            'status'     => $order->status,
            'total'      => $order->total,
            'created_at' => $order->created_at->toISOString(),
            'items'      => $order->items->map(fn ($item) => [
                'id'       => $item->id,
                'quantity' => $item->quantity,
                'price'    => $item->price,
                'product'  => [
                    'id'        => $item->product->id,
                    'name'      => $item->product->name,
                    'photo_url' => collect($item->product->photo_urls)->first(),
                ],
            ]),
        ]);

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

            try {
                $order = DB::transaction(function () use ($user, $validated) {
                    // Lock the inventory row to prevent race conditions
                    $product = Product::findOrFail($validated['product_id']);
                    $inventory = Inventory::where('product_id', $product->id)->lockForUpdate()->first();

                    if (!$product->is_active) {
                        throw new \Exception('This product is no longer available.');
                    }

                    $stock = $inventory ? $inventory->on_hand_qty : 0;
                    if ($stock < $validated['quantity']) {
                        throw new \Exception("Not enough stock. Only {$stock} unit(s) available.");
                    }

                    $total = $validated['quantity'] * $product->price;

                    $newOrder = Order::create([
                        'customer_id' => $user->id,
                        'status'      => Order::STATUS_PENDING,
                        'total'       => $total,
                    ]);

                    OrderItem::create([
                        'order_id'   => $newOrder->id,
                        'product_id' => $product->id,
                        'quantity'   => $validated['quantity'],
                        'price'      => $product->price,
                    ]);

                    // Decrement on_hand_qty in the unified inventory
                    $inventory->decrement('on_hand_qty', $validated['quantity']);

                    return $newOrder;
                });

                return redirect()->route('customer.orders.show', $order->id)->with('success', 'Order placed successfully!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        // Cart Checkout
        try {
            $order = DB::transaction(function () use ($user) {
                $cartItems = Cart::with('product.inventory')
                    ->where('customer_id', $user->id)
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new \Exception('Your cart is empty.');
                }

                // Verify stock availability and active status with row locks
                $total = 0;
                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;
                    $inventory = Inventory::where('product_id', $product->id)->lockForUpdate()->first();

                    if (!$product->is_active) {
                        throw new \Exception("{$product->name} is no longer available. Please remove it from your cart.");
                    }

                    $stock = $inventory ? $inventory->on_hand_qty : 0;
                    if ($stock < $cartItem->quantity) {
                        throw new \Exception("Not enough stock for {$product->name}. Only {$stock} unit(s) available.");
                    }

                    $total += $cartItem->quantity * $product->price;
                }

                $newOrder = Order::create([
                    'customer_id' => $user->id,
                    'status'      => Order::STATUS_PENDING,
                    'total'       => round($total, 2),
                ]);

                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;

                    OrderItem::create([
                        'order_id'   => $newOrder->id,
                        'product_id' => $product->id,
                        'quantity'   => $cartItem->quantity,
                        'price'      => $product->price,
                    ]);

                    // Decrement on_hand_qty in the unified inventory
                    $inventory = Inventory::where('product_id', $product->id)->first();
                    $inventory->decrement('on_hand_qty', $cartItem->quantity);
                }

                // Clear the cart
                Cart::where('customer_id', $user->id)->delete();

                return $newOrder;
            });

            return redirect()->route('customer.orders.show', $order->id)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, Order $order): Response
    {
        if ($order->customer_id !== $request->user()->id) {
            abort(403);
        }

        $order->load('items.product');

        return Inertia::render('Customer/OrderDetail', [
            'order' => [
                'id'         => $order->id,
                'status'     => $order->status,
                'total'      => $order->total,
                'created_at' => $order->created_at->toISOString(),
                'items'      => $order->items->map(fn ($item) => [
                    'id'       => $item->id,
                    'quantity' => $item->quantity,
                    'price'    => $item->price,
                    'product'  => [
                        'id'        => $item->product->id,
                        'name'      => $item->product->name,
                        'sku'       => $item->product->sku,
                        'photo_url' => collect($item->product->photo_urls)->first(),
                    ],
                ]),
            ],
        ]);
    }
}
