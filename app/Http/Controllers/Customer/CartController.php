<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(Request $request): Response
    {
        $cartItems = Cart::with(['product.inventory'])
            ->where('customer_id', $request->user()->id)
            ->get();

        // Transform for frontend
        $transformed = $cartItems->map(fn ($item) => [
            'id'       => $item->id,
            'quantity' => $item->quantity,
            'product'  => [
                'id'        => $item->product->id,
                'name'      => $item->product->name,
                'price'     => $item->product->price,
                'photo_url' => collect($item->product->photo_urls)->first(),
                'stock'     => $item->product->live_stock,
            ],
        ]);

        $total = $cartItems->sum(fn ($item) => $item->quantity * $item->product->price);

        return Inertia::render('Customer/Cart', [
            'cartItems' => $transformed,
            'total'     => round($total, 2),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['sometimes', 'integer', 'min:1'],
        ]);

        $product = Product::with('inventory')->findOrFail($validated['product_id']);
        $requestedQty = $validated['quantity'] ?? 1;

        if (!$product->is_active || $product->live_stock < 1) {
            return redirect()->back()->with('error', 'This product is not available.');
        }

        $cartItem = Cart::where('customer_id', $request->user()->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        // Validate combined quantity does not exceed stock
        $existingQty = $cartItem ? $cartItem->quantity : 0;
        $newTotal = $existingQty + $requestedQty;

        if ($newTotal > $product->live_stock) {
            return redirect()->back()->with('error', "Only {$product->live_stock} unit(s) available. You already have {$existingQty} in your cart.");
        }

        if ($cartItem) {
            $cartItem->increment('quantity', $requestedQty);
        } else {
            Cart::create([
                'customer_id' => $request->user()->id,
                'product_id'  => $validated['product_id'],
                'quantity'    => $requestedQty,
            ]);
        }

        return redirect()->back()->with('success', 'Added to cart!');
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->customer_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        // Validate quantity against available stock
        $product = Product::with('inventory')->findOrFail($cart->product_id);
        if ($validated['quantity'] > $product->live_stock) {
            return redirect()->back()->with('error', "Only {$product->live_stock} unit(s) available.");
        }

        $cart->update($validated);

        return redirect()->back();
    }

    public function destroy(Request $request, Cart $cart)
    {
        if ($cart->customer_id !== $request->user()->id) {
            abort(403);
        }

        $cart->delete();

        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}
