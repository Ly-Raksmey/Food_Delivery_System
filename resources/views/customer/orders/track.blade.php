@extends('layouts.customer')

@section('title', 'Track Order #' . $order->order_number)

@section('content')
<section class="customer-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.show', $order) }}">#{{ $order->order_number }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Track</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Order #{{ $order->order_number }}</h2>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'preparing' => 'primary',
                                    'ready' => 'secondary',
                                    'out_for_delivery' => 'orange',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                ];
                                $badgeClass = $statusColors[$order->order_status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $badgeClass }} fs-6">
                                {{ ucwords(str_replace('_', ' ', $order->order_status)) }}
                            </span>
                        </div>

                        <div class="tracking-timeline">
                            @php
                                $steps = [
                                    ['status' => 'pending', 'label' => 'Order Placed', 'icon' => 'bi-receipt'],
                                    ['status' => 'confirmed', 'label' => 'Confirmed', 'icon' => 'bi-check-circle'],
                                    ['status' => 'preparing', 'label' => 'Preparing', 'icon' => 'bi-fire'],
                                    ['status' => 'ready', 'label' => 'Ready for Pickup', 'icon' => 'bi-box-seam'],
                                    ['status' => 'out_for_delivery', 'label' => 'Out for Delivery', 'icon' => 'bi-truck'],
                                    ['status' => 'delivered', 'label' => 'Delivered', 'icon' => 'bi-check2-all'],
                                ];

                                $currentStepIndex = collect($steps)->search(fn($step) => $step['status'] == $order->order_status);
                                if ($order->order_status == 'cancelled') {
                                    $currentStepIndex = -1;
                                }
                            @endphp

                            @foreach($steps as $index => $step)
                                @php
                                    $isCompleted = $currentStepIndex !== false && $currentStepIndex !== -1 && $index <= $currentStepIndex;
                                    $isActive = $currentStepIndex !== false && $currentStepIndex !== -1 && $index == $currentStepIndex;
                                    $isPending = $currentStepIndex === false || ($currentStepIndex !== -1 && $index > $currentStepIndex);
                                @endphp
                                <div class="d-flex mb-4">
                                    <div class="flex-shrink-0">
                                        @if($isCompleted)
                                            <div class="step-icon step-completed">
                                                <i class="bi bi-{{ $step['icon'] }}"></i>
                                            </div>
                                        @elseif($isActive)
                                            <div class="step-icon step-active">
                                                <i class="bi bi-{{ $step['icon'] }}"></i>
                                            </div>
                                        @else
                                            <div class="step-icon step-pending">
                                                <i class="bi bi-{{ $step['icon'] }}"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 fw-semibold {{ $isCompleted || $isActive ? '' : 'text-muted' }}">{{ $step['label'] }}</h6>
                                        @if($isActive)
                                            <span class="badge bg-light text-primary border mt-1">In Progress</span>
                                        @endif
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <div class="tracking-line {{ $isCompleted ? 'line-completed' : '' }}"></div>
                                @endif
                            @endforeach

                            @if($order->order_status == 'cancelled')
                                <div class="d-flex mt-4">
                                    <div class="flex-shrink-0">
                                        <div class="step-icon step-cancelled">
                                            <i class="bi bi-x-circle"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 fw-semibold">Cancelled</h6>
                                        <p class="text-muted small mb-0">This order has been cancelled.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
