<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UiMockupController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Admin/Dashboard');
    }

    public function products()
    {
        return Inertia::render('Admin/Products/Index');
    }

    public function categories()
    {
        return Inertia::render('Admin/Categories/Index');
    }

    public function customers()
    {
        return Inertia::render('Admin/Customers/Index');
    }

    public function orders()
    {
        return Inertia::render('Admin/Orders/Index');
    }

    public function shipping()
    {
        return Inertia::render('Admin/Shipping/Index');
    }

    public function suppliers()
    {
        return Inertia::render('Admin/Suppliers/Index');
    }

    public function purchaseRequests()
    {
        return Inertia::render('Admin/PurchaseRequests/Index');
    }

    public function purchaseOrders()
    {
        return Inertia::render('Admin/PurchaseOrders/Index');
    }

    public function inventory()
    {
        return Inertia::render('Admin/Inventory/Index');
    }

    public function reports()
    {
        return Inertia::render('Admin/Reports/Index');
    }

    public function users()
    {
        return Inertia::render('Admin/Users/Index');
    }

    public function settings()
    {
        return Inertia::render('Admin/Settings/Index');
    }
}
