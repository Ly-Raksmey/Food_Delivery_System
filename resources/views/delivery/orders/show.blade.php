@extends('layouts.delivery')

@section('title', 'Order #' . ($order->order_number ?? 'Details'))
@section('topbar_title', 'Order Details')
@section('topbar_subtitle', '#' . ($order->order_number ?? 'N/A'))

@section('content')
<a href="{{ route('delivery.orders.index') }}" class="delivery-btn delivery-btn-ghost delivery-btn-sm mb-3">
    <i class="bi bi-arrow-left me-1"></i> Back to Orders
</a>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="delivery-card">
            <div class="delivery-card-header">
                <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Order Information</h5>
                @php
                    $badgeClass = match($delivery->status) {
                        'assigned' => 'pending',
                        'accepted' => 'preparing',
                        'picked_up' => 'ready',
                        'out_for_delivery' => 'out',
                        'delivered' => 'delivered',
                        default => 'pending',
                    };
                @endphp
                <span class="delivery-badge delivery-badge-{{ $badgeClass }} fs-6">{{ ucfirst(str_replace('_', ' ', $delivery->status)) }}</span>
            </div>
            <div class="delivery-card-body">
                <div class="delivery-info-row">
                    <span class="text-muted">Order Number</span>
                    <span class="fw-semibold">#{{ $order->order_number ?? 'N/A' }}</span>
                </div>
                <div class="delivery-info-row">
                    <span class="text-muted">Date</span>
                    <span>{{ $order->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</span>
                </div>
                <div class="delivery-info-row">
                    <span class="text-muted">Payment Method</span>
                    <span class="text-capitalize">{{ str_replace('_', ' ', $order->payment_method ?? 'N/A') }}</span>
                </div>
                <div class="delivery-info-row">
                    <span class="text-muted">Payment Status</span>
                    <span class="delivery-badge {{ $order->payment_status === 'paid' ? 'delivery-badge-delivered' : 'delivery-badge-pending' }}">
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                </div>
                @if ($order->notes)
                    <div class="delivery-info-row">
                        <span class="text-muted">Notes</span>
                        <span>{{ $order->notes }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="delivery-card">
            <div class="delivery-card-header">
                <h5 class="mb-0"><i class="bi bi-basket me-2"></i>Order Items</h5>
            </div>
            <div class="delivery-card-body p-0">
                <div class="delivery-table-wrap" style="border-radius: 0; border: none;">
                    <table class="delivery-table mb-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->items ?? [] as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($item->food && $item->food->image)
                                                <img src="{{ asset('storage/' . $item->food->image) }}" alt="{{ $item->food->name }}" class="rounded" style="width:36px;height:36px;object-fit:cover;">
                                            @else
                                                <div class="rounded d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#EAF4E5;"><i class="bi bi-image text-muted"></i></div>
                                            @endif
                                            <span class="fw-semibold">{{ $item->food->name ?? 'Unknown Item' }}</span>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->price ?? 0, 2) }}</td>
                                    <td class="text-center">{{ $item->quantity ?? 0 }}</td>
                                    <td class="text-end fw-semibold">${{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">No items found</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Total</td>
                                <td class="text-end fw-bold">${{ number_format($order->total_amount ?? 0, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="delivery-card">
            <div class="delivery-card-header">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Customer Information</h5>
            </div>
            <div class="delivery-card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="delivery-avatar-circle me-3">{{ $order->user ? substr($order->user->name, 0, 1) : '?' }}</div>
                    <div>
                        <div class="fw-semibold">{{ $order->user->name ?? 'Unknown' }}</div>
                        <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                    </div>
                </div>
                <div class="delivery-info-row">
                    <span class="text-muted"><i class="bi bi-telephone me-1"></i> Phone</span>
                    <span class="fw-semibold">{{ $order->user->phone ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="delivery-card">
            <div class="delivery-card-header">
                <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Delivery Address</h5>
            </div>
            <div class="delivery-card-body">
                @if ($order->address)
                    <p class="mb-2 fw-semibold">{{ $order->address->address }}</p>
                    <p class="text-muted mb-2">{{ $order->address->city ?? '' }}</p>
                    @if($order->address->latitude && $order->address->longitude)
                        <div class="delivery-map mt-3" id="deliveryMap" style="height: 220px; border-radius: 16px;"
                             data-lat="{{ $order->address->latitude }}"
                             data-lng="{{ $order->address->longitude }}"
                             data-address="{{ $order->address->address }}"></div>
                        <a href="https://www.google.com/maps/dir/?api=1&origin=11.5754,104.9234&destination={{ $order->address->latitude }},{{ $order->address->longitude }}&travelmode=driving" target="_blank" class="delivery-btn delivery-btn-primary w-100 mt-3">
                            <i class="bi bi-google me-1"></i> Open in Google Maps
                        </a>
                    @else
                        <a href="https://www.google.com/maps/dir/?api=1&origin=11.5754,104.9234&destination={{ urlencode($order->address->address . ', ' . $order->address->city) }}&travelmode=driving" target="_blank" class="delivery-btn delivery-btn-primary w-100 mt-2">
                            <i class="bi bi-google me-1"></i> Open in Google Maps
                        </a>
                    @endif
                @else
                    <p class="text-muted mb-0">No address provided</p>
                @endif
            </div>
        </div>

        <div class="delivery-card">
            <div class="delivery-card-header">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
            </div>
            <div class="delivery-card-body">
                @if ($delivery->status === 'assigned')
                    <form method="POST" action="{{ route('delivery.orders.accept', $order) }}" class="mb-2">
                        @csrf
                        <button type="submit" class="delivery-btn delivery-btn-primary w-100">
                            <i class="bi bi-check-lg"></i> Accept Delivery
                        </button>
                    </form>
                @endif
                @if ($delivery->status === 'accepted')
                    <form method="POST" action="{{ route('delivery.orders.pickup', $order) }}" class="mb-2">
                        @csrf
                        <button type="submit" class="delivery-btn delivery-btn-primary w-100">
                            <i class="bi bi-bag-check"></i> Pick Up Order
                        </button>
                    </form>
                @endif
                @if ($delivery->status === 'picked_up')
                    <form method="POST" action="{{ route('delivery.orders.outForDelivery', $order) }}" class="mb-2">
                        @csrf
                        <button type="submit" class="delivery-btn delivery-btn-primary w-100">
                            <i class="bi bi-truck"></i> Start Delivery
                        </button>
                    </form>
                @endif
                @if ($delivery->status === 'out_for_delivery')
                    @if ($order->payment_method === 'khqr' && $order->payment_status !== 'paid')
                        <button type="button" class="delivery-btn delivery-btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#khqrModal" data-total="{{ $order->total_amount ?? 0 }}" data-order-id="{{ $order->id }}">
                            <i class="bi bi-check-circle me-1"></i> Mark Delivered & Collect Payment
                        </button>
                    @else
                        <form method="POST" action="{{ route('delivery.orders.delivered', $order) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="delivery-btn delivery-btn-success w-100">
                                <i class="bi bi-check-circle"></i> Mark Delivered & Print Receipt
                            </button>
                        </form>
                    @endif
                @endif
                @if ($delivery->status === 'delivered')
                    <button type="button" class="delivery-btn delivery-btn-ghost w-100 mb-2" data-receipt-url="{{ route('delivery.orders.receipt', $order) }}" data-bs-toggle="modal" data-bs-target="#receiptModal">
                        <i class="bi bi-receipt me-1"></i> View Receipt
                    </button>
                @endif
                @if ($order->user && $order->user->phone)
                    <a href="tel:{{ $order->user->phone }}" class="delivery-btn delivery-btn-ghost w-100 mb-2">
                        <i class="bi bi-telephone me-1"></i> Call Customer
                    </a>
                @endif
                @if ($order->payment_status !== 'paid')
                    <form method="POST" action="{{ route('delivery.orders.markPaid', $order) }}">
                        @csrf
                        <button type="submit" class="delivery-btn delivery-btn-success w-100">
                            <i class="bi bi-cash me-1"></i> Mark Payment Received
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.getElementById('deliveryMap');
    if (mapContainer) {
        const lat = parseFloat(mapContainer.dataset.lat);
        const lng = parseFloat(mapContainer.dataset.lng);
        const address = mapContainer.dataset.address;

        if (lat && lng) {
            const map = L.map('deliveryMap', {
                center: [lat, lng],
                zoom: 15,
                zoomControl: true,
                attributionControl: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const greenIcon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background: #2F6B3A; width: 32px; height: 32px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.3);"><i class="bi bi-geo-alt" style="color: white; font-size: 14px; display: flex; justify-content: center; align-items: center; height: 100%; transform: rotate(45deg);"></i></div>',
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            });

            L.marker([lat, lng], {icon: greenIcon}).addTo(map)
                .bindPopup(address)
                .openPopup();

            map.invalidateSize();
        }
    }
});
</script>
@endsection
