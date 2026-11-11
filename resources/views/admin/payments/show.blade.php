@extends('layouts.admin')

@section('title', 'Payment Detail')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Payment Detail</h1>
        <p class="page-subtitle">#{{ $payment->order->order_number ?? 'N/A' }}</p>
    </div>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Payments
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Payment Information</h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12">
                        <p class="mb-1 text-muted small">Order Number</p>
                        <p class="fw-semibold mb-0">#{{ $payment->order->order_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-12">
                        <p class="mb-1 text-muted small">Payment Method</p>
                        <p class="fw-semibold mb-0 text-capitalize">{{ str_replace('_', ' ', $payment->payment_method ?? 'N/A') }}</p>
                    </div>
                    <div class="col-12">
                        <p class="mb-1 text-muted small">Amount</p>
                        <p class="fw-bold mb-0 text-success" style="font-size: 1.1rem;">${{ number_format($payment->amount ?? 0, 2) }}</p>
                    </div>
                    <div class="col-12">
                        <p class="mb-1 text-muted small">Status</p>
                        <span class="badge bg-{{ $payment->status == 'paid' ? 'success' : ($payment->status == 'failed' ? 'danger' : 'warning') }}">
                            {{ ucfirst($payment->status ?? 'pending') }}
                        </span>
                    </div>
                    <div class="col-12">
                        <p class="mb-1 text-muted small">Reference</p>
                        <p class="fw-semibold mb-0">{{ $payment->transaction_reference ?? 'N/A' }}</p>
                    </div>
                    @if($payment->paid_at)
                    <div class="col-12">
                        <p class="mb-1 text-muted small">Paid At</p>
                        <p class="fw-semibold mb-0">{{ $payment->paid_at->format('d M Y, h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Customer Information</h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted small">Name</p>
                        <p class="fw-semibold mb-0">{{ $payment->order->user->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted small">Email</p>
                        <p class="fw-semibold mb-0">{{ $payment->order->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted small">Phone</p>
                        <p class="fw-semibold mb-0">{{ $payment->order->user->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Order Items</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Item</th>
                                <th>Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end pe-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payment->order->items ?? [] as $item)
                            <tr>
                                <td class="ps-3">{{ $item->food->name ?? 'Unknown Item' }}</td>
                                <td>${{ number_format($item->price ?? 0, 2) }}</td>
                                <td class="text-center">{{ $item->quantity ?? 0 }}</td>
                                <td class="text-end pe-3">${{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No items found.</td>
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