<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UiMockupController extends Controller
{
    public function home()
    {
        return Inertia::render('Customer/Home');
    }

    public function dashboard()
    {
        return Inertia::render('Customer/Dashboard');
    }

    public function shop()
    {
        return Inertia::render('Customer/Shop/Index');
    }

    public function categories()
    {
        return Inertia::render('Customer/Categories/Index');
    }

    public function wishlist()
    {
        return Inertia::render('Customer/Wishlist/Index');
    }

    public function cart()
    {
        return Inertia::render('Customer/Cart/Index');
    }

    public function orders()
    {
        return Inertia::render('Customer/Orders/Index');
    }

    public function shippingAddress()
    {
        return Inertia::render('Customer/ShippingAddress/Index');
    }

    public function paymentMethods()
    {
        return Inertia::render('Customer/PaymentMethods/Index');
    }

    public function profile()
    {
        return Inertia::render('Customer/Profile/Index');
    }
}
