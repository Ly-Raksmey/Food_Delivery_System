<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Food;
use App\Models\User;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'daily');
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::now();
        $timeFilter = $request->get('time_filter', '');
        $orderStatus = $request->get('order_status', '');
        $paymentMethod = $request->get('payment_method', '');
        $paymentStatus = $request->get('payment_status', '');
        $customerId = $request->get('customer_id', '');

        if ($period === 'daily') {
            $startDate = $startDate->startOfDay();
            $endDate = $startDate->copy()->endOfDay();
        } elseif ($period === 'monthly') {
            $startDate = $startDate->startOfMonth();
            $endDate = $endDate->endOfMonth();
        } elseif ($period === 'yearly') {
            $startDate = $startDate->startOfYear();
            $endDate = $endDate->endOfYear();
        }

        $query = Order::whereRaw("DATE(CONVERT_TZ(created_at, '+00:00', '+07:00')) BETWEEN ? AND ?", [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($timeFilter === 'morning') {
            $query->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '00:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '11:59:59'");
        } elseif ($timeFilter === 'evening') {
            $query->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '12:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '17:59:59'");
        } elseif ($timeFilter === 'night') {
            $query->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '18:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '23:59:59'");
        }

        if ($orderStatus) {
            $query->where('order_status', $orderStatus);
        }
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }
        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }
        if ($customerId) {
            $query->where('user_id', $customerId);
        }

        $orders = $query->get();
        $payments = Payment::where('status', 'paid')
            ->whereRaw("DATE(CONVERT_TZ(created_at, '+00:00', '+07:00')) BETWEEN ? AND ?", [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        $expenses = Expense::whereBetween('expense_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        if ($paymentStatus === 'paid') {
            $payments->where('status', 'paid');
        } elseif ($paymentStatus === 'pending') {
            $payments->where('status', 'pending');
        }
        if ($customerId) {
            $payments->whereIn('order_id', $orders->pluck('id'));
        }

        $payments = $payments->get();

        $totalSales = $payments->sum('amount');
        $totalExpenses = $expenses->sum('amount');
        $profit = $totalSales - $totalExpenses;

        $salesByFood = OrderItem::whereIn('order_id', $orders->pluck('id'))
            ->selectRaw('food_id, SUM(quantity) as total_qty, SUM(subtotal) as total_amount')
            ->groupBy('food_id')
            ->with('food')
            ->get();

        $details = [];
        if ($period === 'daily') {
            $currentDate = Carbon::parse($startDate);
            while ($currentDate->lte($endDate)) {
                $dateStr = $currentDate->format('Y-m-d');
                $dayOrders = $orders->filter(function ($order) use ($dateStr) {
                    return Carbon::parse($order->created_at)->setTimezone('Asia/Phnom_Penh')->format('Y-m-d') === $dateStr;
                });
                $dayRevenue = $payments->filter(function ($payment) use ($dateStr) {
                    return Carbon::parse($payment->created_at)->setTimezone('Asia/Phnom_Penh')->format('Y-m-d') === $dateStr;
                })->sum('amount');
                $dayExpenses = $expenses->filter(function ($expense) use ($dateStr) {
                    return Carbon::parse($expense->expense_date)->format('Y-m-d') === $dateStr;
                })->sum('amount');

                $details[] = [
                    'date' => $currentDate->format('M d, Y'),
                    'orders' => $dayOrders->count(),
                    'revenue' => $dayRevenue,
                    'expenses' => $dayExpenses,
                    'profit' => $dayRevenue - $dayExpenses,
                ];
                $currentDate->addDay();
            }
        } elseif ($period === 'monthly') {
            $currentMonth = Carbon::parse($startDate)->startOfMonth();
            while ($currentMonth->lte($endDate)) {
                $monthOrders = $orders->filter(function ($order) use ($currentMonth) {
                    $orderDate = Carbon::parse($order->created_at)->setTimezone('Asia/Phnom_Penh');
                    return $orderDate->month === $currentMonth->month && $orderDate->year === $currentMonth->year;
                });
                $monthRevenue = $payments->filter(function ($payment) use ($currentMonth) {
                    $paymentDate = Carbon::parse($payment->created_at)->setTimezone('Asia/Phnom_Penh');
                    return $paymentDate->month === $currentMonth->month && $paymentDate->year === $currentMonth->year;
                })->sum('amount');
                $monthExpenses = $expenses->filter(function ($expense) use ($currentMonth) {
                    return Carbon::parse($expense->expense_date)->month === $currentMonth->month
                        && Carbon::parse($expense->expense_date)->year === $currentMonth->year;
                })->sum('amount');

                $details[] = [
                    'date' => $currentMonth->format('M Y'),
                    'orders' => $monthOrders->count(),
                    'revenue' => $monthRevenue,
                    'expenses' => $monthExpenses,
                    'profit' => $monthRevenue - $monthExpenses,
                ];
                $currentMonth->addMonth();
            }
        } else {
            $currentYear = Carbon::parse($startDate)->startOfYear();
            while ($currentYear->lte($endDate)) {
                $yearOrders = $orders->filter(function ($order) use ($currentYear) {
                    return Carbon::parse($order->created_at)->setTimezone('Asia/Phnom_Penh')->year === $currentYear->year;
                });
                $yearRevenue = $payments->filter(function ($payment) use ($currentYear) {
                    return Carbon::parse($payment->created_at)->setTimezone('Asia/Phnom_Penh')->year === $currentYear->year;
                })->sum('amount');
                $yearExpenses = $expenses->filter(function ($expense) use ($currentYear) {
                    return Carbon::parse($expense->expense_date)->year === $currentYear->year;
                })->sum('amount');

                $details[] = [
                    'date' => $currentYear->format('Y'),
                    'orders' => $yearOrders->count(),
                    'revenue' => $yearRevenue,
                    'expenses' => $yearExpenses,
                    'profit' => $yearRevenue - $yearExpenses,
                ];
                $currentYear->addYear();
            }
        }

        $report_data = [
            'total_orders' => $orders->count(),
            'total_revenue' => $totalSales,
            'total_expenses' => $totalExpenses,
            'net_profit' => $profit,
            'details' => $details,
            'topFoods' => $salesByFood->take(10),
        ];

        $filteredOrders = Order::with('user', 'payment', 'items.food')
            ->whereRaw("DATE(CONVERT_TZ(created_at, '+00:00', '+07:00')) BETWEEN ? AND ?", [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($timeFilter === 'morning') {
            $filteredOrders->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '00:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '11:59:59'");
        } elseif ($timeFilter === 'evening') {
            $filteredOrders->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '12:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '17:59:59'");
        } elseif ($timeFilter === 'night') {
            $filteredOrders->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '18:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '23:59:59'");
        }
        if ($orderStatus) {
            $filteredOrders->where('order_status', $orderStatus);
        }
        if ($paymentMethod) {
            $filteredOrders->where('payment_method', $paymentMethod);
        }
        if ($paymentStatus) {
            $filteredOrders->where('payment_status', $paymentStatus);
        }
        if ($customerId) {
            $filteredOrders->where('user_id', $customerId);
        }

        $filteredOrders = $filteredOrders->latest()->get();

        $customers = User::where('role', 'customer')->orderBy('name')->get();

        return view('admin.reports.index', compact('report_data', 'period', 'startDate', 'endDate', 'filteredOrders', 'timeFilter', 'customers'));
    }

    public function print(Request $request)
    {
        $period = $request->get('period', 'daily');
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::now();
        $timeFilter = $request->get('time_filter', '');
        $orderStatus = $request->get('order_status', '');
        $paymentMethod = $request->get('payment_method', '');
        $paymentStatus = $request->get('payment_status', '');
        $customerId = $request->get('customer_id', '');

        if ($period === 'daily') {
            $startDate = $startDate->startOfDay();
            $endDate = $startDate->copy()->endOfDay();
        } elseif ($period === 'monthly') {
            $startDate = $startDate->startOfMonth();
            $endDate = $endDate->endOfMonth();
        } elseif ($period === 'yearly') {
            $startDate = $startDate->startOfYear();
            $endDate = $endDate->endOfYear();
        }

        $query = Order::whereRaw("DATE(CONVERT_TZ(created_at, '+00:00', '+07:00')) BETWEEN ? AND ?", [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($timeFilter === 'morning') {
            $query->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '00:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '11:59:59'");
        } elseif ($timeFilter === 'evening') {
            $query->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '12:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '17:59:59'");
        } elseif ($timeFilter === 'night') {
            $query->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '18:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '23:59:59'");
        }

        if ($orderStatus) {
            $query->where('order_status', $orderStatus);
        }
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }
        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }
        if ($customerId) {
            $query->where('user_id', $customerId);
        }

        $orders = $query->get();
        $payments = Payment::where('status', 'paid')
            ->whereRaw("DATE(CONVERT_TZ(created_at, '+00:00', '+07:00')) BETWEEN ? AND ?", [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        $expenses = Expense::whereBetween('expense_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        if ($paymentStatus === 'paid') {
            $payments->where('status', 'paid');
        } elseif ($paymentStatus === 'pending') {
            $payments->where('status', 'pending');
        }
        if ($customerId) {
            $payments->whereIn('order_id', $orders->pluck('id'));
        }

        $payments = $payments->get();

        $totalSales = $payments->sum('amount');
        $totalExpenses = $expenses->sum('amount');
        $profit = $totalSales - $totalExpenses;

        $salesByFood = OrderItem::whereIn('order_id', $orders->pluck('id'))
            ->selectRaw('food_id, SUM(quantity) as total_qty, SUM(subtotal) as total_amount')
            ->groupBy('food_id')
            ->with('food')
            ->get();

        $details = [];
        if ($period === 'daily') {
            $currentDate = Carbon::parse($startDate);
            while ($currentDate->lte($endDate)) {
                $dateStr = $currentDate->format('Y-m-d');
                $dayOrders = $orders->filter(function ($order) use ($dateStr) {
                    return Carbon::parse($order->created_at)->setTimezone('Asia/Phnom_Penh')->format('Y-m-d') === $dateStr;
                });
                $dayRevenue = $payments->filter(function ($payment) use ($dateStr) {
                    return Carbon::parse($payment->created_at)->setTimezone('Asia/Phnom_Penh')->format('Y-m-d') === $dateStr;
                })->sum('amount');
                $dayExpenses = $expenses->filter(function ($expense) use ($dateStr) {
                    return Carbon::parse($expense->expense_date)->format('Y-m-d') === $dateStr;
                })->sum('amount');

                $details[] = [
                    'date' => $currentDate->format('M d, Y'),
                    'orders' => $dayOrders->count(),
                    'revenue' => $dayRevenue,
                    'expenses' => $dayExpenses,
                    'profit' => $dayRevenue - $dayExpenses,
                ];
                $currentDate->addDay();
            }
        } elseif ($period === 'monthly') {
            $currentMonth = Carbon::parse($startDate)->startOfMonth();
            while ($currentMonth->lte($endDate)) {
                $monthOrders = $orders->filter(function ($order) use ($currentMonth) {
                    $orderDate = Carbon::parse($order->created_at)->setTimezone('Asia/Phnom_Penh');
                    return $orderDate->month === $currentMonth->month && $orderDate->year === $currentMonth->year;
                });
                $monthRevenue = $payments->filter(function ($payment) use ($currentMonth) {
                    $paymentDate = Carbon::parse($payment->created_at)->setTimezone('Asia/Phnom_Penh');
                    return $paymentDate->month === $currentMonth->month && $paymentDate->year === $currentMonth->year;
                })->sum('amount');
                $monthExpenses = $expenses->filter(function ($expense) use ($currentMonth) {
                    return Carbon::parse($expense->expense_date)->month === $currentMonth->month
                        && Carbon::parse($expense->expense_date)->year === $currentMonth->year;
                })->sum('amount');

                $details[] = [
                    'date' => $currentMonth->format('M Y'),
                    'orders' => $monthOrders->count(),
                    'revenue' => $monthRevenue,
                    'expenses' => $monthExpenses,
                    'profit' => $monthRevenue - $monthExpenses,
                ];
                $currentMonth->addMonth();
            }
        } else {
            $currentYear = Carbon::parse($startDate)->startOfYear();
            while ($currentYear->lte($endDate)) {
                $yearOrders = $orders->filter(function ($order) use ($currentYear) {
                    return Carbon::parse($order->created_at)->setTimezone('Asia/Phnom_Penh')->year === $currentYear->year;
                });
                $yearRevenue = $payments->filter(function ($payment) use ($currentYear) {
                    return Carbon::parse($payment->created_at)->setTimezone('Asia/Phnom_Penh')->year === $currentYear->year;
                })->sum('amount');
                $yearExpenses = $expenses->filter(function ($expense) use ($currentYear) {
                    return Carbon::parse($expense->expense_date)->year === $currentYear->year;
                })->sum('amount');

                $details[] = [
                    'date' => $currentYear->format('Y'),
                    'orders' => $yearOrders->count(),
                    'revenue' => $yearRevenue,
                    'expenses' => $yearExpenses,
                    'profit' => $yearRevenue - $yearExpenses,
                ];
                $currentYear->addYear();
            }
        }

        $report_data = [
            'total_orders' => $orders->count(),
            'total_revenue' => $totalSales,
            'total_expenses' => $totalExpenses,
            'net_profit' => $profit,
            'details' => $details,
            'topFoods' => $salesByFood->take(10),
        ];

        $filteredOrders = Order::with('user', 'payment', 'items.food')
            ->whereRaw("DATE(CONVERT_TZ(created_at, '+00:00', '+07:00')) BETWEEN ? AND ?", [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($timeFilter === 'morning') {
            $filteredOrders->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '00:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '11:59:59'");
        } elseif ($timeFilter === 'evening') {
            $filteredOrders->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '12:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '17:59:59'");
        } elseif ($timeFilter === 'night') {
            $filteredOrders->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '18:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '23:59:59'");
        }
        if ($orderStatus) {
            $filteredOrders->where('order_status', $orderStatus);
        }
        if ($paymentMethod) {
            $filteredOrders->where('payment_method', $paymentMethod);
        }
        if ($paymentStatus) {
            $filteredOrders->where('payment_status', $paymentStatus);
        }
        if ($customerId) {
            $filteredOrders->where('user_id', $customerId);
        }

        $filteredOrders = $filteredOrders->latest()->get();

        $customers = User::where('role', 'customer')->orderBy('name')->get();

        return view('admin.reports.print', compact('report_data', 'period', 'startDate', 'endDate', 'filteredOrders', 'timeFilter', 'customers'));
    }

    public function exportExcel(Request $request)
    {
        $period = $request->get('period', 'daily');
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::now();
        $timeFilter = $request->get('time_filter', '');
        $orderStatus = $request->get('order_status', '');
        $paymentMethod = $request->get('payment_method', '');
        $paymentStatus = $request->get('payment_status', '');
        $customerId = $request->get('customer_id', '');

        if ($period === 'daily') {
            $startDate = $startDate->startOfDay();
            $endDate = $startDate->copy()->endOfDay();
        } elseif ($period === 'monthly') {
            $startDate = $startDate->startOfMonth();
            $endDate = $endDate->endOfMonth();
        } elseif ($period === 'yearly') {
            $startDate = $startDate->startOfYear();
            $endDate = $endDate->endOfYear();
        }

        $query = Order::whereRaw("DATE(CONVERT_TZ(created_at, '+00:00', '+07:00')) BETWEEN ? AND ?", [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($timeFilter === 'morning') {
            $query->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '00:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '11:59:59'");
        } elseif ($timeFilter === 'evening') {
            $query->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '12:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '17:59:59'");
        } elseif ($timeFilter === 'night') {
            $query->whereRaw("TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) >= '18:00:00' AND TIME(CONVERT_TZ(created_at, '+00:00', '+07:00')) <= '23:59:59'");
        }
        if ($orderStatus) {
            $query->where('order_status', $orderStatus);
        }
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }
        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }
        if ($customerId) {
            $query->where('user_id', $customerId);
        }

        $filteredOrders = $query->with('user', 'items')->latest()->get();

        $payments = Payment::where('status', 'paid')
            ->whereRaw("DATE(CONVERT_TZ(created_at, '+00:00', '+07:00')) BETWEEN ? AND ?", [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        $totalRevenue = $payments->sum('amount');

        $filename = 'report_' . $period . '_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($filteredOrders, $totalRevenue) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['Business Report']);
            fputcsv($file, ['Period: ' . ($filteredOrders->first()->created_at ?? now())->format('M d, Y') . ' - ' . ($filteredOrders->last()->created_at ?? now())->format('M d, Y')]);
            fputcsv($file, ['Total Revenue: $' . number_format($totalRevenue, 2)]);
            fputcsv($file, ['Total Orders: ' . $filteredOrders->count()]);
            fputcsv($file, []);

            fputcsv($file, ['Order #', 'Customer', 'Date', 'Payment Method', 'Payment Status', 'Order Status', 'Items', 'Total']);

            foreach ($filteredOrders as $order) {
                fputcsv($file, [
                    '#' . ($order->order_number ?? 'N/A'),
                    $order->user->name ?? 'N/A',
                    $order->created_at ? $order->created_at->format('M d, Y H:i') : 'N/A',
                    ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')),
                    ucfirst($order->payment_status ?? 'pending'),
                    ucfirst(str_replace('_', ' ', $order->order_status ?? 'pending')),
                    $order->items->count() ?? 0,
                    '$' . number_format($order->total_amount ?? 0, 2),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
