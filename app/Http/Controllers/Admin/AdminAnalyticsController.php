<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        $popularFoods = Food::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();

        $last30Days = Carbon::now()->subDays(30);
        $monthlyOrders = Order::where('created_at', '>=', $last30Days)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        $monthlyRevenue = Payment::where('status', 'paid')
            ->where('created_at', '>=', $last30Days)
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        $categoryStats = Food::selectRaw('category_id, COUNT(*) as count, SUM(price) as total_value')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $chartData = [
            'popularFoods' => $popularFoods,
            'monthlyOrders' => $monthlyOrders,
            'monthlyRevenue' => $monthlyRevenue,
            'categoryStats' => $categoryStats,
        ];

        return view('admin.analytics.index', compact('chartData'));
    }
}
