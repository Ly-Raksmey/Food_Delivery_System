@extends('layouts.admin')

@section('title', 'Customer Detail')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Customer Detail</h1>
        <p class="page-subtitle">View customer information and order history</p>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <h4>Customer Detail</h4>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Customers
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Customer Information</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($customer->profile_image)
                        <img src="{{ asset('storage/' . $customer->profile_image) }}" alt="{{ $customer->name }}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto badge bg-primary" style="width: 80px; height: 80px; font-size: 24px;">
                            <i class="bi bi-person"></i>
                        </div>
                    @endif
                    <h5 class="fw-semibold mt-2 mb-0">{{ $customer->name }}</h5>
                    <p class="text-muted small mb-0">{{ ucfirst($customer->role) }}</p>
                </div>
                <hr>
                <div class="row g-2">
                    <div class="col-12">
                        <p class="mb-1 text-muted small">Email</p>
                        <p class="fw-semibold mb-0">{{ $customer->email }}</p>
                    </div>
                    <div class="col-12">
                        <p class="mb-1 text-muted small">Phone</p>
                        <p class="fw-semibold mb-0">{{ $customer->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-12">
                        <p class="mb-1 text-muted small">Address</p>
                        <p class="fw-semibold mb-0">{{ $customer->address ?? 'N/A' }}</p>
                    </div>
                    <div class="col-12">
                        <p class="mb-1 text-muted small">Status</p>
                        <span class="badge bg-{{ $customer->status == 'active' ? 'success' : 'warning' }}">
                            {{ ucfirst($customer->status ?? 'inactive') }}
                        </span>
                    </div>
                    <div class="col-12">
                        <p class="mb-1 text-muted small">Joined</p>
                        <p class="fw-semibold mb-0">{{ $customer->created_at ? $customer->created_at->format('d M Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($customer->addresses->count() > 0)
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Saved Addresses</h6>
            </div>
            <div class="card-body">
                @foreach($customer->addresses as $address)
                <div class="card mb-2 border">
                    <div class="card-body py-2">
                        <p class="fw-semibold mb-1 small">{{ $address->address }}</p>
                        <p class="text-muted small mb-0">{{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}</p>
                        <p class="text-muted small mb-0">Phone: {{ $address->phone }}</p>
                        @if($address->is_default)
                            <span class="badge bg-primary mt-1">Default</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Order History</h6>
                <span class="badge bg-primary">{{ $customer->orders->count() }} orders</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->orders->take(20) ?? [] as $order)
                            <tr>
                                <td class="fw-semibold">#{{ $order->order_number ?? 'N/A' }}</td>
                                <td>{{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</td>
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
                                        {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td>${{ number_format($order->total_amount ?? 0, 2) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No orders found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
