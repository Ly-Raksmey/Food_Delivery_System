
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')




<!-- =========================================================
     PAGE HEADER
========================================================= -->

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">
            Welcome back! Here's what's happening with your restaurant.
        </p>
    </div>

    <div class="d-flex gap-2 align-items-center flex-wrap">

        <div class="btn-group" role="group">
            <button
                type="button"
                class="btn btn-outline-primary btn-sm date-preset"
                data-range="today">
                Today
            </button>

            <button
                type="button"
                class="btn btn-outline-primary btn-sm date-preset"
                data-range="week">
                This Week
            </button>

            <button
                type="button"
                class="btn btn-outline-primary btn-sm date-preset"
                data-range="month">
                This Month
            </button>

            <button
                type="button"
                class="btn btn-outline-primary btn-sm date-preset"
                data-range="year">
                This Year
            </button>
        </div>

        <form
            action="{{ route('admin.dashboard') }}"
            method="GET"
            id="dateFilterForm">

            <div class="d-none">

                <input
                    type="date"
                    name="start_date"
                    id="startDate"
                    value="{{ request('start_date') ?: ($startDate ? $startDate->format('Y-m-d') : '') }}">

                <input
                    type="date"
                    name="end_date"
                    id="endDate"
                    value="{{ request('end_date') ?: ($endDate ? $endDate->format('Y-m-d') : '') }}">

                <button type="submit">
                    Apply
                </button>

            </div>
        </form>

    </div>
</div>


<!-- =========================================================
     STATS CARDS ROW 1
========================================================= -->

<div class="row g-3 mb-4">

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card animate-fade-in-up">

            <div class="stat-card-icon primary">
                <i class="bi bi-people"></i>
            </div>

            <p class="stat-card-label">
                Total Customers
            </p>

            <h3 class="stat-card-value">
                {{ number_format($stats['total_customers'] ?? 0) }}
            </h3>

        </div>
    </div>


    <div class="col-sm-6 col-xl-3">
        <div class="stat-card animate-fade-in-up">

            <div class="stat-card-icon info">
                <i class="bi bi-egg-fried"></i>
            </div>

            <p class="stat-card-label">
                Total Foods
            </p>

            <h3 class="stat-card-value">
                {{ number_format($stats['total_foods'] ?? 0) }}
            </h3>

        </div>
    </div>


    <div class="col-sm-6 col-xl-3">
        <div class="stat-card animate-fade-in-up">

            <div class="stat-card-icon warning">
                <i class="bi bi-receipt"></i>
            </div>

            <p class="stat-card-label">
                Total Orders
            </p>

            <h3 class="stat-card-value">
                {{ number_format($stats['total_orders'] ?? 0) }}
            </h3>

        </div>
    </div>


    <div class="col-sm-6 col-xl-3">
        <div class="stat-card animate-fade-in-up">

            <div class="stat-card-icon danger">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <p class="stat-card-label">
                Pending Orders
            </p>

            <h3 class="stat-card-value">
                {{ number_format($stats['pending_orders'] ?? 0) }}
            </h3>

        </div>
    </div>

</div>


<!-- =========================================================
     STATS CARDS ROW 2
========================================================= -->

<div class="row g-3 mb-4">

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-gradient success animate-fade-in-up">

            <div class="stat-card-icon">
                <i class="bi bi-check-circle"></i>
            </div>

            <p class="stat-card-label">
                Completed Orders
            </p>

            <h3 class="stat-card-value">
                {{ number_format($stats['completed_orders'] ?? 0) }}
            </h3>

        </div>
    </div>


    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-gradient primary animate-fade-in-up">

            <div class="stat-card-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>

            <p class="stat-card-label">
                Today's Revenue
            </p>

            <h3 class="stat-card-value">
                ${{ number_format($stats['today_revenue'] ?? 0, 2) }}
            </h3>

        </div>
    </div>


    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-gradient warning animate-fade-in-up">

            <div class="stat-card-icon">
                <i class="bi bi-graph-up"></i>
            </div>

            <p class="stat-card-label">
                Monthly Revenue
            </p>

            <h3 class="stat-card-value">
                ${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}
            </h3>

        </div>
    </div>


    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-gradient {{ ($stats['net_profit'] ?? 0) >= 0 ? 'success' : 'danger' }} animate-fade-in-up">

            <div class="stat-card-icon">
                <i class="bi bi-wallet2"></i>
            </div>

            <p class="stat-card-label">
                Net Profit
            </p>

            <h3 class="stat-card-value {{ ($stats['net_profit'] ?? 0) < 0 ? 'text-danger' : '' }}">
                ${{ number_format($stats['net_profit'] ?? 0, 2) }}
            </h3>

        </div>
    </div>

