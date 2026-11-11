@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Orders</h1>
        <p class="page-subtitle">Manage and track all customer orders</p>
    </div>
</div>

<div class="filter-card">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0" name="search" placeholder="Search order # or customer..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" name="status">
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
        <div class="col-md-3">
            <select class="form-select" name="payment_status">
                <option value="">All Payments</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>
    </form>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h6 class="data-card-title">All Orders</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary">{{ $orders->total() ?? 0 }} total</span>
            <div class="admin-view-toggle">
                <a href="{{ route('admin.orders.index') }}" class="admin-view-btn {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}" title="List View">
                    <i class="bi bi-list-ul"></i>
                </a>
                <a href="{{ route('admin.orders.grid') }}" class="admin-view-btn {{ request()->routeIs('admin.orders.grid') ? 'active' : '' }}" title="Card View">
                    <i class="bi bi-grid"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders ?? [] as $order)
                    <tr>
                        <td class="fw-semibold">#{{ $order->order_number ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm avatar-primary">{{ substr($order->user->name ?? 'N', 0, 1) }}</div>
                                {{ $order->user->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="fw-semibold">${{ number_format($order->total_amount ?? 0, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-inline status-update-form">
                                @csrf
                                <select class="form-select form-select-sm status-select" name="order_status" onchange="this.form.submit()" data-current-status="{{ $order->order_status }}">
                                    <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="preparing" {{ $order->order_status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                    <option value="ready" {{ $order->order_status == 'ready' ? 'selected' : '' }}>Ready</option>
                                    <option value="out_for_delivery" {{ $order->order_status == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                    <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" class="btn btn-outline-success" title="Receipt" data-receipt-url="{{ route('admin.orders.receipt', $order) }}" data-bs-toggle="modal" data-bs-target="#receiptModal">
                                    <i class="bi bi-receipt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-inbox empty-state-icon"></i>
                                <p class="empty-state-title">No orders found</p>
                                <p class="empty-state-text">Try adjusting your filters or search terms.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="card-footer bg-white border-top d-flex justify-content-center py-3">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-update-form').forEach(form => {
        const select = form.querySelector('.status-select');
        const originalStatus = select.dataset.currentStatus;

        select.addEventListener('change', function() {
            const newStatus = this.value;
            if (newStatus === originalStatus) return;

            const submitBtn = this;
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    '_token': form.querySelector('[name=_token]').value,
                    'order_status': newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    select.dataset.currentStatus = newStatus;
                    // Update the payment status badge if needed
                    showToast('Order status updated successfully', 'success');
                } else {
                    select.value = originalStatus;
                    showToast(data.message || 'Failed to update status', 'error');
                }
            })
            .catch(() => {
                select.value = originalStatus;
                showToast('Failed to update status', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
            });
        });
    });

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        toast.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
        toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}-fill me-2"></i>${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
});
</script>
@endsection
