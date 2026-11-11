@extends('layouts.customer')

@section('title', 'Notification Detail')

@section('content')
<section class="customer-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Notification Detail</h5>
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="notification-detail-icon {{ $notification->type == 'order' ? 'primary' : ($notification->type == 'delivery' ? 'info' : ($notification->type == 'payment' ? 'success' : 'secondary')) }}">
                                    <i class="bi bi-{{ $notification->type == 'order' ? 'receipt' : ($notification->type == 'delivery' ? 'truck' : ($notification->type == 'payment' ? 'credit-card' : 'bell')) }}"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="fw-bold mb-1">{{ $notification->title }}</h4>
                                <p class="mb-2 text-muted">{{ $notification->message }}</p>
                                <div class="d-flex gap-3 flex-wrap">
                                    <span class="badge bg-light text-dark border">
                                        {{ ucfirst($notification->type ?? 'general') }}
                                    </span>
                                    <small class="text-muted">{{ $notification->created_at->format('d M Y, h:i A') }}</small>
                                    @if($notification->read_at)
                                        <small class="text-success"><i class="bi bi-check2-all me-1"></i>Read {{ $notification->read_at->diffForHumans() }}</small>
                                    @else
                                        <small class="text-warning"><i class="bi bi-circle me-1"></i>Unread</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($relatedOrder)
                        <hr>
                        <div class="mt-4">
                            <h6 class="fw-semibold mb-3"><i class="bi bi-receipt me-2 text-primary"></i>Related Order</h6>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-muted small">Order Number</p>
                                            <p class="fw-semibold mb-0">#{{ $relatedOrder->order_number }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-muted small">Order Status</p>
                                            @php
                                                $statusBadge = match($relatedOrder->order_status) {
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
                                                {{ ucfirst(str_replace('_', ' ', $relatedOrder->order_status)) }}
                                            </span>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-muted small">Payment Status</p>
                                            <span class="badge bg-{{ $relatedOrder->payment_status == 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($relatedOrder->payment_status) }}
                                            </span>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-muted small">Total Amount</p>
                                            <p class="fw-bold mb-0 text-primary">${{ number_format($relatedOrder->total_amount ?? 0, 2) }}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-1 text-muted small">Order Date</p>
                                            <p class="mb-0">{{ $relatedOrder->created_at ? $relatedOrder->created_at->format('d M Y, h:i A') : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('orders.show', $relatedOrder) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i> View Full Order Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($relatedFood)
                        <hr>
                        <div class="mt-4">
                            <h6 class="fw-semibold mb-3"><i class="bi bi-chat-square-text me-2 text-primary"></i>Related Review</h6>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-muted small">Food</p>
                                            <p class="fw-semibold mb-0">{{ $relatedFood->name }}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-1 text-muted small">{{ $notification->message }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('foods.show', $relatedFood) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i> View Food & Reply
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($relatedDelivery)
                        <hr>
                        <div class="mt-4">
                            <h6 class="fw-semibold mb-3"><i class="bi bi-truck me-2 text-primary"></i>Delivery Information</h6>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-muted small">Delivery Status</p>
                                            <p class="fw-semibold mb-0">{{ ucfirst(str_replace('_', ' ', $relatedDelivery->status ?? 'pending')) }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-muted small">Delivery Staff</p>
                                            <p class="fw-semibold mb-0">{{ $relatedDelivery->deliveryStaff->name ?? 'Unassigned' }}</p>
                                        </div>
                                        @if($relatedDelivery->assigned_at)
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-muted small">Assigned At</p>
                                            <p class="mb-0">{{ \Carbon\Carbon::parse($relatedDelivery->assigned_at)->format('d M Y, h:i A') }}</p>
                                        </div>
                                        @endif
                                        @if($relatedDelivery->delivered_at)
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-muted small">Delivered At</p>
                                            <p class="mb-0">{{ \Carbon\Carbon::parse($relatedDelivery->delivered_at)->format('d M Y, h:i A') }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!$relatedOrder && !$relatedDelivery)
                        <hr>
                        <div class="mt-4 text-center py-4">
                            <i class="bi bi-info-circle fa-2x mb-2 d-block text-muted"></i>
                            <p class="text-muted mb-0">No additional details available for this notification.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
