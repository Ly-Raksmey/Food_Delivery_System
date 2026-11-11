@extends('layouts.customer')

@section('title', 'Order #' . $order->order_number)

@section('content')
<section class="customer-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">#{{ $order->order_number }}</li>
            </ol>
        </nav>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Order Items</h5>
                        @foreach($order->items as $item)
                            <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                                <img src="{{ $item->food->image ? asset('storage/' . $item->food->image) : asset('images/placeholder-food.jpg') }}" alt="{{ $item->food->name }}" class="img-thumb-md">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold">{{ $item->food->name }}</h6>
                                    <p class="mb-0 text-muted small">Qty: {{ $item->quantity }} x ${{ number_format($item->price, 2) }}</p>
                                </div>
                                <span class="fw-bold">${{ number_format($item->quantity * $item->price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><i class="bi bi-truck me-2 text-primary"></i>Delivery Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Address:</strong></p>
                                <p class="text-muted">{{ $order->address->address ?? 'N/A' }}</p>
                                <p class="text-muted">{{ $order->address->city ?? '' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Phone:</strong></p>
                                <p class="text-muted">{{ $order->address->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Order Status -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Order Status</h5>
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
                        <span class="badge bg-{{ $badgeClass }} fs-6 mb-3">
                            {{ ucwords(str_replace('_', ' ', $order->order_status)) }}
                        </span>
                        <a href="{{ route('orders.track', $order) }}" class="btn btn-outline-primary w-100 mb-2">Track Order</a>
                        <button type="button" class="btn btn-outline-success w-100" data-receipt-url="{{ route('orders.receipt', $order) }}" data-bs-toggle="modal" data-bs-target="#receiptModal">
                            <i class="bi bi-receipt me-1"></i> View Receipt
                        </button>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Payment Information</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Method</span>
                            <span class="fw-semibold text-capitalize">{{ $order->payment_method ?? 'Cash' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Delivery</span>
                            <span>${{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount</span>
                                <span>-${{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-primary">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Order Details</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Order Number</span>
                            <span class="fw-semibold">#{{ $order->order_number }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Date</span>
                            <span class="fw-semibold">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
