@extends('layouts.customer')

@section('title', 'Notifications')

@section('content')
<section class="customer-section">
    <div class="container">
        <div class="page-header">
            <div>
                <h1 class="page-title">Notifications</h1>
                <p class="page-subtitle">Stay updated with your order status</p>
            </div>
            @if($unreadCount > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-check-all me-1"></i> Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        @if($notifications->count() > 0)
            <div class="data-card">
                <div class="data-card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <a href="{{ route('notifications.show', $notification) }}" class="list-group-item list-group-item-action notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="notification-icon {{ $notification->type == 'order' ? 'primary' : ($notification->type == 'delivery' ? 'info' : ($notification->type == 'payment' ? 'success' : 'secondary')) }}">
                                            <i class="bi bi-{{ $notification->type == 'order' ? 'receipt' : ($notification->type == 'delivery' ? 'truck' : ($notification->type == 'payment' ? 'credit-card' : 'bell')) }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold {{ is_null($notification->read_at) ? 'text-dark' : 'text-muted' }}">
                                                {{ $notification->title }}
                                                @if(is_null($notification->read_at))
                                                    <span class="badge bg-danger rounded-pill ms-1" style="font-size:0.6rem;">New</span>
                                                @endif
                                            </h6>
                                            <p class="mb-1 text-muted small">{{ $notification->message }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        @if(is_null($notification->read_at))
                                            <form action="{{ route('notifications.read', $notification) }}" method="POST" class="d-inline" onclick="event.stopPropagation();">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            </form>
                                        @else
                                            <i class="bi bi-check2-all text-success"></i>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-bell-slash empty-state-icon"></i>
                <p class="empty-state-title">No notifications yet</p>
                <p class="empty-state-text">We'll notify you when something important happens.</p>
            </div>
        @endif
    </div>
</section>
@endsection
