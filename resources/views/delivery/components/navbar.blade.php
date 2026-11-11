<nav class="navbar navbar-expand-lg delivery-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('delivery.dashboard') }}">
            <div class="delivery-brand-icon">
                <i class="bi bi-truck"></i>
            </div>
            <span>Delivery Panel</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#deliveryNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="deliveryNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}" href="{{ route('delivery.dashboard') }}">
                        <i class="bi bi-grid-1x2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('delivery.orders.*') ? 'active' : '' }}" href="{{ route('delivery.orders.index') }}">
                        <i class="bi bi-bag"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('delivery.report') ? 'active' : '' }}" href="{{ route('delivery.report') }}">
                        <i class="bi bi-graph-up"></i> Report
                    </a>
                </li>
            </ul>
            @php
                $unreadCount = 0;
                $recentNotifications = collect();
                if (auth()->check()) {
                    try {
                        $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
                        $recentNotifications = auth()->user()->notifications()->latest()->take(5)->get();
                    } catch (\Exception $e) {
                        $unreadCount = 0;
                    }
                }
            @endphp
            {{-- <div class="dropdown me-2">
                <div class="delivery-notif-bell" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    @if($unreadCount > 0)
                        <span class="delivery-notif-badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                    @endif
                </div>
                <ul class="dropdown-menu dropdown-menu-end delivery-notif-menu">
                    <li class="d-flex justify-content-between align-items-center px-3 py-2">
                        <span class="fw-semibold">Notifications</span>
                        @if($unreadCount > 0)
                            <form action="{{ route('notifications.readAll') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link p-0 text-decoration-none">Mark all read</button>
                            </form>
                        @endif
                    </li>
                    <li><hr class="dropdown-divider my-0"></li>
                    @forelse($recentNotifications as $notification)
                        <li>
                            <a class="dropdown-item delivery-notif-item {{ is_null($notification->read_at) ? 'unread' : '' }}" href="{{ route('notifications.show', $notification) }}">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div class="flex-grow-1">
                                        <p class="mb-1 fw-semibold small {{ is_null($notification->read_at) ? 'text-dark' : 'text-muted' }}">
                                            {{ $notification->title }}
                                        </p>
                                        <p class="mb-1 text-muted small" style="font-size:0.75rem;">{{ $notification->message }}</p>
                                        <small class="text-muted" style="font-size:0.7rem;">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if(is_null($notification->read_at))
                                        <span class="badge bg-danger rounded-pill" style="font-size:0.6rem;">New</span>
                                    @endif
                                </div>
                            </a>
                        </li>
                    @empty
                        <li><span class="dropdown-item text-muted small">No notifications yet</span></li>
                    @endforelse
                    <li><hr class="dropdown-divider my-0"></li>
                    <li><a class="dropdown-item text-center small" href="{{ route('notifications.index') }}">View All Notifications</a></li>
                </ul>
            </div> --}}
            <div class="dropdown">
                <div class="delivery-user" data-bs-toggle="dropdown">
                    <div class="delivery-user-avatar">
                        {{ substr(Auth::user()->name ?? 'D', 0, 1) }}
                    </div>
                    <div class="d-none d-md-block">
                        <div class="delivery-user-name">{{ Auth::user()->name ?? 'Driver' }}</div>
                        <div class="delivery-user-role">Delivery Driver</div>
                    </div>
                    <i class="bi bi-chevron-down ms-1" style="font-size:0.7rem; opacity:0.7;"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('delivery.profile.index') }}">
                            <i class="bi bi-person"></i> My Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>

                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
