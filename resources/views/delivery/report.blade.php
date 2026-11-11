@extends('layouts.delivery')

@section('title', 'Delivery History')
@section('topbar_title', 'Delivery History')
@section('topbar_subtitle', 'View your completed deliveries')

@section('content')
<div class="delivery-card mb-4">
    <div class="delivery-card-body">
        <form action="{{ route('delivery.report') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Start Date</label>
                <input type="date" class="form-control" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">End Date</label>
                <input type="date" class="form-control" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select class="form-select" name="status_filter">
                    <option value="">All Statuses</option>
                    <option value="assigned" {{ request('status_filter') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="accepted" {{ request('status_filter') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="picked_up" {{ request('status_filter') == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                    <option value="out_for_delivery" {{ request('status_filter') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                    <option value="delivered" {{ request('status_filter') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="delivery-btn delivery-btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="delivery-stats">
    <div class="row g-3">
        <div class="col-6 col-lg-3">
            <div class="delivery-stat-card">
                <span class="delivery-stat-icon"><i class="bi bi-truck"></i></span>
                <div>
                    <h3 class="delivery-stat-value">{{ number_format($report_data['total_deliveries'] ?? 0) }}</h3>
                    <p class="delivery-stat-label">Total Deliveries</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="delivery-stat-card">
                <span class="delivery-stat-icon"><i class="bi bi-check-circle"></i></span>
                <div>
                    <h3 class="delivery-stat-value">{{ number_format($report_data['completed'] ?? 0) }}</h3>
                    <p class="delivery-stat-label">Completed</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="delivery-stat-card">
                <span class="delivery-stat-icon"><i class="bi bi-hourglass-split"></i></span>
                <div>
                    <h3 class="delivery-stat-value">{{ number_format($report_data['pending'] ?? 0) }}</h3>
                    <p class="delivery-stat-label">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="delivery-stat-card">
                <span class="delivery-stat-icon"><i class="bi bi-cash-stack"></i></span>
                <div>
                    <h3 class="delivery-stat-value">${{ number_format($report_data['earnings'] ?? 0, 2) }}</h3>
                    <p class="delivery-stat-label">Total Earnings</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="delivery-section">
    <div class="delivery-section-header">
        <div>
            <h2 class="delivery-section-title mb-0">Delivery History</h2>
            <p class="delivery-section-subtitle mb-0">{{ $reportDeliveries->count() }} deliveries in this period</p>
        </div>
    </div>

    <div class="delivery-table-wrap">
        <table class="delivery-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportDeliveries ?? [] as $delivery)
                    @php
                        $order = $delivery->order;
                        $customer = $order->user ?? null;
                        $address = $order->address ?? null;
                        $statusBadge = match($delivery->status) {
                            'assigned' => 'pending',
                            'accepted' => 'preparing',
                            'picked_up' => 'ready',
                            'out_for_delivery' => 'out',
                            'delivered' => 'delivered',
                            default => 'pending',
                        };
                    @endphp
                    <tr class="delivery-table-row" data-href="{{ route('delivery.orders.show', $order) }}">
                        <td class="fw-semibold" data-label="Order">#{{ $order->order_number ?? 'N/A' }}</td>
                        <td data-label="Customer">
                            <div class="delivery-table-user">
                                <span class="delivery-table-avatar">{{ $customer ? substr($customer->name, 0, 1) : '?' }}</span>
                                <div>
                                    <div class="fw-semibold">{{ $customer->name ?? 'Unknown' }}</div>
                                    <small class="text-muted">{{ $customer->phone ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td data-label="Address"><small class="text-muted">{{ $address->address ?? 'N/A' }}</small></td>
                        <td data-label="Status">
                            <span class="delivery-badge delivery-badge-{{ $statusBadge }}">{{ ucfirst(str_replace('_', ' ', $delivery->status)) }}</span>
                        </td>
                        <td data-label="Date" class="text-muted">{{ $delivery->created_at ? $delivery->created_at->format('M d, Y') : 'N/A' }}</td>
                        <td data-label="Amount" class="text-end fw-semibold">${{ number_format($order->total_amount ?? 0, 2) }}</td>
                        <td data-label="Action">
                            <a href="{{ route('delivery.orders.show', $order) }}" class="delivery-btn delivery-btn-ghost delivery-btn-sm">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="delivery-empty">
                                <i class="bi bi-inbox"></i>
                                <p>No deliveries found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
