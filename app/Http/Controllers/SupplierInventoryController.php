<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierInventoryController extends Controller
{
    public function index(Request $request)
    {
        $items = InventoryItem::where('supplier_id', $request->user()->id)
            ->latest()
            ->get();

        return Inertia::render('Supplier/Inventory/Index', [
            'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image_url'   => ['nullable', 'url', 'max:2000'],
            'sku'         => ['required', 'string', 'max:255', 'unique:inventory_items'],
            'stock'       => ['required', 'integer', 'min:0'],
            'price'       => ['required', 'numeric', 'min:0'],
            'status'      => ['required', 'string', 'in:draft,active,hidden'],
        ]);

        InventoryItem::create([
            'supplier_id' => $request->user()->id,
            ...$validated,
        ]);

        return redirect()->route('supplier.inventory')->with('success', 'Item created.');
    }

    public function update(Request $request, InventoryItem $item)
    {
        if ($item->supplier_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image_url'   => ['nullable', 'url', 'max:2000'],
            'sku'         => ['required', 'string', 'max:255', 'unique:inventory_items,sku,' . $item->id],
            'stock'       => ['required', 'integer', 'min:0'],
            'price'       => ['required', 'numeric', 'min:0'],
            'status'      => ['required', 'string', 'in:draft,active,hidden'],
        ]);

        $item->update($validated);

        return redirect()->route('supplier.inventory')->with('success', 'Item updated.');
    }

    public function destroy(Request $request, InventoryItem $item)
    {
        if ($item->supplier_id !== $request->user()->id) {
            abort(403);
        }

        $item->delete();

        return redirect()->route('supplier.inventory')->with('success', 'Item deleted.');
    }
}
