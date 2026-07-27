<?php

namespace App\Http\Controllers\Admin\SupplyChain;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $products = Product::with(['inventory', 'suppliers'])
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('sku', 'like', "%{$s}%"))
            ->when($request->status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($request->stock === 'low', fn ($q) => $q->lowStock())
            ->when($request->stock === 'out', fn ($q) => $q->whereHas('inventory', fn ($i) => $i->where('on_hand_qty', 0)))
            ->when($request->stock === 'in', fn ($q) => $q->inStock())
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $products->getCollection()->transform(fn ($p) => [
            'id'            => $p->id,
            'sku'           => $p->sku,
            'name'          => $p->name,
            'price'         => $p->price,
            'is_active'     => $p->is_active,
            'live_stock'    => $p->live_stock,
            'incoming_qty'  => $p->inventory?->incoming_qty ?? 0,
            'reorder_point' => $p->inventory?->reorder_point ?? 0,
            'stock_status'  => $p->stock_status,
            'photo_url'     => collect($p->photo_urls)->first(),
            'supplier_name' => $p->suppliers->pluck('name')->join(', '),
        ]);

        return Inertia::render('Admin/SupplyChain/Products/Index', [
            'products' => $products,
            'filters'  => $request->only(['search', 'status', 'stock']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/SupplyChain/Products/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sku'           => 'required|string|max:100|unique:products,sku',
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:5000',
            'price'         => 'required|numeric|min:0',
            'is_active'     => 'boolean',
            'reorder_point' => 'required|integer|min:0',
            'photos.*'      => 'nullable|image|max:5120', // 5MB each
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $photoPaths[] = $file->store('products', 'public');
            }
        }

        $product = Product::create([
            'sku'         => $validated['sku'],
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'is_active'   => $validated['is_active'] ?? true,
            'photos'      => $photoPaths,
        ]);

        // Create inventory record with reorder_point
        Inventory::create([
            'product_id'    => $product->id,
            'on_hand_qty'   => 0,
            'incoming_qty'  => 0,
            'reorder_point' => $validated['reorder_point'],
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): Response
    {
        $product->load('inventory');

        return Inertia::render('Admin/SupplyChain/Products/Edit', [
            'product' => [
                'id'            => $product->id,
                'sku'           => $product->sku,
                'name'          => $product->name,
                'description'   => $product->description,
                'price'         => $product->price,
                'is_active'     => $product->is_active,
                'photo_urls'    => $product->photo_urls,
                'photos'        => $product->photos ?? [],
                'reorder_point' => $product->inventory?->reorder_point ?? 0,
                'on_hand_qty'   => $product->inventory?->on_hand_qty ?? 0,
                'incoming_qty'  => $product->inventory?->incoming_qty ?? 0,
            ],
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:5000',
            'price'         => 'required|numeric|min:0',
            'is_active'     => 'boolean',
            'reorder_point' => 'required|integer|min:0',
            'photos.*'      => 'nullable|image|max:5120',
            'remove_photos' => 'nullable|array',
        ]);

        // Handle photo removal
        $existingPhotos = $product->photos ?? [];
        if (! empty($validated['remove_photos'])) {
            foreach ($validated['remove_photos'] as $path) {
                Storage::disk('public')->delete($path);
                $existingPhotos = array_filter($existingPhotos, fn ($p) => $p !== $path);
            }
        }

        // Handle new photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $existingPhotos[] = $file->store('products', 'public');
            }
        }

        $product->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'is_active'   => $validated['is_active'] ?? $product->is_active,
            'photos'      => array_values($existingPhotos),
        ]);

        // Update reorder point on inventory
        $product->inventory()->updateOrCreate(
            ['product_id' => $product->id],
            ['reorder_point' => $validated['reorder_point']]
        );

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->update(['is_active' => false]);

        return redirect()->route('admin.products.index')->with('success', 'Product deactivated. It will reactivate when restocked.');
    }
}
