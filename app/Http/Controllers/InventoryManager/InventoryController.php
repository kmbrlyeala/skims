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
        $inventory = Inventory::with('product')
            ->when($request->search, fn ($q, $s) => $q->whereHas('product', fn ($p) =>
                $p->where('name', 'like', "%{$s}%")->orWhere('sku', 'like', "%{$s}%")
            ))
            ->when($request->stock === 'low', fn ($q) =>
                $q->whereColumn('on_hand_qty', '<=', 'reorder_point')->where('reorder_point', '>', 0)
            )
            ->when($request->stock === 'out', fn ($q) => $q->where('on_hand_qty', 0))
            ->when($request->stock === 'ok', fn ($q) =>
                $q->whereColumn('on_hand_qty', '>', 'reorder_point')
            )
            ->latest('last_updated_at')
            ->paginate(25)
            ->withQueryString();

        $inventory->getCollection()->transform(fn ($inv) => [
            'id'             => $inv->id,
            'product_id'     => $inv->product_id,
            'product_name'   => $inv->product->name,
            'sku'            => $inv->product->sku,
            'on_hand_qty'    => $inv->on_hand_qty,
            'incoming_qty'   => $inv->incoming_qty,
            'reorder_point'  => $inv->reorder_point,
            'is_low_stock'   => $inv->is_low_stock,
            'is_out_of_stock' => $inv->is_out_of_stock,
            'last_updated_at' => $inv->last_updated_at?->diffForHumans(),
        ]);

        // Summary counts for dashboard strip
        $summary = [
            'total_skus'     => Inventory::count(),
            'low_stock'      => Inventory::whereColumn('on_hand_qty', '<=', 'reorder_point')->where('reorder_point', '>', 0)->count(),
            'out_of_stock'   => Inventory::where('on_hand_qty', 0)->count(),
            'incoming_total' => Inventory::sum('incoming_qty'),
        ];

        return Inertia::render('InventoryManager/Inventory/Index', [
            'inventory' => $inventory,
            'summary'   => $summary,
            'filters'   => $request->only(['search', 'stock']),
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
}
