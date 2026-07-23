<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminOrderController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Order::with(['customer:id,name,email', 'items'])
            ->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Orders', [
            'orders'  => $orders,
            'filters' => $request->only(['status']),
        ]);
    }
}
