@extends('layouts.delivery')

@section('title', 'Delivery Dashboard')
@section('topbar_title', 'Delivery Dashboard')
@section('topbar_subtitle', 'Manage your assigned food deliveries')

@section('content')
@php
    $firstAssigned = $selectedDelivery ?? $recentDeliveries->first();
    $activeDelivery = $firstAssigned ? $firstAssigned->order : null;
    $customer = $activeDelivery->user ?? null;
    $address = $activeDelivery->address ?? null;
    $customerLat = $address->latitude ?? null;
    $customerLng = $address->longitude ?? null;
@endphp

<div class="delivery-hero">
    <div class="delivery-hero-shapes">
        <div class="delivery-hero-shape delivery-hero-shape-1"></div>
        <div class="delivery-hero-shape delivery-hero-shape-2"></div>
    </div>
    <div class="delivery-hero-badge">
        <i class="bi bi-lightning-fill"></i> Food Delivery Partner
    </div>
    <h2 class="delivery-hero-title">
        Deliver fresh food,<br><em>make customers happy.</em>
    </h2>
    <p class="delivery-hero-sub">
        Stay on top of your assigned orders and complete deliveries quickly and safely.
        Fresh Food • Fast Delivery
    </p>
    <a href="{{ route('delivery.orders.index') }}" class="delivery-btn delivery-btn-primary delivery-btn-lg">
        <i class="bi bi-bag"></i> View Deliveries
    </a>
    <div class="delivery-hero-illustration">
        <i class="bi bi-truck"></i>
    </div>
</div>

<div class="delivery-quick-actions">
    <a href="{{ route('delivery.orders.index') }}" class="delivery-quick-action">
        <div class="delivery-quick-action-icon">
            <i class="bi bi-bag"></i>
        </div>
        <div>
            <div class="fw-semibold">View Orders</div>
            <small class="text-muted">{{ $assignedOrders }} pending</small>
        </div>
    </a>
    <a href="{{ route('delivery.report') }}" class="delivery-quick-action">
        <div class="delivery-quick-action-icon">
            <i class="bi bi-clock-history"></i>
        </div>
        <div>
            <div class="fw-semibold">Delivery History</div>
            <small class="text-muted">View completed</small>
        </div>
    </a>
    <a href="#" class="delivery-quick-action">
        <div class="delivery-quick-action-icon">
            <i class="bi bi-headset"></i>
        </div>
        <div>
            <div class="fw-semibold">Contact Support</div>
            <small class="text-muted">Get help</small>
        </div>
    </a>
</div>

