<?php

namespace App\Http\Controllers\Admin\SupplyChain;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SupplierController extends Controller
{
    public function index(Request $request): Response
    {
        $suppliers = Supplier::withCount('purchaseRequests')
            ->with('products')
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->platform, fn ($q, $p) => $q->where('source_platform', $p))
            ->when($request->status === 'active', fn ($q) => $q->active())
            ->when($request->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/SupplyChain/Suppliers/Index', [
            'suppliers' => $suppliers,
            'filters'   => $request->only(['search', 'platform', 'status']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'contact_name'    => 'nullable|string|max:255',
            'contact_email'   => 'nullable|email|max:255',
            'contact_phone'   => 'nullable|string|max:50',
            'source_platform' => 'required|in:alibaba,local_factory,other',
            'lead_time_days'  => 'required|integer|min:1|max:365',
            'notes'           => 'nullable|string|max:2000',
            'is_active'       => 'boolean',
        ]);

        Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier): Response
    {
        $supplier->load([
            'supplierProducts.product.inventory',
            'purchaseRequests' => fn ($q) => $q->with('product')->latest()->take(10),
        ]);

        return Inertia::render('Admin/SupplyChain/Suppliers/Show', [
            'supplier' => [
                'id'              => $supplier->id,
                'name'            => $supplier->name,
                'contact_name'    => $supplier->contact_name,
                'contact_email'   => $supplier->contact_email,
                'contact_phone'   => $supplier->contact_phone,
                'source_platform' => $supplier->source_platform,
                'source_platform_label' => $supplier->source_platform_label,
                'lead_time_days'  => $supplier->lead_time_days,
                'notes'           => $supplier->notes,
                'is_active'       => $supplier->is_active,
                'supplierProducts' => $supplier->supplierProducts->map(fn ($sp) => [
                    'id'         => $sp->id,
                    'product'    => [
                        'id'   => $sp->product->id,
                        'name' => $sp->product->name,
                        'sku'  => $sp->product->sku,
                        'on_hand_qty' => $sp->product->inventory?->on_hand_qty ?? 0,
                    ],
                    'moq'        => $sp->moq,
                    'unit_cost'  => $sp->unit_cost,
                ]),
                'recent_prs' => $supplier->purchaseRequests->map(fn ($pr) => [
                    'id'       => $pr->id,
                    'product'  => $pr->product->name,
                    'quantity' => $pr->quantity_requested,
                    'status'   => $pr->status,
                    'status_label' => $pr->status_label,
                    'status_color' => $pr->status_color,
                    'created_at' => $pr->created_at->toDateString(),
                ]),
            ],
            'products' => Product::active()->get(['id', 'name', 'sku']),
        ]);
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'contact_name'    => 'nullable|string|max:255',
            'contact_email'   => 'nullable|email|max:255',
            'contact_phone'   => 'nullable|string|max:50',
            'source_platform' => 'required|in:alibaba,local_factory,other',
            'lead_time_days'  => 'required|integer|min:1|max:365',
            'notes'           => 'nullable|string|max:2000',
            'is_active'       => 'boolean',
        ]);

        $supplier->update($validated);

        return redirect()->back()->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->update(['is_active' => false]);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deactivated.');
    }

    public function linkProduct(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'moq'        => 'required|integer|min:1',
            'unit_cost'  => 'required|numeric|min:0',
        ]);

        $supplier->supplierProducts()->updateOrCreate(
            ['product_id' => $validated['product_id']],
            ['moq' => $validated['moq'], 'unit_cost' => $validated['unit_cost']]
        );

        return redirect()->back()->with('success', 'Product linked to supplier.');
    }

    public function unlinkProduct(Supplier $supplier, Product $product): RedirectResponse
    {
        $supplier->supplierProducts()->where('product_id', $product->id)->delete();

        return redirect()->back()->with('success', 'Product unlinked from supplier.');
    }
}
