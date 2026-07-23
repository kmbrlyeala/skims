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
            'totalProducts' => InventoryItem::count(),
            'activeProducts'=> InventoryItem::active()->count(),
            'recentOrders'  => Order::with('customer:id,name')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return Inertia::render('Admin/Dashboard', [
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
            'totalOrders'    => OrderItem::where('supplier_id', $user->id)->distinct('order_id')->count('order_id'),
            'totalRevenue'   => OrderItem::where('supplier_id', $user->id)
                ->whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))
                ->selectRaw('SUM(price * quantity) as total')
                ->value('total') ?? 0,
            'recentOrders'   => OrderItem::with(['order.customer:id,name', 'inventoryItem:id,name'])
                ->where('supplier_id', $user->id)
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
