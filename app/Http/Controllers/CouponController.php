<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Cart;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        if (!$coupon->isValid()) {
            return back()->with('error', 'This coupon is no longer valid.');
        }

        $cart = auth()->user()->cart;
        $subtotal = $cart ? $cart->total : 0;

        if ($coupon->minimum_order_amount && $subtotal < $coupon->minimum_order_amount) {
            return back()->with('error', "Minimum order amount of {$coupon->minimum_order_amount} is required to use this coupon.");
        }

        if ($coupon->used_count >= $coupon->usage_limit) {
            return back()->with('error', 'This coupon has been used up.');
        }

        session(['coupon' => $coupon]);

        return back()->with('success', 'Coupon applied successfully.');
    }

    public function remove()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon removed successfully.');
    }
}
