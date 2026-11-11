<aside class="delivery-sidebar" id="deliverySidebar">
    <div class="delivery-sidebar-brand">
        <a href="{{ route('delivery.dashboard') }}" class="delivery-brand">
            <span class="delivery-brand-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C8 6 5 9 5 13a7 7 0 0 0 14 0c0-4-3-7-7-11Z" fill="currentColor"/>
                    <path d="M12 13c-1.5-2-3-3-3-5a3 3 0 0 1 6 0c0 2-1.5 3-3 5Z" fill="rgba(255,255,255,0.35)"/>
                </svg>
            </span>
            <span class="delivery-brand-text">FoodDelivery</span>
        </a>
    </div>

    <nav class="delivery-sidebar-nav">
        <a href="{{ route('delivery.dashboard') }}" class="delivery-nav-item {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('delivery.orders.index') }}" class="delivery-nav-item {{ request()->routeIs('delivery.orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag"></i>
            <span>Orders</span>
        </a>
        <a href="{{ route('delivery.report') }}" class="delivery-nav-item {{ request()->routeIs('delivery.report') || request()->routeIs('delivery.report.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Delivery History</span>
        </a>
        <a href="{{ route('delivery.profile.index') }}" class="delivery-nav-item {{ request()->routeIs('delivery.profile.*') ? 'active' : '' }}">
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>
        <div class="delivery-sidebar-spacer"></div>
        {{-- <a href="#" class="delivery-nav-item delivery-nav-item-muted">
            <i class="bi bi-bell"></i>
            <span>Notifications</span>
        </a> --}}
        <form method="POST" action="{{ route('logout') }}" class="delivery-sidebar-logout">
            @csrf
            <button type="submit" class="delivery-nav-item delivery-nav-item-muted w-100 text-start border-0 bg-transparent">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>

    <div class="delivery-sidebar-footer">
        <small class="text-muted">&copy; {{ date('Y') }} Sire • Fresh Food • Fast Delivery</small>
    </div>
</aside>

<div class="delivery-sidebar-overlay" id="sidebarOverlay"></div>
