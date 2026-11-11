<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'payment', 'delivery.deliveryStaff')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function grid()
    {
        $orders = Order::with('user', 'payment', 'delivery.deliveryStaff')->latest()->paginate(20);
        return view('admin.orders.grid', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.food', 'address', 'payment', 'delivery.deliveryStaff', 'couponUsages.coupon');

        $allStatuses = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'preparing' => 'Preparing',
            'ready' => 'Ready',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];

        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['ready', 'cancelled'],
            'ready' => ['out_for_delivery', 'cancelled'],
            'out_for_delivery' => ['delivered', 'cancelled'],
            'delivered' => [],
            'cancelled' => [],
        ];

        $availableStatuses = [];
        foreach ($allowedTransitions[$order->order_status] ?? [] as $status) {
            $availableStatuses[$status] = $allStatuses[$status];
        }

        return view('admin.orders.show', compact('order', 'availableStatuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,confirmed,preparing,ready,out_for_delivery,delivered,cancelled',
        ]);

        $newStatus = $request->order_status;
        $currentStatus = $order->order_status;

        // Allow any status change for admin flexibility
        if ($currentStatus === $newStatus) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'new_status' => $newStatus, 'message' => 'Status is already set to this value.']);
            }
            return back()->with('success', 'Status is already set to this value.');
        }

        $order->update(['order_status' => $newStatus]);

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Order Status Updated',
            'message' => "Your order #{$order->order_number} is now {$newStatus}.",
            'type' => 'order',
            'url' => route('orders.show', $order),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'new_status' => $newStatus]);
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    public function receipt(Order $order)
    {
        $order->load('items.food', 'address', 'payment', 'delivery.deliveryStaff', 'user');
        $delivery = $order->delivery()->with('deliveryStaff')->first();

        if (request()->ajax()) {
            return view('shared.receipt-content', compact('order', 'delivery'));
        }

        return view('shared.receipt', compact('order', 'delivery'))
            ->with('backUrl', route('admin.orders.index'));
    }

    public function receiptDownload(Order $order)
    {
        $order->load('items.food', 'address', 'payment', 'delivery.deliveryStaff', 'user');
        $delivery = $order->delivery()->with('deliveryStaff')->first();

        $filename = 'receipt-' . ($order->order_number ?? $order->id) . '.pdf';

        $pdf = Pdf::loadView('shared.receipt-pdf', compact('order', 'delivery'))
            ->setPaper([0, 0, 283, 842]);

        return $pdf->download($filename);
    }

    private function isValidStatusTransition(string $from, string $to): bool
    {
        if ($from === $to) {
            return true;
        }

        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['ready', 'cancelled'],
            'ready' => ['out_for_delivery', 'cancelled'],
            'out_for_delivery' => ['delivered', 'cancelled'],
            'delivered' => [],
            'cancelled' => [],
        ];

        return in_array($to, $allowedTransitions[$from] ?? []);
    }
}
