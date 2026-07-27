<?php

namespace App\Http\Controllers\InventoryManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UiMockupController extends Controller
{
    public function purchaseRequests()
    {
        return Inertia::render('InventoryManager/PurchaseRequests/Index');
    }

    public function purchaseOrders()
    {
        return Inertia::render('InventoryManager/PurchaseOrders/Index');
    }

    public function stockMovement()
    {
        return Inertia::render('InventoryManager/StockMovement/Index');
    }

    public function lowStock()
    {
        return Inertia::render('InventoryManager/Inventory/LowStock');
    }

    public function reports()
    {
        return Inertia::render('InventoryManager/Reports/Index');
    }
}
