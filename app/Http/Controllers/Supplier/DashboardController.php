<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $supplierId = $request->user()->supplier_id;

        // Total orders received
        $totalOrders = PurchaseOrder::whereHas('purchaseRequest', function ($q) use ($supplierId) {
            $q->where('supplier_id', $supplierId);
        })->count();

        // Pending POs (Pending or Confirmed or Preparing)
        $pendingPos = PurchaseOrder::whereHas('purchaseRequest', function ($q) use ($supplierId) {
            $q->where('supplier_id', $supplierId);
        })->whereIn('status', ['ordered', 'confirmed', 'preparing'])->count();

        // Completed Deliveries (Shipped or Received)
        $completedDeliveries = PurchaseOrder::whereHas('purchaseRequest', function ($q) use ($supplierId) {
            $q->where('supplier_id', $supplierId);
        })->whereIn('status', ['shipped', 'partially_received', 'received'])->count();

        // Total Sales (Sum of total_cost of completed/shipped deliveries)
        $totalSales = PurchaseOrder::whereHas('purchaseRequest', function ($q) use ($supplierId) {
            $q->where('supplier_id', $supplierId);
        })->whereIn('status', ['shipped', 'partially_received', 'received'])->sum('total_cost');

        return Inertia::render('Supplier/Dashboard', [
            'metrics' => [
                'totalOrders' => $totalOrders,
                'pendingPos' => $pendingPos,
                'completedDeliveries' => $completedDeliveries,
                'totalSales' => $totalSales,
            ]
        ]);
    }
}