<div class="delivery-stats">
    <div class="row g-3">
        <div class="col-6 col-lg-3">
            <div class="delivery-stat-card">
                <span class="delivery-stat-icon"><i class="bi bi-truck"></i></span>
                <div>
                    <h3 class="delivery-stat-value">{{ $assignedOrders }}</h3>
                    <p class="delivery-stat-label">Today's Deliveries</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="delivery-stat-card">
                <span class="delivery-stat-icon"><i class="bi bi-hourglass-split"></i></span>
                <div>
                    <h3 class="delivery-stat-value">{{ $activeDeliveries }}</h3>
                    <p class="delivery-stat-label">Pending Orders</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="delivery-stat-card">
                <span class="delivery-stat-icon"><i class="bi bi-check-circle"></i></span>
                <div>
                    <h3 class="delivery-stat-value">{{ $completedDeliveries }}</h3>
                    <p class="delivery-stat-label">Completed</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="delivery-stat-card">
                <span class="delivery-stat-icon"><i class="bi bi-cash-stack"></i></span>
                <div>
                    <h3 class="delivery-stat-value">${{ number_format($report_data['earnings'] ?? 0, 2) }}</h3>
                    <p class="delivery-stat-label">Today's Earnings</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="delivery-section">
    <h2 class="delivery-section-title">Active Delivery</h2>
    <div class="row g-3 mb-4">
        <div class="col-lg-5">
            @if($activeDelivery)
            <div class="delivery-active-card h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h3 class="delivery-active-order">#{{ $activeDelivery->order_number ?? 'N/A' }}</h3>
                        <span class="delivery-badge delivery-badge-{{ $firstAssigned->status === 'out_for_delivery' ? 'out' : ($firstAssigned->status === 'picked_up' ? 'ready' : 'pending') }}">
                            {{ ucfirst(str_replace('_', ' ', $firstAssigned->status)) }}
                        </span>
                    </div>
                    <span class="delivery-active-type">Delivery</span>
                </div>

                <div class="delivery-active-row">
                    <i class="bi bi-building"></i>
                    <div>
                        <small>Restaurant</small>
                        <p>FoodDelivery</p>
                    </div>
                </div>
                <div class="delivery-active-row">
                    <i class="bi bi-person"></i>
                    <div>
                        <small>Customer</small>
                        <p>{{ $customer->name ?? 'Unknown' }}</p>
                    </div>
                </div>
                <div class="delivery-active-row">
                    <i class="bi bi-telephone"></i>
                    <div>
                        <small>Phone</small>
                        <p>{{ $customer->phone ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="delivery-active-row">
                    <i class="bi bi-geo-alt"></i>
                    <div>
                        <small>Address</small>
                        <p>{{ $address->address ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="delivery-active-row">
                    <i class="bi bi-basket"></i>
                    <div>
                        <small>Order Total</small>
                        <p>${{ number_format($activeDelivery->total_amount ?? 0, 2) }}</p>
                    </div>
                </div>
                <div class="delivery-active-row">
                    <i class="bi bi-credit-card"></i>
                    <div>
                        <small>Payment</small>
                        <p class="text-capitalize mb-0">{{ str_replace('_', ' ', $activeDelivery->payment_method ?? 'cash') }}</p>
                    </div>
                </div>

                @if(($activeDelivery->items ?? collect())->count() > 0)
                <div class="mt-3">
                    <small class="text-muted fw-semibold">Order Items</small>
                    <div class="delivery-active-items">
                        @foreach($activeDelivery->items as $item)
                            <div class="delivery-active-item">
                                <span>{{ $item->food->name ?? 'Item' }} × {{ $item->quantity ?? 1 }}</span>
                                <span class="fw-semibold">${{ number_format(($item->price ?? 0) * ($item->quantity ?? 1), 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="d-grid gap-2 mt-4">
                    @if($customer && $customer->phone)
                        <a href="tel:{{ $customer->phone }}" class="delivery-btn delivery-btn-ghost w-100">
                            <i class="bi bi-telephone me-1"></i> Call Customer
                        </a>
                    @endif
                    @if($firstAssigned->status === 'assigned')
                        <form method="POST" action="{{ route('delivery.orders.accept', $activeDelivery) }}">
                            @csrf
                            <button type="submit" class="delivery-btn delivery-btn-primary w-100">
                                <i class="bi bi-play-fill"></i> Start Delivery
                            </button>
                        </form>
                    @elseif($firstAssigned->status === 'accepted')
                        <form method="POST" action="{{ route('delivery.orders.pickup', $activeDelivery) }}">
                            @csrf
                            <button type="submit" class="delivery-btn delivery-btn-primary w-100">
                                <i class="bi bi-bag-check"></i> Pick Up Order
                            </button>
                        </form>
                    @elseif($firstAssigned->status === 'picked_up')
                        <form method="POST" action="{{ route('delivery.orders.outForDelivery', $activeDelivery) }}">
                            @csrf
                            <button type="submit" class="delivery-btn delivery-btn-primary w-100">
                                <i class="bi bi-truck"></i> Start Delivery
                            </button>
                        </form>
                    @elseif($firstAssigned->status === 'out_for_delivery')
                        @if($activeDelivery->payment_method === 'khqr' && $activeDelivery->payment_status !== 'paid')
                            <button type="button" class="delivery-btn delivery-btn-success w-100" data-bs-toggle="modal" data-bs-target="#khqrModal" data-total="{{ $activeDelivery->total_amount ?? 0 }}" data-order-id="{{ $activeDelivery->id }}">
                                <i class="bi bi-check-circle me-1"></i> Mark as Delivered
                            </button>
                        @else
                            <form method="POST" action="{{ route('delivery.orders.delivered', $activeDelivery) }}" class="w-100">
                                @csrf
                                <button type="submit" class="delivery-btn delivery-btn-success w-100">
                                    <i class="bi bi-check-circle"></i> Mark as Delivered
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
            @else
            <div class="delivery-active-card h-100 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #D4E4C4;"></i>
                    <p class="text-muted mt-3 mb-0" style="font-size: 1rem;">No active delivery right now.</p>
                    <p class="text-muted" style="font-size: 0.85rem;">Check your orders for new deliveries.</p>
                </div>
            </div>
            @endif
        </div>


        
        <div class="col-lg-7">
            <div class="delivery-map-card h-100">
                <div class="delivery-map-header">
                    <div class="delivery-map-legend">
                        <span class="delivery-map-legend-item">
                            <span class="delivery-map-legend-dot" style="background: #2F6B3A;"></span>
                            Restaurant
                        </span>
                        <span class="delivery-map-legend-item">
                            <span class="delivery-map-legend-dot" style="background: #4F9D5A;"></span>
                            Your Location
                        </span>
                        <span class="delivery-map-legend-item">
                            <span class="delivery-map-legend-dot" style="background: #E6A23C;"></span>
                            Customer
                        </span>
                    </div>
                </div>




                <div id="activeDeliveryMap" class="delivery-map" style="height: 340px;"
                    data-restaurant-lat="{{ $restaurantLat }}"
                    data-restaurant-lng="{{ $restaurantLng }}"
                    data-restaurant-name="{{ $restaurantName }}"
                    data-driver-lat="{{ $driverLat }}"
                    data-driver-lng="{{ $driverLng }}"
                    data-customer-lat="{{ $customerLat }}"
                    data-customer-lng="{{ $customerLng }}"
                    data-customer-address="{{ $address->address ?? 'Customer Location' }}">
                </div>




                <div class="delivery-map-footer">
                    <div class="delivery-map-meta">
                        <span class="delivery-map-meta-label">Estimated arrival</span>
                        <strong id="etaValue">-- min</strong>
                    </div>
                    <div class="delivery-map-meta">
                        <span class="delivery-map-meta-label">Distance</span>
                        <strong id="distanceValue">-- km</strong>
                    </div>
                    @if($customerLat && $customerLng)
                    <a href="https://www.google.com/maps/dir/?api=1&origin={{ $driverLat }},{{ $driverLng }}&destination={{ $customerLat }},{{ $customerLng }}&travelmode=driving" target="_blank" class="delivery-btn delivery-btn-primary delivery-btn-sm">
                        <i class="bi bi-navigation me-1"></i> Navigate
                    </a>
                    @endif
                </div>




            </div>
        </div>
    </div>
</div>

<div class="delivery-section">
    <div class="delivery-section-header">
        <div>
            <h2 class="delivery-section-title mb-0">Your Deliveries</h2>
            <p class="delivery-section-subtitle mb-0">Recent assigned orders</p>
        </div>
        <a href="{{ route('delivery.orders.index') }}" class="delivery-btn delivery-btn-outline">
            View All <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="delivery-table-wrap">
        <table class="delivery-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>Order Time</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentDeliveries as $delivery)
                    @php
                        $order = $delivery->order;
                        $orderCustomer = $order->user ?? null;
                        $orderAddress = $order->address ?? null;
                        $statusBadge = match($delivery->status) {
                            'assigned' => 'pending',
                            'accepted' => 'preparing',
                            'picked_up' => 'ready',
                            'out_for_delivery' => 'out',
                            'delivered' => 'delivered',
                            default => 'pending',
                        };
                    @endphp
                    <tr class="delivery-table-row" data-href="{{ route('delivery.dashboard', ['order' => $delivery->id]) }}">
                        <td class="fw-semibold" data-label="Order">#{{ $order->order_number ?? 'N/A' }}</td>
                        <td data-label="Customer">
                            <div class="delivery-table-user">
                                <span class="delivery-table-avatar">{{ $orderCustomer ? substr($orderCustomer->name, 0, 1) : '?' }}</span>
                                <div>
                                    <div class="fw-semibold">{{ $orderCustomer->name ?? 'Unknown' }}</div>
                                    <small class="text-muted">{{ $orderCustomer->phone ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td data-label="Address"><small class="text-muted">{{ $orderAddress->address ?? 'N/A' }}</small></td>
                        <td data-label="Time"><small>{{ $order->created_at?->format('h:i A') ?? '' }}</small></td>
                        <td data-label="Amount" class="fw-semibold">${{ number_format($order->total_amount ?? 0, 2) }}</td>
                        <td data-label="Status">
                            <span class="delivery-badge delivery-badge-{{ $statusBadge }}">
                                {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                            </span>
                        </td>
                        <td data-label="Action">
                            <a href="{{ route('delivery.orders.show', $order) }}" class="delivery-btn delivery-btn-ghost delivery-btn-sm">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="delivery-empty">
                                <i class="bi bi-inbox"></i>
                                <p>No recent orders assigned yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.getElementById('activeDeliveryMap');
    
    if (!mapContainer) return;
    
    const restaurantLat = parseFloat(mapContainer.dataset.restaurantLat);
    const restaurantLng = parseFloat(mapContainer.dataset.restaurantLng);
    const restaurantName = mapContainer.dataset.restaurantName;
    const driverLat = parseFloat(mapContainer.dataset.driverLat);
    const driverLng = parseFloat(mapContainer.dataset.driverLng);
    const customerLat = mapContainer.dataset.customerLat ? parseFloat(mapContainer.dataset.customerLat) : null;
    const customerLng = mapContainer.dataset.customerLng ? parseFloat(mapContainer.dataset.customerLng) : null;
    const customerAddress = mapContainer.dataset.customerAddress;
    
    if (!restaurantLat || !restaurantLng) return;
    
    const map = L.map('activeDeliveryMap', {
        center: [driverLat || restaurantLat, driverLng || restaurantLng],
        zoom: 14,
        zoomControl: true,
        attributionControl: true
    });
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    
    const restaurantIcon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="
            background: linear-gradient(135deg, #2F6B3A 0%, #4F9D5A 100%);
            width: 40px;
            height: 40px;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(47,107,58,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
        ">
            <i class="bi bi-shop" style="color: white; font-size: 16px; transform: rotate(45deg);"></i>
        </div>`,
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });
    
    const driverIcon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="
            background: linear-gradient(135deg, #4F9D5A 0%, #6BB87A 100%);
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 6px 20px rgba(79,157,90,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse-driver 2s infinite;
        ">
            <i class="bi bi-bicycle" style="color: white; font-size: 20px;"></i>
        </div>
        <style>
            @keyframes pulse-driver {
                0%, 100% { box-shadow: 0 6px 20px rgba(79,157,90,0.5); }
                50% { box-shadow: 0 6px 30px rgba(79,157,90,0.8); }
            }
        </style>`,
        iconSize: [48, 48],
        iconAnchor: [24, 24],
        popupAnchor: [0, -24]
    });
    
    const customerIcon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="
            background: linear-gradient(135deg, #E6A23C 0%, #F5C06A 100%);
            width: 40px;
            height: 40px;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(230,162,60,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
        ">
            <i class="bi bi-house-fill" style="color: white; font-size: 16px; transform: rotate(45deg);"></i>
        </div>`,
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });
    
    L.marker([restaurantLat, restaurantLng], {icon: restaurantIcon})
        .addTo(map)
        .bindPopup(`<div style="text-align: center; padding: 8px;">
            <strong style="color: #2F6B3A; font-size: 14px;">${restaurantName}</strong><br>
            <small style="color: #7A847C;">Restaurant</small>
        </div>`)
        .openPopup();
    
    if (driverLat && driverLng) {
        L.marker([driverLat, driverLng], {icon: driverIcon})
            .addTo(map)
            .bindPopup(`<div style="text-align: center; padding: 8px;">
                <strong style="color: #4F9D5A; font-size: 14px;">Your Location</strong><br>
                <small style="color: #7A847C;">Delivery Driver</small>
            </div>`)
            .openPopup();
    }
    
    if (customerLat && customerLng) {
        L.marker([customerLat, customerLng], {icon: customerIcon})
            .addTo(map)
            .bindPopup(`<div style="text-align: center; padding: 8px;">
                <strong style="color: #E6A23C; font-size: 14px;">Customer</strong><br>
                <small style="color: #7A847C;">${customerAddress}</small>
            </div>`);
        
        const routeLine = L.polyline([
            [restaurantLat, restaurantLng],
            [driverLat || restaurantLat, driverLng || restaurantLng],
            [customerLat, customerLng]
        ], {
            color: '#2F6B3A',
            weight: 4,
            opacity: 0.8,
            dashArray: '10, 10',
            lineCap: 'round',
            lineJoin: 'round'
        }).addTo(map);
        
        const bounds = L.latLngBounds([
            [restaurantLat, restaurantLng],
            [driverLat || restaurantLat, driverLng || restaurantLng],
            [customerLat, customerLng]
        ]);
        map.fitBounds(bounds, { padding: [40, 40] });
        
        const distance = calculateDistance(
            driverLat || restaurantLat, driverLng || restaurantLng,
            customerLat, customerLng
        );
        const eta = Math.round(distance * 3);
        
        document.getElementById('distanceValue').textContent = distance.toFixed(1) + ' km';
        document.getElementById('etaValue').textContent = eta + ' min';
    } else {
        map.setView([driverLat || restaurantLat, driverLng || restaurantLng], 14);
    }
    
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }
    
    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }
    
    setTimeout(() => {
        map.invalidateSize();
    }, 300);
});
</script>
@endsection
