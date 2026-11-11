<header class="delivery-topbar">
    <div class="delivery-topbar-left">
        <button class="delivery-sidebar-toggler" id="sidebarToggler" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <h1 class="delivery-topbar-title">@yield('topbar_title', 'Delivery Dashboard')</h1>
            <p class="delivery-topbar-subtitle">@yield('topbar_subtitle', 'Manage your assigned food deliveries')</p>
        </div>
    </div>

    <div class="delivery-topbar-right">
        {{-- <button class="delivery-topbar-icon" title="Notifications">
            <i class="bi bi-bell"></i>
            <span class="delivery-topbar-badge">3</span>
        </button> --}}

        <div class="delivery-status-toggle" id="statusToggle" title="Toggle online/offline">
            <span class="delivery-status-dot delivery-status-online"></span>
            <span class="delivery-status-text d-none d-sm-inline">Online</span>
        </div>

        <div class="dropdown">
            <div class="delivery-topbar-user" data-bs-toggle="dropdown">
                <div class="delivery-topbar-avatar">
                    {{ substr(Auth::user()->name ?? 'D', 0, 1) }}
                </div>
                <div class="d-none d-md-block text-start">
                    <div class="delivery-topbar-name">Hello, {{ Auth::user()->name ?? 'Driver' }} 👋</div>
                    <div class="delivery-topbar-role">Ready for delivery</div>
                </div>
                <i class="bi bi-chevron-down delivery-topbar-chevron d-none d-md-block"></i>
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
    </div>
</header>
