<?php

namespace App\Http\Controllers\InventoryManager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'inventory']);

        if ($request->has('search') && $request->search !== null) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'in_stock') {
                $query->whereHas('inventory', function($q) {
                    $q->where('on_hand_qty', '>', 0);
                });
            } elseif ($request->status === 'low_stock') {
                $query->whereHas('inventory', function($q) {
                    $q->whereColumn('on_hand_qty', '<=', 'reorder_point')
                      ->where('on_hand_qty', '>', 0)
                      ->where('reorder_point', '>', 0);
                });
            } elseif ($request->status === 'out_of_stock') {
                $query->whereHas('inventory', function($q) {
                    $q->where('on_hand_qty', '<=', 0);
                })->orWhereDoesntHave('inventory');
            }
        }

        if ($request->has('export') && $request->export === 'csv') {
            $csvData = $query->get();
            $csv = "ID,Name,SKU,Category,Price,On Hand Qty,Min Stock,Status\n";
            foreach ($csvData as $p) {
                $status = $p->is_active ? ($p->inventory?->on_hand_qty > 0 ? 'In Stock' : 'Out of Stock') : 'Discontinued';
                $csv .= "{$p->id},\"{$p->name}\",{$p->sku},\"" . ($p->category?->name ?? '') . "\",{$p->price},{$p->inventory?->on_hand_qty},{$p->inventory?->reorder_point},{$status}\n";
            }
            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="products_export.csv"');
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        return Inertia::render('InventoryManager/Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category_id', 'status']),
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'inventory', 'inventoryBatches']);
        
        return Inertia::render('InventoryManager/Products/Show', [
            'product' => $product,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:products,sku',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        Product::create($validated);

        return redirect()->back()->with('success', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $product->update($validated);

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Check for inventory or pending purchase orders before deletion?
        // Let's just soft delete if it has transactions, or force delete if empty.
        // Or simply delete. (Assuming relationships are handled by DB constraints or we just delete it for now).
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
