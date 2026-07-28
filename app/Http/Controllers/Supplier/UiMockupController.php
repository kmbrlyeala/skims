<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UiMockupController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Supplier/Dashboard');
    }

    public function purchaseRequests()
    {
        return Inertia::render('Supplier/PurchaseRequests/Index');
    }

    public function purchaseOrders()
    {
        return Inertia::render('Supplier/PurchaseOrders/Index');
    }

    public function deliveries()
    {
        return Inertia::render('Supplier/Deliveries/Index');
    }

    public function productsSupplied()
    {
        return Inertia::render('Supplier/ProductsSupplied/Index');
    }

    public function deliveryHistory()
    {
        return Inertia::render('Supplier/DeliveryHistory/Index');
    }

    public function inventory()
    {
        return Inertia::render('Supplier/Inventory/Index');
    }

    public function invoices()
    {
        return Inertia::render('Supplier/Invoices/Index');
    }

    public function notifications()
    {
        return Inertia::render('Supplier/Notifications/Index');
    }

    public function reports()
    {
        return Inertia::render('Supplier/Reports/Index');
    }
}
