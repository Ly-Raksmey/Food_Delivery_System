<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\Delivery;
use App\Models\User;
use App\Events\OrderPlaced;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $addresses = $user->addresses;
        $cart = $user->cart()->with('items.food')->first();
        $coupon = session('coupon');

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cart->total;
        $discount = 0;
        $deliveryFee = 2.00;

        if ($coupon) {
            if ($coupon->type === 'percentage') {
                $discount = ($subtotal * $coupon->value) / 100;
                if ($coupon->maximum_discount) {
                    $discount = min($discount, $coupon->maximum_discount);
                }
            } else {
                $discount = min($coupon->value, $subtotal);
            }
        }

        $total = $subtotal + $deliveryFee - $discount;

        return view('customer.checkout.index', compact('addresses', 'cart', 'subtotal', 'discount', 'deliveryFee', 'total', 'coupon'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_type' => 'required|in:saved,new',
            'address_id' => 'nullable|exists:addresses,id',
            'recipient_name' => 'nullable|required_if:address_type,new|string|max:255',
            'phone' => 'nullable|required_if:address_type,new|string|max:20',
            'address' => 'nullable|required_if:address_type,new|string|max:500',
            'city' => 'nullable|required_if:address_type,new|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'payment_method' => 'required|in:cash,khqr',
            'khqr_payment_type' => 'nullable|required_if:payment_method,khqr|in:now,delivery',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        if ($request->address_type === 'saved') {
            $address = Address::where('id', $request->address_id)
                ->where('user_id', $user->id)
                ->firstOrFail();
        } else {
            $address = Address::create([
                'user_id' => $user->id,
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
        }

        $cart = $user->cart()->with('items.food')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cart->total;
        $discount = 0;
        $deliveryFee = 2.00;
        $coupon = session('coupon');

        if ($coupon) {
            if ($coupon->type === 'percentage') {
                $discount = ($subtotal * $coupon->value) / 100;
                if ($coupon->maximum_discount) {
                    $discount = min($discount, $coupon->maximum_discount);
                }
            } else {
                $discount = min($coupon->value, $subtotal);
            }
        }

        $total = $subtotal + $deliveryFee - $discount;

        DB::beginTransaction();
        try {
            $orderNumber = $this->generateOrderNumber();

            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'order_number' => $orderNumber,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $total,
                'coupon_code' => $coupon ? $coupon->code : null,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'khqr' && $request->khqr_payment_type === 'now' ? 'paid' : 'pending',
                'order_status' => $request->payment_method === 'khqr' && $request->khqr_payment_type === 'now' ? 'confirmed' : 'pending',
                'notes' => $request->notes,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $item->food_id,
                    'food_name' => $item->food->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $total,
                'status' => $request->payment_method === 'khqr' && $request->khqr_payment_type === 'now' ? 'paid' : 'pending',
            ]);

            Delivery::create([
                'order_id' => $order->id,
                'delivery_staff_id' => null,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);

            $activeDeliveryStaff = User::where('role', 'delivery')
                ->where('status', 'active')
                ->get();

            foreach ($activeDeliveryStaff as $staff) {
                Notification::create([
                    'user_id' => $staff->id,
                    'title' => 'New Order Available',
                    'message' => "Order #{$orderNumber} is available for delivery. Please accept it from the delivery panel.",
                    'type' => 'delivery',
                    'url' => route('delivery.orders.index'),
                ]);
            }

            if ($coupon) {
                $coupon->increment('used_count');
                session()->forget('coupon');
            }

            $cart->items()->delete();

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Order Placed',
                'message' => "Your order #{$orderNumber} has been placed successfully.",
                'type' => 'order',
                'url' => route('orders.show', $order),
            ]);

            DB::commit();

            event(new OrderPlaced($order));

            return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    private function generateOrderNumber(): string
    {
        $year = now()->year;
        $lastOrder = Order::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $sequence = $lastOrder ? (int) substr($lastOrder->order_number, -6) + 1 : 1;

        return 'FD-' . $year . '-' . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('customer.checkout.success', compact('order'));
    }
}
