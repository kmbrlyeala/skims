<?php

namespace App\Http\Controllers\InventoryManager;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends Controller
{
    public function index(Request $request): Response
    {
        $inventoryQuery = Inventory::with(['product.category', 'product.inventoryBatches'])
            ->when($request->search, fn ($q, $s) => $q->whereHas('product', fn ($p) =>
                $p->where('name', 'like', "%{$s}%")->orWhere('sku', 'like', "%{$s}%")
            ))
            ->when($request->category_id, fn ($q, $c) => $c !== 'all' ? $q->whereHas('product', fn ($p) => $p->where('category_id', $c)) : $q)
            ->when($request->stock === 'low', fn ($q) =>
                $q->whereColumn('on_hand_qty', '<=', 'reorder_point')->where('reorder_point', '>', 0)
            )
            ->when($request->stock === 'out', fn ($q) => $q->where('on_hand_qty', 0))
            ->latest('last_updated_at');

        if ($request->has('export') && $request->export === 'csv') {
            $csvData = $inventoryQuery->get();
            $csv = "Product Name,SKU,Category,On Hand Qty,Reorder Point,Low Stock,Out of Stock\n";
            foreach ($csvData as $inv) {
                $category = $inv->product?->category?->name ?? 'Uncategorized';
                $productName = str_replace('"', '""', $inv->product?->name ?? '');
                $csv .= "\"{$productName}\",{$inv->product?->sku},\"{$category}\",{$inv->on_hand_qty},{$inv->reorder_point}," . ($inv->is_low_stock ? 'Yes' : 'No') . "," . ($inv->is_out_of_stock ? 'Yes' : 'No') . "\n";
            }
            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="inventory_export.csv"');
        }

        $inventory = $inventoryQuery->paginate(25)->withQueryString();

        $inventory->getCollection()->transform(function ($inv) {
            $product = $inv->product;
            $batch = $product->inventoryBatches->first(); // Just taking the first for display purposes
            $reserved = 0; // Mock or calculate reserved qty
            $available = $inv->on_hand_qty - $reserved;

            return [
                'id'             => $inv->id,
                'product_id'     => $inv->product_id,
                'product_name'   => $product->name,
                'photo_urls'     => $product->photo_urls ?? [],
                'sku'            => $product->sku,
                'category_name'  => $product->category?->name ?? 'Uncategorized',
                'on_hand_qty'    => $inv->on_hand_qty,
                'reserved_qty'   => $reserved,
                'available_qty'  => $available,
                'batch_number'   => $batch?->batch_number ?? '-',
                'expiration_date'=> $batch?->expiration_date ? \Carbon\Carbon::parse($batch->expiration_date)->format('M d, Y') : '-',
                'reorder_point'  => $inv->reorder_point,
                'is_low_stock'   => $inv->is_low_stock,
                'is_out_of_stock' => $inv->is_out_of_stock,
                'last_updated_at' => $inv->last_updated_at?->diffForHumans(),
            ];
        });

        return Inertia::render('InventoryManager/Inventory/Index', [
            'inventory'  => $inventory,
            'categories' => \App\Models\Category::all(),
            'filters'    => $request->only(['search', 'stock', 'category_id']),
        ]);
    }

    /**
     * Update only the reorder_point — no manual qty editing allowed.
     */
    public function update(Request $request, Inventory $inventory): RedirectResponse
    {
        $validated = $request->validate([
            'reorder_point' => 'required|integer|min:0',
        ]);

        $inventory->update(['reorder_point' => $validated['reorder_point']]);

        return redirect()->back()->with('success', 'Reorder point updated.');
    }

    public function transaction(Request $request, \App\Models\Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:add,deduct,adjust',
            'quantity' => 'required|integer|min:1',
            'batch_number' => 'nullable|string',
            'expiration_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $quantityChange = $validated['quantity'];
        if ($validated['type'] === 'deduct') {
            $quantityChange = -$validated['quantity'];
        }

        // Find or create batch
        $batch = \App\Models\InventoryBatch::firstOrCreate(
            [
                'product_id' => $product->id,
                'batch_number' => $validated['batch_number'] ?? null,
            ],
            [
                'expiration_date' => $validated['expiration_date'] ?? null,
                'quantity' => 0
            ]
        );

        if ($validated['type'] === 'adjust') {
            // Adjust sets the exact quantity of the batch
            $quantityChange = $validated['quantity'] - $batch->quantity;
        }

        $batch->increment('quantity', $quantityChange);

        \App\Models\InventoryTransaction::create([
            'product_id' => $product->id,
            'inventory_batch_id' => $batch->id,
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'quantity' => $validated['type'] === 'adjust' ? $validated['quantity'] : $quantityChange,
            'notes' => $validated['notes'],
        ]);

        // Update overall inventory on_hand_qty cache
        $inventory = $product->inventory()->firstOrCreate(['product_id' => $product->id]);
        $inventory->on_hand_qty = $product->inventoryBatches()->sum('quantity');
        $inventory->last_updated_at = now();
        $inventory->save();

        return redirect()->back()->with('success', 'Stock updated successfully.');
    }
}
