@extends('layouts.admin')

@section('title', 'Delivery Details')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Delivery Details</h1>
        <p class="page-subtitle">#{{ $delivery->order->order_number ?? 'N/A' }}</p>
    </div>
    <a href="{{ route('admin.deliveries.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Back to Deliveries
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order #{{ $delivery->order->order_number ?? 'N/A' }}</h5>
                <span class="badge bg-{{ $delivery->status == 'assigned' ? 'warning' : ($delivery->status == 'picked_up' ? 'info' : ($delivery->status == 'out_for_delivery' ? 'orange' : ($delivery->status == 'delivered' ? 'success' : 'secondary'))) }}">
                    {{ ucfirst(str_replace('_', ' ', $delivery->status ?? 'pending')) }}
                </span>
            </div>
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Food</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($delivery->order->items ?? [] as $item)
                            <tr>
                                <td>{{ $item->food->name ?? 'N/A' }}</td>
                                <td>{{ $item->quantity ?? 0 }}</td>
                                <td>${{ number_format($item->price ?? 0, 2) }}</td>
                                <td>${{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No items found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-end">
                    <strong>Total: ${{ number_format($delivery->order->total_amount ?? 0, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Delivery Info</h6>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $delivery->status ?? 'pending')) }}</p>
                <p class="mb-1"><strong>Assigned At:</strong> {{ $delivery->assigned_at ? \Carbon\Carbon::parse($delivery->assigned_at)->format('M d, Y H:i') : 'N/A' }}</p>
                <p class="mb-1"><strong>Picked Up At:</strong> {{ $delivery->picked_up_at ? \Carbon\Carbon::parse($delivery->picked_up_at)->format('M d, Y H:i') : 'N/A' }}</p>
                <p class="mb-0"><strong>Delivered At:</strong> {{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->format('M d, Y H:i') : 'N/A' }}</p>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Delivery Staff</h6>
            </div>
            <div class="card-body">
                @if($delivery->deliveryStaff)
                    <p class="mb-1"><strong>Name:</strong> {{ $delivery->deliveryStaff->name ?? 'Unassigned' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $delivery->deliveryStaff->email ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Phone:</strong> {{ $delivery->deliveryStaff->phone ?? 'N/A' }}</p>
                @else
                    <p class="text-muted mb-0">Unassigned</p>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Customer Info</h6>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Name:</strong> {{ $delivery->order->user->name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $delivery->order->user->email ?? 'N/A' }}</p>
                <p class="mb-0"><strong>Phone:</strong> {{ $delivery->order->user->phone ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Delivery Address</h6>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Address:</strong> {{ $delivery->order->address->address ?? 'N/A' }}</p>
                <p class="mb-0"><strong>Phone:</strong> {{ $delivery->order->address->phone ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
