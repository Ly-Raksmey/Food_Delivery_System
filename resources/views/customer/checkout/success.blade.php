@extends('layouts.customer')

@section('title', 'Order Placed')

@section('content')
<section class="customer-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="card">
                    <div class="card-body py-5">
                        <div class="mb-4">
                            <div class="success-icon">
                                <i class="bi bi-check-lg"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-3">Order Placed Successfully!</h2>
                        <p class="text-muted mb-4">Your order #{{ $order->order_number }} has been placed. You can view your receipt below.</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-primary" data-receipt-url="{{ route('orders.receipt', $order) }}" data-bs-toggle="modal" data-bs-target="#receiptModal">
                                <i class="bi bi-receipt me-1"></i> View Receipt
                            </button>
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-list me-1"></i> View My Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
