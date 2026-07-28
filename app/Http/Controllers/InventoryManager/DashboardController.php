<?php

namespace App\Http\Controllers\InventoryManager;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalProducts = Product::active()->count();
        $lowStockProducts = Product::active()->lowStock()->count();
        $outOfStockProducts = Product::active()->whereHas('inventory', function ($q) {
            $q->where('on_hand_qty', 0);
        })->count();
        $pendingDeliveries = PurchaseOrder::open()->count();

        $stockValue = Product::active()->with('inventory')->get()->sum(function ($p) {
            return $p->live_stock * $p->price;
        });

        $recentMovements = InventoryTransaction::with(['product', 'user', 'batch'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $lowStockList = Product::active()
            ->lowStock()
            ->with(['category', 'inventory'])
            ->take(10)
            ->get();
            
        $notifications = $request->user()->unreadNotifications()->take(5)->get();

        return Inertia::render('InventoryManager/Dashboard', [
            'stats' => [
                'totalProducts' => $totalProducts,
                'lowStockProducts' => $lowStockProducts,
                'outOfStockProducts' => $outOfStockProducts,
                'pendingDeliveries' => $pendingDeliveries,
                'stockValue' => $stockValue,
            ],
            'recentMovements' => $recentMovements,
            'lowStockList' => $lowStockList,
            'notifications' => $notifications,
        ]);
    }
}
