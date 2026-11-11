@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Order #{{ $order->order_number ?? 'N/A' }}</h1>
        <p class="page-subtitle">View and manage order details</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Order #{{ $order->order_number ?? 'N/A' }}</h5>
                <div>
                    <span class="badge bg-{{ $order->order_status == 'pending' ? 'warning' : ($order->order_status == 'confirmed' ? 'primary' : ($order->order_status == 'preparing' ? 'info' : ($order->order_status == 'ready' ? 'secondary' : ($order->order_status == 'out_for_delivery' ? 'orange' : ($order->order_status == 'delivered' ? 'success' : 'danger'))))) }}">
                        {{ ucfirst(str_replace('_', ' ', $order->order_status ?? 'pending')) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Food</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items ?? [] as $item)
                            <tr>
                                <td>{{ $item->food->name ?? 'N/A' }}</td>
                                <td>{{ $item->quantity ?? 0 }}</td>
                                <td>${{ number_format($item->price ?? 0, 2) }}</td>
                                <td>${{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No items found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-end">
                    <strong>Total: ${{ number_format($order->total_amount ?? 0, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Customer Info</h6>
            </div>
            <div class="card-body">
                                <p class="mb-1"><strong>Name:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $order->user->phone ?? 'N/A' }}</p>
            </div>
        </div>

         <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Delivery Info</h6>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Address:</strong> {{ $order->address->address ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $order->address->phone ?? 'N/A' }}</p>
                @if($order->address && $order->address->latitude && $order->address->longitude)
                    <div class="mt-3">
                        <strong>Delivery Location Map:</strong>
                         <div id="adminDeliveryMap" class="border" style="height: 250px; border-radius: 8px; margin-top: 8px;"
                              data-lat="{{ $order->address->latitude }}"
                              data-lng="{{ $order->address->longitude }}"
                              data-address="{{ $order->address->address }}"></div>
                    </div>
                    <div class="mt-3">
                        <a href="https://www.google.com/maps/dir/?api=1&origin=11.5754,104.9234&destination={{ $order->address->latitude }},{{ $order->address->longitude }}&travelmode=driving"
                           target="_blank"
                            class="btn btn-primary w-100">
                             <i class="bi bi-google me-1"></i> Open in Google Maps to Start Delivery
                         </a>
                         <small class="text-muted d-block mt-1">Route from restaurant (#21, St 360, Boeung Kengkang) to delivery address</small>
                     </div>
                 @elseif($order->address)
                     <div class="mt-3">
                         <a href="https://www.google.com/maps/dir/?api=1&origin=11.5754,104.9234&destination={{ urlencode($order->address->address . ', ' . $order->address->city) }}&travelmode=driving"
                            target="_blank"
                            class="btn btn-primary w-100">
                            <i class="bi bi-google me-1"></i> Open in Google Maps to Start Delivery
                        </a>
                    </div>
                @endif
            </div>
        </div>

<div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Update Status</h6>
            </div>
            <div class="card-body">
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" id="statusUpdateForm">
                        @csrf
                        <div class="mb-3">
                            <select class="form-select" name="order_status" id="orderStatusSelect">
                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="preparing" {{ $order->order_status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                <option value="ready" {{ $order->order_status == 'ready' ? 'selected' : '' }}>Ready</option>
                                <option value="out_for_delivery" {{ $order->order_status == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                         <button type="button" class="btn btn-outline-success w-100 mt-2" data-receipt-url="{{ route('admin.orders.receipt', $order) }}" data-bs-toggle="modal" data-bs-target="#receiptModal">
                              <i class="bi bi-receipt me-1"></i> View Receipt
                          </button>
                  </div>
          </div>
         </div>
     </div>
 </div>
 @endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.getElementById('adminDeliveryMap');
    if (mapContainer) {
        const lat = parseFloat(mapContainer.dataset.lat);
        const lng = parseFloat(mapContainer.dataset.lng);
        const address = mapContainer.dataset.address;

        if (lat && lng) {
            const map = L.map('adminDeliveryMap', {
                center: [lat, lng],
                zoom: 15,
                zoomControl: true,
                attributionControl: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup(address)
                .openPopup();

            map.invalidateSize();
        }
    }

    // AJAX status update for order detail page
    const statusForm = document.getElementById('statusUpdateForm');
    const statusSelect = document.getElementById('orderStatusSelect');
    if (statusForm && statusSelect) {
        const originalStatus = statusSelect.value;

        statusForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const newStatus = statusSelect.value;
            if (newStatus === originalStatus) return;

            const submitBtn = statusForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';

            fetch(statusForm.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': statusForm.querySelector('[name=_token]').value,
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    '_token': statusForm.querySelector('[name=_token]').value,
                    'order_status': newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status badge in the header
                    const badge = document.querySelector('.card-header .badge');
                    if (badge) {
                        const statusLabels = {
                            'pending': 'Pending',
                            'confirmed': 'Confirmed',
                            'preparing': 'Preparing',
                            'ready': 'Ready',
                            'out_for_delivery': 'Out for Delivery',
                            'delivered': 'Delivered',
                            'cancelled': 'Cancelled'
                        };
                        const statusClasses = {
                            'pending': 'warning',
                            'confirmed': 'primary',
                            'preparing': 'info',
                            'ready': 'secondary',
                            'out_for_delivery': 'orange',
                            'delivered': 'success',
                            'cancelled': 'danger'
                        };
                        badge.className = `badge bg-${statusClasses[newStatus] || 'warning'}`;
                        badge.textContent = statusLabels[newStatus] || newStatus;
                    }
                    statusSelect.value = newStatus;
                    showToast('Order status updated successfully', 'success');
                } else {
                    statusSelect.value = originalStatus;
                    showToast(data.message || 'Failed to update status', 'error');
                }
            })
            .catch(() => {
                statusSelect.value = originalStatus;
                showToast('Failed to update status', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Update Status';
            });
        });
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        toast.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
        toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}-fill me-2"></i>${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
});
</script>
@endsection
