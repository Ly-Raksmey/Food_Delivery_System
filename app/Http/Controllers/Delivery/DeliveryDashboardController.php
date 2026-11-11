<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\User;
use Carbon\Carbon;

class DeliveryDashboardController extends Controller
{
    public function index()
    {
        $staffId = auth()->id();
        $startDate = request('start_date') ? Carbon::parse(request('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        $statusFilter = request('status_filter', '');

        $assignedOrders = Delivery::where('delivery_staff_id', $staffId)
            ->where('status', 'assigned')
            ->with('order.user', 'order.address')
            ->count();

        $activeDeliveries = Delivery::where('delivery_staff_id', $staffId)
            ->whereIn('status', ['accepted', 'picked_up', 'out_for_delivery'])
            ->with('order.user', 'order.address')
            ->count();

        $completedDeliveries = Delivery::where('delivery_staff_id', $staffId)
            ->where('status', 'delivered')
            ->whereMonth('delivered_at', Carbon::now()->month)
            ->count();

        $availableOrders = Delivery::whereNull('delivery_staff_id')
            ->where('status', 'assigned')
            ->count();

        $recentDeliveries = Delivery::where('delivery_staff_id', $staffId)
            ->with('order.user', 'order.address', 'order.payment')
            ->latest()
            ->take(10)
            ->get();

        $reportQuery = Delivery::where('delivery_staff_id', $staffId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($statusFilter) {
            $reportQuery->where('status', $statusFilter);
        }

        $reportDeliveries = $reportQuery->with('order.user', 'order.address', 'order.payment')->latest()->get();

        $totalDeliveries = $reportDeliveries->count();
        $reportCompleted = $reportDeliveries->where('status', 'delivered')->count();
        $reportPending = $reportDeliveries->whereIn('status', ['assigned', 'accepted', 'picked_up', 'out_for_delivery'])->count();
        $totalEarnings = $reportDeliveries->where('status', 'delivered')->sum(function ($d) {
            return $d->order->total_amount ?? 0;
        });

        $report_data = [
            'total_deliveries' => $totalDeliveries,
            'completed' => $reportCompleted,
            'pending' => $reportPending,
            'earnings' => $totalEarnings,
        ];

        $restaurantLat = 11.5694;
        $restaurantLng = 104.9234;
        $restaurantName = 'Sire Restaurant';

        $selectedDeliveryId = request('order');

        $selectedDelivery = null;
        if ($selectedDeliveryId) {
            $selectedDelivery = Delivery::where('delivery_staff_id', $staffId)
                ->where('id', $selectedDeliveryId)
                ->with('order.user', 'order.address', 'order.payment')
                ->first();
        }

        $firstAssigned = $selectedDelivery ?: $recentDeliveries->first();
        $activeDelivery = $firstAssigned ? $firstAssigned->order : null;
        $customer = $activeDelivery->user ?? null;
        $address = $activeDelivery->address ?? null;
        $customerLat = $address->latitude ?? null;
        $customerLng = $address->longitude ?? null;

        $driverLat = $firstAssigned?->driver_lat ?? 11.5720;
        $driverLng = $firstAssigned?->driver_lng ?? 104.9200;

        return view('delivery.dashboard', compact(
            'assignedOrders', 'activeDeliveries', 'completedDeliveries', 'availableOrders', 
            'recentDeliveries', 'report_data', 'reportDeliveries', 'startDate', 'endDate', 
            'statusFilter', 'restaurantLat', 'restaurantLng', 'restaurantName', 'driverLat', 'driverLng', 'selectedDelivery'
        ));
    }

    public function report()
    {
        $staffId = auth()->id();
        $startDate = request('start_date') ? Carbon::parse(request('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        $statusFilter = request('status_filter', '');

        $reportQuery = Delivery::where('delivery_staff_id', $staffId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($statusFilter) {
            $reportQuery->where('status', $statusFilter);
        }

        $reportDeliveries = $reportQuery->with('order.user', 'order.address', 'order.payment')->latest()->get();

        $totalDeliveries = $reportDeliveries->count();
        $reportCompleted = $reportDeliveries->where('status', 'delivered')->count();
        $reportPending = $reportDeliveries->whereIn('status', ['assigned', 'accepted', 'picked_up', 'out_for_delivery'])->count();
        $totalEarnings = $reportDeliveries->where('status', 'delivered')->sum(function ($d) {
            return $d->order->total_amount ?? 0;
        });

        $report_data = [
            'total_deliveries' => $totalDeliveries,
            'completed' => $reportCompleted,
            'pending' => $reportPending,
            'earnings' => $totalEarnings,
        ];

        return view('delivery.report', compact('report_data', 'reportDeliveries', 'startDate', 'endDate', 'statusFilter'));
    }

    public function printReport()
    {
        $staffId = auth()->id();
        $startDate = request('start_date') ? Carbon::parse(request('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        $statusFilter = request('status_filter', '');

        $reportQuery = Delivery::where('delivery_staff_id', $staffId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($statusFilter) {
            $reportQuery->where('status', $statusFilter);
        }

        $reportDeliveries = $reportQuery->with('order.user', 'order.address', 'order.payment')->latest()->get();

        $totalDeliveries = $reportDeliveries->count();
        $reportCompleted = $reportDeliveries->where('status', 'delivered')->count();
        $reportPending = $reportDeliveries->whereIn('status', ['assigned', 'accepted', 'picked_up', 'out_for_delivery'])->count();
        $totalEarnings = $reportDeliveries->where('status', 'delivered')->sum(function ($d) {
            return $d->order->total_amount ?? 0;
        });

        $report_data = [
            'total_deliveries' => $totalDeliveries,
            'completed' => $reportCompleted,
            'pending' => $reportPending,
            'earnings' => $totalEarnings,
        ];

        return view('delivery.report-print', compact('report_data', 'reportDeliveries', 'startDate', 'endDate', 'statusFilter'));
    }

    public function exportExcel()
    {
        $staffId = auth()->id();
        $startDate = request('start_date') ? Carbon::parse(request('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        $statusFilter = request('status_filter', '');

        $reportQuery = Delivery::where('delivery_staff_id', $staffId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($statusFilter) {
            $reportQuery->where('status', $statusFilter);
        }

        $reportDeliveries = $reportQuery->with('order.user', 'order.address', 'order.payment')->latest()->get();

        $totalDeliveries = $reportDeliveries->count();
        $reportCompleted = $reportDeliveries->where('status', 'delivered')->count();
        $reportPending = $reportDeliveries->whereIn('status', ['assigned', 'accepted', 'picked_up', 'out_for_delivery'])->count();
        $totalEarnings = $reportDeliveries->where('status', 'delivered')->sum(function ($d) {
            return $d->order->total_amount ?? 0;
        });

        $filename = 'delivery_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($reportDeliveries, $totalDeliveries, $reportCompleted, $reportPending, $totalEarnings) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['Delivery Report']);
            fputcsv($file, ['Driver: ' . (Auth::user()->name ?? 'N/A')]);
            fputcsv($file, ['Period: ' . ($reportDeliveries->first()->created_at ?? now())->format('M d, Y') . ' - ' . ($reportDeliveries->last()->created_at ?? now())->format('M d, Y')]);
            fputcsv($file, []);

            fputcsv($file, ['Summary']);
            fputcsv($file, ['Total Deliveries', $totalDeliveries]);
            fputcsv($file, ['Completed', $reportCompleted]);
            fputcsv($file, ['Pending', $reportPending]);
            fputcsv($file, ['Total Earnings', '$' . number_format($totalEarnings, 2)]);
            fputcsv($file, []);

            fputcsv($file, ['Order #', 'Customer', 'Phone', 'Address', 'Status', 'Date', 'Amount']);

            foreach ($reportDeliveries as $delivery) {
                $order = $delivery->order;
                $customer = $order->user ?? null;
                $address = $order->address ?? null;

                fputcsv($file, [
                    '#' . ($order->order_number ?? 'N/A'),
                    $customer->name ?? 'Unknown',
                    $customer->phone ?? 'N/A',
                    $address->address ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $delivery->status)),
                    $delivery->created_at ? $delivery->created_at->format('M d, Y') : 'N/A',
                    '$' . number_format($order->total_amount ?? 0, 2),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