</div>


<!-- =========================================================
     CHARTS
========================================================= -->

<div class="row g-3 mb-4">

    <div class="col-lg-8">

        <div class="card">

            <div class="card-header">
                <h6 class="card-title">
                    Sales Overview
                </h6>
            </div>

            <div class="card-body">

                <div
                    class="chart-container"
                    style="height: 300px;">

                    <canvas id="salesChart"></canvas>

                </div>

            </div>

        </div>

    </div>


    <div class="col-lg-4">

        <div class="card">

            <div class="card-header">
                <h6 class="card-title">
                    Order Status
                </h6>
            </div>

            <div class="card-body">

                <div
                    class="chart-container"
                    style="height: 300px;">

                    <canvas id="orderStatusChart"></canvas>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     INCOME VS EXPENSES
========================================================= -->

<div class="row g-3 mb-4">

    <div class="col-12">

        <div class="card">

            <div class="card-header">
                <h6 class="card-title">
                    Income vs Expenses
                </h6>
            </div>

            <div class="card-body">

                <div
                    class="chart-container"
                    style="height: 250px;">

                    <canvas id="incomeExpenseChart"></canvas>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     RECENT ORDERS
========================================================= -->

<div class="data-card">

    <div class="data-card-header d-flex justify-content-between align-items-center">

        <h6 class="data-card-title">
            Recent Orders
        </h6>

        <a
            href="{{ route('admin.orders.index') }}"
            class="btn btn-sm btn-outline-primary">

            View All
            <i class="bi bi-arrow-right ms-1"></i>

        </a>

    </div>


    <div class="data-card-body">

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>

                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($recentOrders ?? [] as $order)

                        <tr>

                            <td class="fw-semibold">
                                #{{ $order->order_number ?? 'N/A' }}
                            </td>


                            <td>

                                <div class="d-flex align-items-center gap-2">

                                    <div class="avatar avatar-sm avatar-primary">
                                        {{ substr($order->user->name ?? 'N', 0, 1) }}
                                    </div>

                                    {{ $order->user->name ?? 'N/A' }}

                                </div>

                            </td>


                            <td class="fw-semibold">
                                ${{ number_format($order->total_amount ?? 0, 2) }}
                            </td>


                            <td>

                                <span
                                    class="badge bg-{{ ($order->payment_status ?? '') == 'paid' ? 'success' : 'warning' }}">

                                    {{ ucfirst($order->payment_status ?? 'pending') }}

                                </span>

                            </td>


                            <td>

                                @php

                                    $statusBadge = match($order->order_status) {

                                        'pending' => 'warning',

                                        'confirmed' => 'primary',

                                        'preparing' => 'info',

                                        'ready' => 'secondary',

                                        'out_for_delivery' => 'orange',

                                        'delivered' => 'success',

                                        default => 'danger',

                                    };

                                @endphp


                                <span class="badge bg-{{ $statusBadge }}">

                                    {{ ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $order->order_status ?? 'pending'
                                        )
                                    ) }}

                                </span>

                            </td>


                            <td class="text-muted">

                                {{
                                    $order->created_at
                                    ? $order->created_at->format('M d, Y')
                                    : 'N/A'
                                }}

                            </td>


                            <td>

                                <a
                                    href="{{ route('admin.orders.show', $order) }}"
                                    class="btn btn-sm btn-outline-primary btn-icon">

                                    <i class="bi bi-eye"></i>

                                </a>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="7">

                                <div class="empty-state">

                                    <div class="empty-state-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>

                                    <p class="empty-state-title">
                                        No orders yet
                                    </p>

                                    <p class="empty-state-text">
                                        Orders will appear here once customers start placing them.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<!-- =========================================================
     CHART.JS
