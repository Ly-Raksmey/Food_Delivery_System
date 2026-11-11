@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Payments</h1>
        <p class="page-subtitle">Monitor all payment transactions</p>
    </div>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h6 class="data-card-title">All Payments</h6>
        <span class="badge bg-primary">{{ $payments->count() ?? 0 }} total</span>
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Reference</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments ?? [] as $payment)
                    <tr>
                        <td class="fw-semibold">#{{ $payment->order->order_number ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-{{ $payment->payment_method == 'card' ? 'credit-card' : ($payment->payment_method == 'cash' ? 'cash' : 'wallet') }} me-1"></i>
                                {{ ucfirst($payment->payment_method ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="fw-semibold text-success">${{ number_format($payment->amount ?? 0, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $payment->status == 'paid' ? 'success' : ($payment->status == 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($payment->status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="text-muted font-monospace" style="font-size:0.8rem;">{{ $payment->reference ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-credit-card empty-state-icon"></i>
                                <p class="empty-state-title">No payments yet</p>
                                <p class="empty-state-text">Payment records will appear here once orders are placed.</p>
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
