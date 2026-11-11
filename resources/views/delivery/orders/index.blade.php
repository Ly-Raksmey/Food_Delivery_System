@extends('layouts.delivery')

@section('title', 'My Orders')
@section('topbar_title', 'My Orders')
@section('topbar_subtitle', 'View and manage all your deliveries')

@section('content')
<div class="delivery-orders-page">
    <div class="delivery-orders-header">
        <div>
            <h2 class="delivery-orders-title">My Orders</h2>
            <p class="delivery-orders-subtitle">View and manage all your deliveries</p>
        </div>
        {{-- <div class="delivery-orders-header-actions">
            <button class="delivery-icon-btn" title="Notifications">
                <i class="bi bi-bell"></i>
                <span class="badge">3</span>
            </button>

            <div class="delivery-status-chip" id="statusToggle" title="Toggle online/offline">
                <span class="delivery-status-dot delivery-status-online"></span>
                <span class="delivery-status-text">Online</span>
            </div>

            <div class="dropdown">
                <div class="delivery-user-chip" data-bs-toggle="dropdown">
                    <div class="delivery-user-avatar">
                        {{ substr(Auth::user()->name ?? 'D', 0, 1) }}
                    </div>
                    <div class="delivery-user-meta">
                        <div class="delivery-user-name">Hello, {{ Auth::user()->name ?? 'Driver' }} 👋</div>
                        <div class="delivery-user-role">Ready for delivery</div>
                    </div>
                    <i class="bi bi-chevron-down" style="color: var(--premium-muted); font-size: 0.75rem;"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end delivery-topbar-dropdown">
                    <li><a class="dropdown-item" href="{{ route('delivery.profile.index') }}"><i class="bi bi-person me-2"></i> My Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('delivery.orders.index') }}"><i class="bi bi-bag me-2"></i> My Orders</a></li>
                    <li><a class="dropdown-item" href="{{ route('delivery.report') }}"><i class="bi bi-clock-history me-2"></i> Delivery History</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div> --}}
    </div>

    <div class="delivery-orders-toolbar">
        <div class="delivery-tabs-wrap">
            <div class="delivery-premium-tabs" id="orderTabs" role="tablist">
                <button class="delivery-premium-tab active" id="assigned-tab" data-bs-toggle="pill" data-bs-target="#assigned" type="button" role="tab">
                    <i class="bi bi-clipboard-check"></i> Assigned <span class="tab-count">{{ $assignedOrders->count() }}</span>
                </button>
                <button class="delivery-premium-tab" id="active-tab" data-bs-toggle="pill" data-bs-target="#active" type="button" role="tab">
                    <i class="bi bi-truck"></i> Active <span class="tab-count">{{ $activeOrders->count() }}</span>
                </button>
                <button class="delivery-premium-tab" id="available-tab" data-bs-toggle="pill" data-bs-target="#available" type="button" role="tab">
                    <i class="bi bi-inbox"></i> Available <span class="tab-count">{{ $availableOrders->count() }}</span>
                </button>
                <button class="delivery-premium-tab" id="completed-tab" data-bs-toggle="pill" data-bs-target="#completed" type="button" role="tab">
                    <i class="bi bi-check-circle"></i> Completed <span class="tab-count">{{ $completedOrders->count() }}</span>
                </button>
            </div>
        </div>

        <div class="delivery-view-toggle">
            <button type="button" class="delivery-view-btn active" data-view="list" title="List View">
                <i class="bi bi-list-ul"></i>
            </button>
            <button type="button" class="delivery-view-btn" data-view="card" title="Card View">
                <i class="bi bi-grid"></i>
            </button>
        </div>
    </div>

    <div class="delivery-orders-layout">
        <div class="tab-content" id="orderTabsContent">
            <div class="tab-pane fade show active" id="assigned" role="tabpanel">
                @php $orders = $assignedOrders; @endphp
                @include('delivery.orders.partials.assigned')
            </div>

            <div class="tab-pane fade" id="active" role="tabpanel">
                @php $orders = $activeOrders; @endphp
                @include('delivery.orders.partials.active')
            </div>

            <div class="tab-pane fade" id="available" role="tabpanel">
                @php $orders = $availableOrders; @endphp
                @include('delivery.orders.partials.available')
            </div>

            <div class="tab-pane fade" id="completed" role="tabpanel">
                @php $orders = $completedOrders; @endphp
                @include('delivery.orders.partials.completed')
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewBtns = document.querySelectorAll('.delivery-view-btn');
    const savedView = localStorage.getItem('deliveryOrdersView') || 'list';

    function setActiveView(view) {
        viewBtns.forEach(function(btn) {
            btn.classList.toggle('active', btn.getAttribute('data-view') === view);
        });

        document.querySelectorAll('.delivery-orders-list-view, .delivery-orders-card-view').forEach(function(el) {
            el.style.display = 'none';
        });

        document.querySelectorAll('.delivery-orders-list-view[data-view-active="' + view + '"], .delivery-orders-card-view[data-view-active="' + view + '"]').forEach(function(el) {
            el.style.display = '';
        });

        localStorage.setItem('deliveryOrdersView', view);
    }

    viewBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            setActiveView(this.getAttribute('data-view'));
        });
    });

    setActiveView(savedView);
});
</script>
@endsection
