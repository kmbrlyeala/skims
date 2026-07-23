<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminInventoryController extends Controller
{
    public function index(Request $request): Response
    {
        $query = InventoryItem::with('supplier:id,name')
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Inventory', [
            'items'   => $items,
            'filters' => $request->only(['search']),
        ]);
    }
}
