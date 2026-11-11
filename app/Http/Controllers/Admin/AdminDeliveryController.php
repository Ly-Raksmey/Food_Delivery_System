<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\User;
use App\Models\Notification;

class AdminDeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::with('order.user', 'deliveryStaff')->latest()->paginate(20);
        return view('admin.deliveries.index', compact('deliveries'));
    }

    public function grid()
    {
        $deliveries = Delivery::with('order.user', 'deliveryStaff')->latest()->paginate(20);
        return view('admin.deliveries.grid', compact('deliveries'));
    }

    public function assign(Request $request, Order $order)
    {
        $request->validate([
            'delivery_staff_id' => 'required|exists:users,id',
        ]);

        $deliveryStaff = User::where('id', $request->delivery_staff_id)
            ->where('role', 'delivery')
            ->firstOrFail();

        Delivery::create([
            'order_id' => $order->id,
            'delivery_staff_id' => $deliveryStaff->id,
            'status' => 'assigned',
        ]);

        $order->update(['order_status' => 'confirmed']);

        Notification::create([
            'user_id' => $deliveryStaff->id,
            'title' => 'New Delivery Assignment',
            'message' => "You have been assigned to deliver order #{$order->order_number}.",
            'type' => 'delivery',
            'url' => route('delivery.orders.index'),
        ]);

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Delivery Assigned',
            'message' => "A delivery staff has been assigned to your order #{$order->order_number}.",
            'type' => 'order',
            'url' => route('orders.show', $order),
        ]);

        return back()->with('success', 'Delivery staff assigned successfully.');
    }

    public function show(Delivery $delivery)
    {
        $delivery->load('order.user', 'order.address', 'order.items.food', 'deliveryStaff');
        return view('admin.deliveries.show', compact('delivery'));
    }
}
