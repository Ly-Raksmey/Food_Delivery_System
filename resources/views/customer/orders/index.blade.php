@extends('layouts.customer')

@section('title', 'My Orders')

@section('content')
<section class="customer-section">
    <div class="container">
        <div class="page-header">
            <div>
                <h1 class="page-title">My Orders</h1>
                <p class="page-subtitle">Track and manage your orders</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card mb-4">
            <form action="{{ route('orders.index') }}" method="GET" class="row g-2">
                <div class="col-md-4">
                    <select class="form-select" name="status" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </form>
        </div>

        @if($orders->count() > 0)
            <div class="data-card">
                <div class="data-card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="fw-semibold">#{{ $order->order_number }}</td>
                                        <td class="text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="text-muted">{{ $order->items->count() }} item(s)</td>
                                        <td class="fw-semibold">${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'info',
                                                    'preparing' => 'primary',
                                                    'ready' => 'secondary',
                                                    'out_for_delivery' => 'orange',
                                                    'delivered' => 'success',
                                                    'cancelled' => 'danger',
                                                ];
                                                $badgeClass = $statusColors[$order->order_status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }}">
                                                {{ ucwords(str_replace('_', ' ', $order->order_status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('orders.track', $order) }}" class="btn btn-outline-info" title="Track">
                                                    <i class="bi bi-truck"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-success" title="Receipt" data-receipt-url="{{ route('orders.receipt', $order) }}" data-bs-toggle="modal" data-bs-target="#receiptModal">
                                                    <i class="bi bi-receipt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if(method_exists($orders, 'links'))
                <div class="mt-4 d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="bi bi-receipt empty-state-icon"></i>
                <p class="empty-state-title">No orders yet</p>
                <p class="empty-state-text">Start ordering delicious food!</p>
                <a href="{{ route('foods.index') }}" class="btn btn-primary mt-3">Browse Foods</a>
            </div>
        @endif
    </div>
</section>
@endsection
