<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\InventoryItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function admin(): Response
    {
        $stats = [
            'totalUsers'    => User::count(),
            'totalOrders'   => Order::count(),
            'totalRevenue'  => Order::where('status', '!=', 'cancelled')->sum('total'),
            'totalProducts' => \App\Models\Product::count(),
            'activeProducts'=> \App\Models\Product::where('is_active', true)->count(),
            'recentOrders'  => Order::with('customer:id,name')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
        ]);
    }

    public function inventoryManager(): Response
    {
        $stats = [
            'totalProducts' => \App\Models\Product::count(),
            'activeProducts'=> \App\Models\Product::where('is_active', true)->count(),
            'lowStockCount' => \App\Models\Inventory::whereColumn('on_hand_qty', '<=', 'reorder_point')->where('reorder_point', '>', 0)->count(),
        ];

        return Inertia::render('InventoryManager/Dashboard', [
            'stats' => $stats,
        ]);
    }

    public function supplier(Request $request): Response
    {
        $user = $request->user();

        $stats = [
            'totalProducts'  => $user->inventoryItems()->count(),
            'activeProducts' => $user->inventoryItems()->where('status', 'active')->count(),
            'totalStock'     => $user->inventoryItems()->sum('stock'),
            'totalOrders'    => \App\Models\PurchaseOrder::whereHas('purchaseRequest', fn($q) => $q->where('supplier_id', $user->supplier_id))->count(),
            'totalRevenue'   => \App\Models\PurchaseOrder::whereHas('purchaseRequest', fn($q) => $q->where('supplier_id', $user->supplier_id))
                                ->where('status', '!=', 'cancelled')
                                ->sum('total_cost') ?? 0,
            'recentOrders'   => \App\Models\PurchaseOrder::with(['purchaseRequest.product'])
                ->whereHas('purchaseRequest', fn($q) => $q->where('supplier_id', $user->supplier_id))
                ->latest()
                ->take(5)
                ->get(),
        ];

        return Inertia::render('Supplier/Dashboard', [
            'stats' => $stats,
        ]);
    }

    public function customer(Request $request): Response
    {
        $user = $request->user();

        $stats = [
            'totalOrders' => $user->orders()->count(),
            'cartCount'   => $user->cartItems()->sum('quantity'),
            'recentOrders'=> $user->orders()
                ->with('items.inventoryItem:id,name')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return Inertia::render('Customer/Dashboard', [
            'stats' => $stats,
        ]);
    }
}
