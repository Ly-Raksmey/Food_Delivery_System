
<style>
    /* ============================================
       CLEAN GREEN ADMIN SIDEBAR
       ============================================ */

    .sidebar {
        width: 260px !important;
        background: #ffffff;
        border-right: 1px solid #e5e7eb;
        box-shadow: 4px 0 18px rgba(15, 23, 42, 0.04);
        transition: all 0.25s ease;
        z-index: 1040;
    }

    /* Brand */
    .sidebar-brand {
            height: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-bottom: 1px solid #f1f5f9;
            background: #ffffff;
            text-align: center;
    }

    .sidebar-brand-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #16a34a;
        color: #ffffff;
        font-size: 19px;
        box-shadow: 0 4px 10px rgba(22, 163, 74, 0.20);
        flex-shrink: 0;
    }

     .sidebar-brand-logo {
        background: transparent !important;
        box-shadow: none !important;
        width: 260px !important;
        height: 90px !important;
        border-radius: 0 !important;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-logo-img {
        width: 260px !important;
        height: 90px !important;
        object-fit: contain;
    }

    .sidebar-brand-text {
        color: #111827;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    /* Navigation */
    .sidebar-nav {
        padding: 18px 12px 24px;
    }

    .sidebar-section-title {
        padding: 14px 10px 7px;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.09em;
    }

    .sidebar-nav .nav-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 43px;
        margin: 0px 0;
        padding: 10px 12px;
        border-radius: 9px;
        color: #64748b;
        font-size: 13.5px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .sidebar-nav .nav-link i {
        width: 20px;
        text-align: center;
        font-size: 17px;
        color: #94a3b8;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .sidebar-nav .nav-link:hover {
        color: #15803d;
        background: #f0fdf4;
    }

    .sidebar-nav .nav-link:hover i {
        color: #16a34a;
    }

    /* Active navigation */
    .sidebar-nav .nav-link.active {
        color: #15803d;
        background: #dcfce7;
        font-weight: 600;
    }

    .sidebar-nav .nav-link.active i {
        color: #16a34a;
    }

    .sidebar-nav .nav-link.active::before {
        content: "";
        position: absolute;
        left: 0;
        top: 9px;
        bottom: 9px;
        width: 3px;
        border-radius: 0 4px 4px 0;
        background: #16a34a;
    }

    /* Pending order badge */
    .sidebar-nav .nav-link .badge {
        margin-left: auto !important;
        min-width: 22px;
        height: 20px;
        padding: 2px 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: #dc2626 !important;
        color: #ffffff;
        font-size: 10px !important;
        font-weight: 700;
    }

    /* Overlay */
    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.40);
        z-index: 1030;
        opacity: 0;
        visibility: hidden;
        transition: all 0.25s ease;
    }

    .sidebar-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    /* Scrollbar */
    .sidebar-nav {
        height: calc(100vh - 68px);
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #d1fae5 transparent;
    }

    .sidebar-nav::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: #d1fae5;
        border-radius: 10px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: #86efac;
    }

    /* Mobile */
    @media (max-width: 991.98px) {
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            transform: translateX(-100%);
            transition: transform 0.25s ease;
        }

        .sidebar.show {
            transform: translateX(0);
        }
    }

    /* Small screens */
    @media (max-width: 575.98px) {
        .sidebar {
            width: 260px;
        }

        .sidebar-brand {
            height: 110px;
            padding: 0 16px;
        }

        .sidebar-brand-logo {
            width: 240px !important;
            height: 70px !important;
        }

        .sidebar-logo-img {
            width: 240px !important;
            height: 70px !important;
        }

        .sidebar-nav {
            padding-left: 10px;
            padding-right: 10px;
        }
    }
</style>

