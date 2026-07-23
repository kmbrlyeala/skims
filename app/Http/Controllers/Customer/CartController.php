<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(Request $request): Response
    {
        $cartItems = Cart::with('inventoryItem.supplier:id,name')
            ->where('customer_id', $request->user()->id)
            ->get();

        $total = $cartItems->sum(fn ($item) => $item->quantity * $item->inventoryItem->price);

        return Inertia::render('Customer/Cart', [
            'cartItems' => $cartItems,
            'total'     => round($total, 2),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventory_item_id' => ['required', 'exists:inventory_items,id'],
            'quantity'          => ['sometimes', 'integer', 'min:1'],
        ]);

        $product = InventoryItem::findOrFail($validated['inventory_item_id']);

        if ($product->status !== 'active' || $product->stock < 1) {
            return redirect()->back()->with('error', 'This product is not available.');
        }

        $cartItem = Cart::where('customer_id', $request->user()->id)
            ->where('inventory_item_id', $validated['inventory_item_id'])
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $validated['quantity'] ?? 1);
        } else {
            Cart::create([
                'customer_id'       => $request->user()->id,
                'inventory_item_id' => $validated['inventory_item_id'],
                'quantity'          => $validated['quantity'] ?? 1,
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
