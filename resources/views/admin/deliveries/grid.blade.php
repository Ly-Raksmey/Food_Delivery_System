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
        <div class="admin-foods-grid">
            @forelse($deliveries ?? [] as $delivery)
                <div class="admin-food-card">
                    <div class="admin-food-card-body">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <h6 class="admin-food-card-title mb-0">#{{ $delivery->order->order_number ?? 'N/A' }}</h6>
                            <span class="badge bg-{{ $delivery->status == 'delivered' ? 'success' : ($delivery->status == 'cancelled' ? 'danger' : ($delivery->status == 'picked_up' || $delivery->status == 'out_for_delivery' ? 'info' : 'warning')) }}">
                                {{ ucfirst(str_replace('_', ' ', $delivery->status ?? 'pending')) }}
                            </span>
                        </div>
                        <div class="mt-2">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="avatar avatar-sm avatar-primary">{{ substr($delivery->deliveryStaff->name ?? 'U', 0, 1) }}</div>
                                <span>{{ $delivery->deliveryStaff->name ?? 'Unassigned' }}</span>
                            </div>
                            <div class="text-muted" style="font-size: 0.85rem;">
                                Assigned: <span>{{ $delivery->assigned_at ? \Carbon\Carbon::parse($delivery->assigned_at)->format('M d, Y H:i') : 'N/A' }}</span>
                            </div>
                            <div class="text-muted" style="font-size: 0.85rem;">
                                Delivered: <span>{{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->format('M d, Y H:i') : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="admin-food-card-actions">
                            <a href="{{ route('admin.deliveries.show', $delivery) }}" class="btn btn-outline-primary btn-sm" title="View">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="admin-foods-empty">
                    <div class="empty-state">
                        <i class="bi bi-bicycle empty-state-icon"></i>
                        <p class="empty-state-title">No deliveries yet</p>
                        <p class="empty-state-text">Deliveries will appear here once orders are assigned.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection