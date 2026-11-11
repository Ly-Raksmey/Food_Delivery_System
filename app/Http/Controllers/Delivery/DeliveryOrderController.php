<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class DeliveryOrderController extends Controller
{
    public function index()
    {
        $staffId = auth()->id();

        $assignedOrders = Delivery::where('delivery_staff_id', $staffId)
            ->where('status', 'assigned')
            ->with('order.user', 'order.address')
            ->get();

        $activeOrders = Delivery::where('delivery_staff_id', $staffId)
            ->whereIn('status', ['accepted', 'picked_up', 'out_for_delivery'])
            ->with('order.user', 'order.address')
            ->get();

        $completedOrders = Delivery::where('delivery_staff_id', $staffId)
            ->where('status', 'delivered')
            ->with('order.user', 'order.address')
            ->latest()
            ->take(20)
            ->get();

        $availableOrders = Delivery::whereNull('delivery_staff_id')
            ->where('status', 'assigned')
            ->with('order.user', 'order.address')
            ->get();

        return view('delivery.orders.index', compact('assignedOrders', 'activeOrders', 'completedOrders', 'availableOrders'));
    }

    public function show(Order $order)
    {
        $delivery = Delivery::where('order_id', $order->id)
            ->where('delivery_staff_id', auth()->id())
            ->firstOrFail();

        $order->load('items.food', 'address', 'payment', 'user');

        return view('delivery.orders.show', compact('order', 'delivery'));
    }

    public function accept(Order $order)
    {
        $delivery = Delivery::where('order_id', $order->id)
            ->where(function ($query) {
                $query->where('delivery_staff_id', auth()->id())
                      ->orWhereNull('delivery_staff_id');
            })
            ->firstOrFail();

        $delivery->update([
            'delivery_staff_id' => auth()->id(),
            'status' => 'accepted',
        ]);

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Delivery Accepted',
            'message' => "Your order #{$order->order_number} has been accepted for delivery.",
            'type' => 'order',
            'url' => route('orders.show', $order),
        ]);

        return back()->with('success', 'Delivery accepted.');
    }

    public function pickup(Order $order)
    {
        $delivery = Delivery::where('order_id', $order->id)
            ->where('delivery_staff_id', auth()->id())
            ->firstOrFail();

        $delivery->update(['status' => 'picked_up', 'picked_up_at' => now()]);

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Order Picked Up',
            'message' => "Your order #{$order->order_number} has been picked up.",
            'type' => 'order',
            'url' => route('orders.show', $order),
        ]);

        return back()->with('success', 'Order picked up successfully.');
    }

    public function outForDelivery(Order $order)
    {
        $delivery = Delivery::where('order_id', $order->id)
            ->where('delivery_staff_id', auth()->id())
            ->firstOrFail();

        $delivery->update(['status' => 'out_for_delivery']);

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Out for Delivery',
            'message' => "Your order #{$order->order_number} is out for delivery.",
            'type' => 'order',
            'url' => route('orders.show', $order),
        ]);

        return back()->with('success', 'Order marked as out for delivery.');
    }

    public function delivered(Order $order)
    {
        $delivery = Delivery::where('order_id', $order->id)
            ->where('delivery_staff_id', auth()->id())
            ->firstOrFail();

        $delivery->update(['status' => 'delivered', 'delivered_at' => now()]);

        $order->update([
            'order_status' => 'delivered',
            'payment_status' => $order->payment_method === 'khqr' && $order->payment_status !== 'paid' ? 'paid' : $order->payment_status,
        ]);

        if ($order->payment_method === 'khqr' && $order->payment_status !== 'paid') {
            $order->payment()->update(['status' => 'paid']);
        }

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Order Delivered',
            'message' => "Your order #{$order->order_number} has been delivered.",
            'type' => 'order',
            'url' => route('orders.show', $order),
        ]);

        return view('delivery.orders.delivered', compact('order', 'delivery'))->with('success', 'Order delivered successfully. Receipt generated.');
    }

    public function deliverAndPay(Order $order)
    {
        $delivery = Delivery::where('order_id', $order->id)
            ->where('delivery_staff_id', auth()->id())
            ->firstOrFail();

        $order->update([
            'order_status' => 'delivered',
            'payment_status' => 'paid',
        ]);

        $order->payment()->update(['status' => 'paid']);

        $delivery->update(['status' => 'delivered', 'delivered_at' => now()]);

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Order Delivered & Paid',
            'message' => "Your order #{$order->order_number} has been delivered and paid via KHQR.",
            'type' => 'order',
            'url' => route('orders.show', $order),
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'redirect' => route('delivery.orders.delivered', $order)]);
        }

        return view('delivery.orders.delivered', compact('order', 'delivery'))->with('success', 'Order delivered and paid successfully.');
    }

    public function markPaid(Order $order)
    {
        $delivery = Delivery::where('order_id', $order->id)
            ->where('delivery_staff_id', auth()->id())
            ->firstOrFail();

        $order->update(['payment_status' => 'paid']);
        $order->payment()->update(['status' => 'paid']);

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Payment Received',
            'message' => "Payment for order #{$order->order_number} has been marked as paid.",
            'type' => 'payment',
            'url' => route('orders.show', $order),
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Payment marked as received.']);
        }

        return back()->with('success', 'Payment marked as received.');
    }

    public function receipt(Order $order)
    {
        $delivery = Delivery::where('order_id', $order->id)
            ->where('delivery_staff_id', auth()->id())
            ->firstOrFail();

        $order->load('items.food', 'address', 'payment', 'user');

        if (request()->ajax()) {
            return view('shared.receipt-content', compact('order', 'delivery'));
        }

        return view('shared.receipt', compact('order', 'delivery'))
            ->with('backUrl', route('delivery.orders.index'));
    }

    public function receiptDownload(Order $order)
    {
        $delivery = Delivery::where('order_id', $order->id)
            ->where('delivery_staff_id', auth()->id())
            ->firstOrFail();

        $order->load('items.food', 'address', 'payment', 'user');

        $filename = 'receipt-' . ($order->order_number ?? $order->id) . '.pdf';

        $pdf = Pdf::loadView('shared.receipt-pdf', compact('order', 'delivery'))
            ->setPaper([0, 0, 283, 842]);

        return $pdf->download($filename);
    }
}
