<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SupplierProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $supplierId = $request->user()->supplier_id;

        $products = SupplierProduct::with('product.category')
            ->where('supplier_id', $supplierId)
            ->get()
            ->map(function ($sp) {
                return [
                    'id' => $sp->id,
                    'product_id' => $sp->product_id,
                    'name' => $sp->product->name,
                    'sku' => $sp->product->sku,
                    'category' => $sp->product->category->name ?? 'Uncategorized',
                    'unit_cost' => $sp->unit_cost,
                    'moq' => $sp->moq,
                ];
            });

        return Inertia::render('Supplier/Inventory/Index', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'nullable|exists:categories,id',
            'unit_cost' => 'required|numeric|min:0',
            'moq' => 'required|integer|min:1',
        ]);

        $supplierId = $request->user()->supplier_id;

        // Create base product
        $product = Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'category_id' => $request->category_id,
            'price' => $request->unit_cost * 1.5, // Dummy default price
        ]);

        // Link to supplier
        SupplierProduct::create([
            'supplier_id' => $supplierId,
            'product_id' => $product->id,
            'unit_cost' => $request->unit_cost,
            'moq' => $request->moq,
        ]);

        return redirect()->back()->with('success', 'Product added successfully.');
    }

    public function update(Request $request, SupplierProduct $supplierProduct)
    {
        $request->validate([
            'unit_cost' => 'required|numeric|min:0',
            'moq' => 'required|integer|min:1',
        ]);

        // Basic authorization
        if ($supplierProduct->supplier_id !== $request->user()->supplier_id) {
            abort(403);
        }

        $supplierProduct->update([
            'unit_cost' => $request->unit_cost,
            'moq' => $request->moq,
        ]);

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request, SupplierProduct $supplierProduct)
    {
        if ($supplierProduct->supplier_id !== $request->user()->supplier_id) {
            abort(403);
        }

        // We only remove the supplier association, not the base product, or we can remove the base product if they own it exclusively.
        // For simplicity here, we'll just delete the SupplierProduct record.
        $supplierProduct->delete();

        return redirect()->back()->with('success', 'Product removed from catalog.');
    }
}
