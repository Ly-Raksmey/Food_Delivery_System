<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->paginate(20);
        $unreadCount = $user->notifications()->whereNull('read_at')->count();
        return view('customer.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function show(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        $relatedOrder = null;
        $relatedDelivery = null;
        $relatedFood = null;

        if ($notification->type === 'order' || $notification->type === 'delivery' || $notification->type === 'payment') {
            preg_match('/#([A-Z0-9\-]+)/', $notification->message, $matches);
            if (!empty($matches[1])) {
                $relatedOrder = \App\Models\Order::where('order_number', $matches[1])->first();
                if ($relatedOrder) {
                    $relatedDelivery = $relatedOrder->delivery()->with('deliveryStaff')->first();
                }
            }
        } elseif ($notification->type === 'review') {
            preg_match('/for (.+?)\./', $notification->message, $matches);
            if (!empty($matches[1])) {
                $relatedFood = \App\Models\Food::where('name', $matches[1])->first();
            }
        }

        return view('customer.notifications.show', compact('notification', 'relatedOrder', 'relatedDelivery', 'relatedFood'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        return redirect($notification->url ?? route('notifications.index'))->with('success', 'Notification marked as read.');
    }

    public function readAndRedirect(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        return redirect($notification->url ?? route('notifications.index'));
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
