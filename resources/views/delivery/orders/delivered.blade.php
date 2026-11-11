@extends('layouts.delivery')

@section('title', 'Order Delivered')

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="delivery-card">
            <div class="delivery-card-body py-5 text-center">
                <div class="mb-4">
                    <div class="delivery-stat-icon mx-auto" style="width: 80px; height: 80px; border-radius: 50%; font-size: 2.5rem;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-3" style="font-family: 'Fraunces', serif; color: #1E4326;">Order Delivered!</h2>
                <p class="text-muted mb-4">Order #{{ $order->order_number }} has been marked as delivered. You can view the receipt below.</p>
                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <button type="button" class="delivery-btn delivery-btn-primary" data-receipt-url="{{ route('delivery.orders.receipt', $order) }}" data-bs-toggle="modal" data-bs-target="#receiptModal">
                        <i class="bi bi-receipt me-1"></i> View Receipt
                    </button>
                    <a href="{{ route('delivery.orders.index') }}" class="delivery-btn delivery-btn-ghost">
                        <i class="bi bi-list me-1"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
