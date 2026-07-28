<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // $notifications = $request->user()->notifications;
        // Mocking for now to avoid setting up entire Notification classes
        $notifications = [
            ['id' => 1, 'type' => 'PO_Received', 'title' => 'New Purchase Order Received', 'message' => 'You have a new PO.', 'date' => now()->subMinutes(10)->format('Y-m-d H:i'), 'read' => false]
        ];

        return Inertia::render('Supplier/Notifications/Index', [
            'notifications' => $notifications
        ]);
    }

    public function markAllRead(Request $request)
    {
        // $request->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Notifications marked as read.');
    }
}
