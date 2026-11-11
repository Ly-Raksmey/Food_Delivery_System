@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Reports</h1>
        <p class="page-subtitle">Generate and view detailed business reports</p>
    </div>
</div>

<div class="filter-card">
    <form action="{{ route('admin.reports.index') }}" method="GET" id="reportFilterForm" class="row g-3 align-items-end">
        <div class="col-md-2">
            <label class="form-label">Period</label>
            <select class="form-select" name="period" id="reportPeriod" onchange="updateReportDates()">
                <option value="daily" {{ request('period') == 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ request('period') == 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Time Filter</label>
            <select class="form-select" name="time_filter" id="timeFilter">
                <option value="">All Day</option>
                <option value="morning" {{ request('time_filter') == 'morning' ? 'selected' : '' }}>Morning (00:00 - 11:59)</option>
                <option value="evening" {{ request('time_filter') == 'evening' ? 'selected' : '' }}>Evening (12:00 - 17:59)</option>
                <option value="night" {{ request('time_filter') == 'night' ? 'selected' : '' }}>Night (18:00 - 23:59)</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Start Date</label>
            <input type="date" class="form-control" name="start_date" id="reportStartDate" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">End Date</label>
            <input type="date" class="form-control" name="end_date" id="reportEndDate" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">Order Status</label>
            <select class="form-select" name="order_status">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('order_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('order_status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="preparing" {{ request('order_status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                <option value="ready" {{ request('order_status') == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="out_for_delivery" {{ request('order_status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                <option value="delivered" {{ request('order_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Payment Method</label>
            <select class="form-select" name="payment_method">
                <option value="">All Methods</option>
                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash on Delivery</option>
                <option value="khqr" {{ request('payment_method') == 'khqr' ? 'selected' : '' }}>KHQR</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Payment Status</label>
            <select class="form-select" name="payment_status">
                <option value="">All</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Customer</label>
            <select class="form-select" name="customer_id">
                <option value="">All Customers</option>
                @foreach($customers ?? [] as $customer)
                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-funnel"></i> Generate
            </button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.reports.export', request()->all()) }}" class="btn btn-outline-primary w-100">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.reports.print', request()->all()) }}" class="btn btn-outline-secondary w-100" target="_blank">
                <i class="bi bi-printer"></i> Print
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
            </a>
        </div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="bi bi-receipt"></i>
                </div>
            <p class="stat-card-label">Total Orders</p>
            <h3 class="stat-card-value">{{ number_format($report_data['total_orders'] ?? 0) }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            <p class="stat-card-label">Total Revenue</p>
            <h3 class="stat-card-value">${{ number_format($report_data['total_revenue'] ?? 0, 2) }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
            <p class="stat-card-label">Total Expenses</p>
            <h3 class="stat-card-value">${{ number_format($report_data['total_expenses'] ?? 0, 2) }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
            <p class="stat-card-label">Net Profit</p>
            <h3 class="stat-card-value {{ ($report_data['net_profit'] ?? 0) < 0 ? 'text-danger' : '' }}">${{ number_format($report_data['net_profit'] ?? 0, 2) }}</h3>
        </div>
    </div>
</div>

@if(isset($report_data['topFoods']) && $report_data['topFoods']->count() > 0)
<div class="data-card mb-4">
    <div class="data-card-header">
        <h6 class="data-card-title">Top Selling Foods</h6>
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Food</th>
                        <th class="text-center">Quantity Sold</th>
                        <th class="text-end">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report_data['topFoods'] as $food)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($food->food && $food->food->image)
                                    <img src="{{ asset('storage/' . $food->food->image) }}" alt="{{ $food->food->name }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                                <span class="fw-semibold">{{ $food->food->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="text-center">{{ number_format($food->total_qty ?? 0) }}</td>
                        <td class="text-end fw-semibold">${{ number_format($food->total_amount ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if(isset($report_data['details']) && count($report_data['details']) > 0)
<div class="data-card mb-4">
    <div class="data-card-header">
        <h6 class="data-card-title">Sales by {{ ucfirst($period ?? 'period') }}</h6>
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th class="text-center">Orders</th>
                        <th class="text-end">Revenue</th>
                        <th class="text-end">Expenses</th>
                        <th class="text-end">Profit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report_data['details'] as $detail)
                    <tr>
                        <td class="fw-semibold">{{ $detail['date'] }}</td>
                        <td class="text-center">{{ number_format($detail['orders']) }}</td>
                        <td class="text-end">${{ number_format($detail['revenue'], 2) }}</td>
                        <td class="text-end">${{ number_format($detail['expenses'], 2) }}</td>
                        <td class="text-end fw-semibold {{ $detail['profit'] >= 0 ? 'text-success' : 'text-danger' }}">${{ number_format($detail['profit'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="data-card">
    <div class="data-card-header">
        <h6 class="data-card-title">Orders in Selected Period</h6>
        <span class="badge bg-primary">{{ $filteredOrders->count() }} orders</span>
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Items</th>
                        <th class="text-end">Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($filteredOrders ?? [] as $order)
                    <tr>
                        <td class="fw-semibold">#{{ $order->order_number ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm avatar-primary">{{ substr($order->user->name ?? 'N', 0, 1) }}</div>
                                {{ $order->user->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="text-muted">{{ $order->created_at ? $order->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ ($order->payment_status ?? '') == 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->order_status == 'pending' ? 'warning' : ($order->order_status == 'confirmed' ? 'primary' : ($order->order_status == 'preparing' ? 'info' : ($order->order_status == 'ready' ? 'secondary' : ($order->order_status == 'out_for_delivery' ? 'orange' : ($order->order_status == 'delivered' ? 'success' : 'danger'))))) }}">
                                {{ ucfirst(str_replace('_', ' ', $order->order_status ?? 'pending')) }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $order->items->count() ?? 0 }}</td>
                        <td class="text-end fw-semibold">${{ number_format($order->total_amount ?? 0, 2) }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="bi bi-inbox empty-state-icon"></i>
                                <p class="empty-state-title">No orders found</p>
                                <p class="empty-state-text">No orders match the selected date range.</p>
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
<script>
function toLocalYMD(date) {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    return y + '-' + m + '-' + d;
}

function updateReportDates() {
    const period = document.getElementById('reportPeriod').value;
    const startDateInput = document.getElementById('reportStartDate');
    const endDateInput = document.getElementById('reportEndDate');
    const today = new Date();

    if (period === 'daily') {
        const dateStr = toLocalYMD(today);
        startDateInput.value = dateStr;
        endDateInput.value = dateStr;
    } else if (period === 'monthly') {
        const start = new Date(today.getFullYear(), today.getMonth(), 1);
        const end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        startDateInput.value = toLocalYMD(start);
        endDateInput.value = toLocalYMD(end);
    } else if (period === 'yearly') {
        const start = new Date(today.getFullYear(), 0, 1);
        const end = new Date(today.getFullYear(), 11, 31);
        startDateInput.value = toLocalYMD(start);
        endDateInput.value = toLocalYMD(end);
    }

    document.getElementById('reportFilterForm').submit();
}

document.addEventListener('DOMContentLoaded', function() {
    const period = document.getElementById('reportPeriod').value;
    if (!document.getElementById('reportStartDate').value || !document.getElementById('reportEndDate').value) {
        updateReportDates();
    }
});
</script>
@endsection
