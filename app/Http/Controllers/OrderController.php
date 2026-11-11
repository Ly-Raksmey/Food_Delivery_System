<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('user_id', auth()->id())
            ->with('items', 'delivery', 'payment');

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->appends($request->all());

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.food', 'address', 'payment', 'delivery.deliveryStaff');

        return view('customer.orders.show', compact('order'));
    }

    public function track(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $delivery = $order->delivery()->with('deliveryStaff')->first();

        return view('customer.orders.track', compact('order', 'delivery'));
    }

    public function receipt(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.food', 'address', 'payment', 'delivery.deliveryStaff');
        $delivery = $order->delivery()->with('deliveryStaff')->first();

        if (request()->ajax()) {
            return view('shared.receipt-content', compact('order', 'delivery'));
        }

        return view('shared.receipt', compact('order', 'delivery'))
            ->with('backUrl', route('orders.index'));
    }

    public function receiptDownload(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.food', 'address', 'payment', 'delivery.deliveryStaff', 'user');
        $delivery = $order->delivery()->with('deliveryStaff')->first();

        $filename = 'receipt-' . ($order->order_number ?? $order->id) . '.pdf';

        $pdf = Pdf::loadView('shared.receipt-pdf', compact('order', 'delivery'))
            ->setPaper([0, 0, 283, 842]);

        return $pdf->download($filename);
    }
}
