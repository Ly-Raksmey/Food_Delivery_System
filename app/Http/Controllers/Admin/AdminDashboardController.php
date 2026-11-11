<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Food;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Review;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Delivery;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subDays(6)->startOfDay();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $totalCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRevenue = Payment::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
        $totalFoods = Food::count();
        $totalPendingReviews = Review::where('status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $pendingOrders = Order::where('order_status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $completedOrders = Order::where('order_status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $cancelledOrders = Order::where('order_status', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $totalIncome = Payment::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;
        $avgOrderValue = $completedOrders > 0 ? ($totalIncome / $completedOrders) : 0;
        $totalDiscount = Order::whereBetween('created_at', [$startDate, $endDate])
            ->sum('discount_amount');

        $rangeRevenue = $totalIncome;

        $recentOrders = Order::with('user', 'payment')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(10)
            ->get();

        $diffInDays = $startDate->diffInDays($endDate);

        $salesLabels = [];
        $salesData = [];
        if ($diffInDays <= 7) {
            $currentDate = Carbon::parse($startDate);
            while ($currentDate->lte($endDate)) {
                $salesLabels[] = $currentDate->format('M d');
                $salesData[] = Payment::where('status', 'paid')
                    ->whereDate('created_at', $currentDate)
                    ->sum('amount');
                $currentDate->addDay();
            }
        } else {
            $groupBy = 'month';
            if ($diffInDays > 365) {
                $groupBy = 'year';
            }
            $current = Carbon::parse($startDate);
            while ($current->lte($endDate)) {
                if ($groupBy === 'month') {
                    $salesLabels[] = $current->format('M Y');
                    $salesData[] = Payment::where('status', 'paid')
                        ->whereMonth('created_at', $current->month)
                        ->whereYear('created_at', $current->year)
                        ->sum('amount');
                    $current->addMonth();
                } else {
                    $salesLabels[] = $current->format('Y');
                    $salesData[] = Payment::where('status', 'paid')
                        ->whereYear('created_at', $current->year)
                        ->sum('amount');
                    $current->addYear();
                }
            }
        }

        $statusLabels = [];
        $statusData = [];
        $statusColors = [
            'pending' => '#ffc107',
            'confirmed' => '#0d6efd',
            'preparing' => '#0dcaf0',
            'ready' => '#6f42c1',
            'out_for_delivery' => '#fd7e14',
            'delivered' => '#198754',
            'cancelled' => '#dc3545',
        ];

        $orderStatusData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('order_status, COUNT(*) as count')
            ->groupBy('order_status')
            ->get();

        foreach ($orderStatusData as $item) {
            $statusLabels[] = ucfirst(str_replace('_', ' ', $item->order_status));
            $statusData[] = $item->count;
        }

        $ieLabels = [];
        $incomeData = [];
        $expenseData = [];
        if ($diffInDays <= 7) {
            $currentDate = Carbon::parse($startDate);
            while ($currentDate->lte($endDate)) {
                $ieLabels[] = $currentDate->format('M d');
                $incomeData[] = Payment::where('status', 'paid')
                    ->whereDate('created_at', $currentDate)
                    ->sum('amount');
                $expenseData[] = Expense::whereDate('expense_date', $currentDate)
                    ->sum('amount');
                $currentDate->addDay();
            }
        } elseif ($diffInDays <= 365) {
            $currentMonth = Carbon::parse($startDate)->startOfMonth();
            while ($currentMonth->lte($endDate)) {
                $ieLabels[] = $currentMonth->format('M Y');
                $incomeData[] = Payment::where('status', 'paid')
                    ->whereMonth('created_at', $currentMonth->month)
                    ->whereYear('created_at', $currentMonth->year)
                    ->sum('amount');
                $expenseData[] = Expense::whereMonth('expense_date', $currentMonth->month)
                    ->whereYear('expense_date', $currentMonth->year)
                    ->sum('amount');
                $currentMonth->addMonth();
            }
        } else {
            $currentYear = Carbon::parse($startDate)->startOfYear();
            while ($currentYear->lte($endDate)) {
                $ieLabels[] = $currentYear->format('Y');
                $incomeData[] = Payment::where('status', 'paid')
                    ->whereYear('created_at', $currentYear->year)
                    ->sum('amount');
                $expenseData[] = Expense::whereYear('expense_date', $currentYear->year)
                    ->sum('amount');
                $currentYear->addYear();
            }
        }

        $stats = [
            'total_customers' => $totalCustomers,
            'total_foods' => $totalFoods,
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'today_revenue' => $rangeRevenue,
            'monthly_revenue' => $rangeRevenue,
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
        ];

        $websiteStats = [
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'customer_users' => User::where('role', 'customer')->count(),
            'delivery_users' => User::where('role', 'delivery')->count(),
            'total_categories' => Category::count(),
            'active_categories' => Category::where('status', 'active')->count(),
            'total_foods' => Food::count(),
            'available_foods' => Food::where('status', 'available')->count(),
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders,
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'avg_order_value' => $avgOrderValue,
            'total_coupons' => Coupon::count(),
            'active_coupons' => Coupon::where('status', 'active')->count(),
            'total_reviews' => Review::count(),
            'avg_rating' => Review::avg('rating'),
            'total_deliveries' => Delivery::count(),
            'completed_deliveries' => Delivery::where('status', 'delivered')->count(),
            'total_payments' => Payment::count(),
            'paid_payments' => Payment::where('status', 'paid')->count(),
            'total_discount' => $totalDiscount,
        ];

        return view('admin.dashboard', compact(
            'stats',
            'websiteStats',
            'recentOrders',
            'salesLabels',
            'salesData',
            'statusLabels',
            'statusData',
            'statusColors',
            'ieLabels',
            'incomeData',
            'expenseData',
            'startDate',
            'endDate'
        ));
    }

    public function income(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subMonth()->startOfDay();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $payments = Payment::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        $totalIncome = Payment::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('DATE(expense_date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        $totalExpense = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        $diffInDays = $startDate->diffInDays($endDate);

        $salesLabels = [];
        $salesData = [];
        if ($diffInDays <= 7) {
            $currentDate = Carbon::parse($startDate);
            while ($currentDate->lte($endDate)) {
                $salesLabels[] = $currentDate->format('M d');
                $salesData[] = Payment::where('status', 'paid')
                    ->whereDate('created_at', $currentDate)
                    ->sum('amount');
                $currentDate->addDay();
            }
        } elseif ($diffInDays <= 365) {
            $currentMonth = Carbon::parse($startDate)->startOfMonth();
            while ($currentMonth->lte($endDate)) {
                $salesLabels[] = $currentMonth->format('M Y');
                $salesData[] = Payment::where('status', 'paid')
                    ->whereMonth('created_at', $currentMonth->month)
                    ->whereYear('created_at', $currentMonth->year)
                    ->sum('amount');
                $currentMonth->addMonth();
            }
        } else {
            $currentYear = Carbon::parse($startDate)->startOfYear();
            while ($currentYear->lte($endDate)) {
                $salesLabels[] = $currentYear->format('Y');
                $salesData[] = Payment::where('status', 'paid')
                    ->whereYear('created_at', $currentYear->year)
                    ->sum('amount');
                $currentYear->addYear();
            }
        }

        $statusLabels = [];
        $statusData = [];
        $statusColors = [
            'pending' => '#ffc107',
            'confirmed' => '#0d6efd',
            'preparing' => '#0dcaf0',
            'ready' => '#6f42c1',
            'out_for_delivery' => '#fd7e14',
            'delivered' => '#198754',
            'cancelled' => '#dc3545',
        ];

        $orderStatusData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('order_status, COUNT(*) as count')
            ->groupBy('order_status')
            ->get();

        foreach ($orderStatusData as $item) {
            $statusLabels[] = ucfirst(str_replace('_', ' ', $item->order_status));
            $statusData[] = $item->count;
        }

        $ieLabels = [];
        $incomeData = [];
        $expenseData = [];
        if ($diffInDays <= 7) {
            $currentDate = Carbon::parse($startDate);
            while ($currentDate->lte($endDate)) {
                $ieLabels[] = $currentDate->format('M d');
                $incomeData[] = Payment::where('status', 'paid')
                    ->whereDate('created_at', $currentDate)
                    ->sum('amount');
                $expenseData[] = Expense::whereDate('expense_date', $currentDate)
                    ->sum('amount');
                $currentDate->addDay();
            }
        } elseif ($diffInDays <= 365) {
            $currentMonth = Carbon::parse($startDate)->startOfMonth();
            while ($currentMonth->lte($endDate)) {
                $ieLabels[] = $currentMonth->format('M Y');
                $incomeData[] = Payment::where('status', 'paid')
                    ->whereMonth('created_at', $currentMonth->month)
                    ->whereYear('created_at', $currentMonth->year)
                    ->sum('amount');
                $expenseData[] = Expense::whereMonth('expense_date', $currentMonth->month)
                    ->whereYear('expense_date', $currentMonth->year)
                    ->sum('amount');
                $currentMonth->addMonth();
            }
        } else {
            $currentYear = Carbon::parse($startDate)->startOfYear();
            while ($currentYear->lte($endDate)) {
                $ieLabels[] = $currentYear->format('Y');
                $incomeData[] = Payment::where('status', 'paid')
                    ->whereYear('created_at', $currentYear->year)
                    ->sum('amount');
                $expenseData[] = Expense::whereYear('expense_date', $currentYear->year)
                    ->sum('amount');
                $currentYear->addYear();
            }
        }

        $recentOrders = Order::with('user', 'payment')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'payments',
            'totalIncome',
            'expenses',
            'totalExpense',
            'recentOrders',
            'salesLabels',
            'salesData',
            'statusLabels',
            'statusData',
            'statusColors',
            'ieLabels',
            'incomeData',
            'expenseData',
            'startDate',
            'endDate'
        ))->with([
            'stats' => [
                'total_customers' => 0,
                'total_foods' => 0,
                'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
                'pending_orders' => Order::where('order_status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'completed_orders' => Order::where('order_status', 'delivered')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'today_revenue' => $totalIncome,
                'monthly_revenue' => $totalIncome,
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpense,
                'net_profit' => $totalIncome - $totalExpense,
            ],
            'websiteStats' => [
                'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
                'pending_orders' => Order::where('order_status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'completed_orders' => Order::where('order_status', 'delivered')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'cancelled_orders' => Order::where('order_status', 'cancelled')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'total_revenue' => $totalIncome,
                'total_expenses' => $totalExpense,
                'net_profit' => $totalIncome - $totalExpense,
            ],
        ]);
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        Expense::create([
            'title' => 'Quick Add - ' . $request->type,
            'category' => ucfirst($request->type),
            'amount' => $request->amount,
            'description' => $request->description,
            'expense_date' => Carbon::today(),
        ]);

        return back()->with('success', 'Quick entry added successfully.');
    }
}