<div class="sidebar" id="adminSidebar">

    {{-- Brand --}}
    @php
        $restaurant = \App\Models\Restaurant::first();
        $hasLogo = $restaurant && $restaurant->logo;
    @endphp
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon {{ $hasLogo ? 'sidebar-brand-logo' : '' }}">
            @if ($hasLogo)
                <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name }}" class="sidebar-logo-img">
            @else
                <i class="bi bi-basket2-fill"></i>
            @endif
        </div>

        {{-- <span class="sidebar-brand-text">
            {{ $restaurant->name ?? 'FoodDelivery' }}
        </span> --}}
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        {{-- Main --}}
        <div class="sidebar-section-title">
            Main
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i>
            <span class="sidebar-nav-text">Dashboard</span>
        </a>

        {{-- Restaurant
        <a href="{{ route('admin.restaurant.index') }}"
           class="nav-link {{ request()->routeIs('admin.restaurant.*') ? 'active' : '' }}">
            <i class="bi bi-shop"></i>
            <span class="sidebar-nav-text">Restaurant</span>
        </a>
        --}}

        {{-- Analytics
        <a href="{{ route('admin.analytics.index') }}"
           class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i>
            <span class="sidebar-nav-text">Analytics</span>
        </a>
        --}}


        {{-- Menu Management --}}
        <div class="sidebar-section-title">
            Menu Management
        </div>

        <a href="{{ route('admin.categories.index') }}"
           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-collection"></i>
            <span class="sidebar-nav-text">Categories</span>
        </a>

        <a href="{{ route('admin.foods.index') }}"
           class="nav-link {{ request()->routeIs('admin.foods.*') ? 'active' : '' }}">
            <i class="bi bi-egg-fried"></i>
            <span class="sidebar-nav-text">Foods</span>
        </a>


        {{-- Operations --}}
        <div class="sidebar-section-title">
            Operations
        </div>

        <a href="{{ route('admin.orders.index') }}"
           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">

            <i class="bi bi-receipt"></i>

            <span class="sidebar-nav-text">
                Orders
            </span>

            @if(isset($sidebarStats['pending_orders']) && $sidebarStats['pending_orders'] > 0)
                <span class="badge">
                    {{ $sidebarStats['pending_orders'] }}
                </span>
            @endif

        </a>

        <a href="{{ route('admin.deliveries.index') }}"
           class="nav-link {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}">
            <i class="bi bi-bicycle"></i>
            <span class="sidebar-nav-text">Deliveries</span>
        </a>

        <a href="{{ route('admin.delivery-staff.index') }}"
           class="nav-link {{ request()->routeIs('admin.delivery-staff.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i>
            <span class="sidebar-nav-text">Delivery Staff</span>
        </a>

        <a href="{{ route('admin.customers.index') }}"
           class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span class="sidebar-nav-text">Customers</span>
        </a>


        {{-- Finance --}}
        <div class="sidebar-section-title">
            Finance
        </div>

        <a href="{{ route('admin.payments.index') }}"
           class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i>
            <span class="sidebar-nav-text">Payments</span>
        </a>

        <a href="{{ route('admin.expenses.index') }}"
           class="nav-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            <span class="sidebar-nav-text">Expenses</span>
        </a>

        {{-- Income
        <a href="{{ route('admin.income.index') }}"
           class="nav-link {{ request()->routeIs('admin.income.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i>
            <span class="sidebar-nav-text">Income</span>
        </a>
        --}}


        {{-- Marketing --}}
        <div class="sidebar-section-title">
            Marketing
        </div>

        <a href="{{ route('admin.coupons.index') }}"
           class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated"></i>
            <span class="sidebar-nav-text">Coupons</span>
        </a>

        <a href="{{ route('admin.reviews.index') }}"
           class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <i class="bi bi-star"></i>
            <span class="sidebar-nav-text">Reviews</span>
        </a>


        {{-- Reports --}}
        <div class="sidebar-section-title">
            Reports
        </div>

        <a href="{{ route('admin.reports.index') }}"
           class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <span class="sidebar-nav-text">Reports</span>
        </a>

    </nav>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>