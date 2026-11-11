@extends('layouts.admin')

@section('title', 'Deliveries')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Deliveries</h1>
        <p class="page-subtitle">Track and manage all delivery operations</p>
    </div>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h6 class="data-card-title">All Deliveries</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary">{{ $deliveries->count() ?? 0 }} total</span>
            <div class="admin-view-toggle">
                <a href="{{ route('admin.deliveries.index') }}" class="admin-view-btn {{ request()->routeIs('admin.deliveries.index') ? 'active' : '' }}" title="List View">
                    <i class="bi bi-list-ul"></i>
                </a>
                <a href="{{ route('admin.deliveries.grid') }}" class="admin-view-btn {{ request()->routeIs('admin.deliveries.grid') ? 'active' : '' }}" title="Card View">
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
                        <th>Order</th>
                        <th>Delivery Staff</th>
                        <th>Status</th>
                        <th>Assigned At</th>
                        <th>Delivered At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries ?? [] as $delivery)
                    <tr>
                        <td class="fw-semibold">#{{ $delivery->order->order_number ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm avatar-primary">{{ substr($delivery->deliveryStaff->name ?? 'U', 0, 1) }}</div>
                                {{ $delivery->deliveryStaff->name ?? 'Unassigned' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $delivery->status == 'assigned' ? 'warning' : ($delivery->status == 'picked_up' ? 'info' : ($delivery->status == 'out_for_delivery' ? 'orange' : ($delivery->status == 'delivered' ? 'success' : 'secondary'))) }}">
                                {{ ucfirst(str_replace('_', ' ', $delivery->status ?? 'pending')) }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $delivery->assigned_at ? \Carbon\Carbon::parse($delivery->assigned_at)->format('M d, Y H:i') : 'N/A' }}</td>
                        <td class="text-muted">{{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->format('M d, Y H:i') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('admin.deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-primary btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-bicycle empty-state-icon"></i>
                                <p class="empty-state-title">No deliveries yet</p>
                                <p class="empty-state-text">Deliveries will appear here once orders are assigned.</p>
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