========================================================= -->

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       SALES CHART
    ========================================================= */

    const salesCtx =
        document.getElementById('salesChart').getContext('2d');


    new Chart(salesCtx, {

        type: 'line',

        data: {

            labels: @json($salesLabels ?? []),

            datasets: [{

                label: 'Revenue',

                data: @json($salesData ?? []),

                /* GREEN THEME */
                borderColor: '#16a34a',

                backgroundColor: 'rgba(22, 163, 74, 0.08)',

                fill: true,

                tension: 0.4,

                pointBackgroundColor: '#16a34a',

                pointBorderColor: '#ffffff',

                pointBorderWidth: 2,

                pointRadius: 4,

                pointHoverRadius: 6

            }]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    display: false
                },

                tooltip: {

                    backgroundColor: '#17211b',

                    padding: 12,

                    cornerRadius: 8,

                    callbacks: {

                        label: function (context) {

                            return '$' +
                                context.parsed.y.toFixed(2);

                        }

                    }

                }

            },


            scales: {

                y: {

                    beginAtZero: true,

                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },

                    ticks: {

                        callback: function (value) {

                            return '$' + value;

                        }

                    }

                },


                x: {

                    grid: {
                        display: false
                    }

                }

            }

        }

    });


    /* =========================================================
       ORDER STATUS CHART
    ========================================================= */

    const statusCtx =
        document
            .getElementById('orderStatusChart')
            .getContext('2d');


    @php

        $chartColors = array_values(
            $statusColors ??
            [
                '#f59e0b',
                '#16a34a',
                '#22c55e',
                '#86efac',
                '#15803d',
                '#4ade80',
                '#ef4444'
            ]
        );

    @endphp


    new Chart(statusCtx, {

        type: 'doughnut',

        data: {

            labels: @json($statusLabels ?? []),

            datasets: [{

                data: @json($statusData ?? []),

                backgroundColor: @json($chartColors),

                borderWidth: 0,

                hoverOffset: 8

            }]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,

            cutout: '70%',

            plugins: {

                legend: {

                    position: 'bottom',

                    labels: {

                        padding: 20,

                        usePointStyle: true,

                        pointStyle: 'circle'

                    }

                }

            }

        }

    });


    /* =========================================================
       INCOME VS EXPENSES CHART
    ========================================================= */

    const ieCtx =
        document
            .getElementById('incomeExpenseChart')
            .getContext('2d');


    new Chart(ieCtx, {

        type: 'bar',

        data: {

            labels: @json($ieLabels ?? []),

            datasets: [

                {

                    label: 'Income',

                    data: @json($incomeData ?? []),

                    backgroundColor: '#16a34a',

                    borderRadius: 6,

                    borderSkipped: false

                },


                {

                    label: 'Expenses',

                    data: @json($expenseData ?? []),

                    backgroundColor: '#ef4444',

                    borderRadius: 6,

                    borderSkipped: false

                }

            ]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: 'top',

                    labels: {

                        usePointStyle: true,

                        pointStyle: 'circle',

                        padding: 20

                    }

                },


                tooltip: {

                    backgroundColor: '#17211b',

                    padding: 12,

                    cornerRadius: 8,

                    callbacks: {

                        label: function (context) {

                            return context.dataset.label +
                                ': $' +
                                context.parsed.y.toFixed(2);

                        }

                    }

                }

            },


            scales: {

                y: {

                    beginAtZero: true,

                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },

                    ticks: {

                        callback: function (value) {

                            return '$' + value;

                        }

                    }

                },


                x: {

                    grid: {
                        display: false
                    }

                }

            }

        }

    });

});


/* =========================================================
   DATE FILTER
========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    const presetButtons =
        document.querySelectorAll('.date-preset');

    const startDateInput =
        document.getElementById('startDate');

    const endDateInput =
        document.getElementById('endDate');


    function toLocalYMD(date) {

        const y =
            date.getFullYear();

        const m =
            String(date.getMonth() + 1).padStart(2, '0');

        const d =
            String(date.getDate()).padStart(2, '0');


        return y + '-' + m + '-' + d;

    }


    presetButtons.forEach(function (btn) {

        btn.addEventListener('click', function () {

            const range =
                btn.getAttribute('data-range');


            const today =
                new Date();


            let start =
                new Date(today);

            let end =
                new Date(today);


            if (range === 'today') {

                start = new Date(
                    today.getFullYear(),
                    today.getMonth(),
                    today.getDate()
                );

                end = new Date(
                    today.getFullYear(),
                    today.getMonth(),
                    today.getDate()
                );

            }

            else if (range === 'week') {

                const day =
                    today.getDay() || 7;


                start = new Date(
                    today.getFullYear(),
                    today.getMonth(),
                    today.getDate() - day + 1
                );


                end = new Date(
                    today.getFullYear(),
                    today.getMonth(),
                    today.getDate() + (7 - day)
                );

            }

            else if (range === 'month') {

                start = new Date(
                    today.getFullYear(),
                    today.getMonth(),
                    1
                );


                end = new Date(
                    today.getFullYear(),
                    today.getMonth() + 1,
                    0
                );

            }

            else if (range === 'year') {

                start = new Date(
                    today.getFullYear(),
                    0,
                    1
                );


                end = new Date(
                    today.getFullYear(),
                    11,
                    31
                );

            }


            startDateInput.value =
                toLocalYMD(start);

            endDateInput.value =
                toLocalYMD(end);


            document
                .getElementById('dateFilterForm')
                .submit();

        });

    });

});

</script>

@endsection
