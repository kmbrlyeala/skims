<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Product::with('inventory')
            ->active()
            ->inStock();

        if ($search = $request->input('search')) {
            $escapedSearch = str_replace(['%', '_'], ['\%', '\_'], $search);
            $query->where(function ($q) use ($escapedSearch) {
                $q->where('name', 'like', "%{$escapedSearch}%")
                  ->orWhere('description', 'like', "%{$escapedSearch}%");
            });
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        // Transform products for the frontend
        $products->getCollection()->transform(fn ($p) => [
            'id'          => $p->id,
            'sku'         => $p->sku,
            'name'        => $p->name,
            'description' => $p->description,
            'price'       => $p->price,
            'stock'       => $p->live_stock,
            'photo_url'   => collect($p->photo_urls)->first(),
            'is_active'   => $p->is_active,
        ]);

        return Inertia::render('Customer/Shop', [
            'products' => $products,
            'filters'  => $request->only(['search']),
        ]);
    }
}
