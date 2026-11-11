<nav class="topbar">
    <div class="topbar-left">
        <button class="topbar-menu-btn" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
        <div class="d-none d-md-flex align-items-center gap-2">
            <span class="text-muted" style="font-size:0.8rem;">Pages /</span>
            <span class="topbar-title">@yield('title', 'Dashboard')</span>
        </div>
    </div>
    <div class="topbar-right">
        @php
            $unreadCount = 0;
            $recentNotifications = collect();
            if (auth()->check()) {
                try {
                    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
                    $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get();
                } catch (\Exception $e) {
                    $unreadCount = 0;
                }
            }
        @endphp
        <div class="dropdown">
            <button class="topbar-icon-btn" data-bs-toggle="dropdown" aria-label="Notifications">
                <i class="bi bi-bell"></i>
                @if($unreadCount > 0)
                    <span class="badge-notification" style="position:absolute;top:6px;right:6px;width:8px;height:8px;background:var(--danger);border-radius:50%;border:2px solid white;"></span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end" style="width: 320px;">
                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold" style="font-size:0.85rem;">Notifications</h6>
                    <div class="d-flex align-items-center gap-2">
                        @if($unreadCount > 0)
                            <span class="badge bg-danger" style="font-size:0.65rem;">{{ $unreadCount }} new</span>
                            <form action="{{ route('notifications.readAll') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-primary p-0" style="font-size:0.7rem; text-decoration:none;">
                                    <i class="bi bi-check-all"></i> Clear
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div style="max-height: 300px; overflow-y: auto;">
                    @forelse($recentNotifications as $notification)
                        <a href="{{ route('notifications.readAndRedirect', $notification) }}" class="dropdown-item py-2 notification-dropdown-item {{ is_null($notification->read_at) ? 'unread' : 'read' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <p class="mb-1 fw-semibold small {{ is_null($notification->read_at) ? 'text-dark' : 'text-muted' }}">
                                        {{ $notification->title }}
                                    </p>
                                    <p class="mb-1 text-muted small" style="font-size: 0.75rem;">{{ Str::limit($notification->message, 60) }}</p>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                @if(is_null($notification->read_at))
                                    <span class="badge bg-danger rounded-pill ms-2" style="font-size: 0.6rem; flex-shrink:0;">New</span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="py-4 text-center text-muted" style="font-size:0.8rem;">
                            <i class="bi bi-bell-slash fs-4 d-block mb-2 text-secondary"></i>
                            No notifications
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="dropdown">
            <div class="topbar-user" data-bs-toggle="dropdown">
                <div class="topbar-user-avatar">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="d-none d-md-block">
                    <div class="topbar-user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                    <div class="topbar-user-role">Administrator</div>
                </div>
                <i class="bi bi-chevron-down ms-1 text-muted" style="font-size:0.7rem;"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.restaurant.index') }}">
                        <i class="bi bi-shop"></i> Restaurant Settings
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
