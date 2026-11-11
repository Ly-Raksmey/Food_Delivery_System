<div class="receipt-wrapper">
    <div class="receipt-header text-center py-4 border-bottom">
        <h4 class="mb-1 fw-bold"><i class="bi bi-basket2-fill me-2" style="color: #ff6b6b;"></i>FoodDelivery</h4>
        <p class="text-muted mb-0 small">Order Receipt</p>
    </div>

    <div class="receipt-body p-4">
        @php
            $statusBadge = match($order->order_status) {
                'pending' => 'warning',
                'confirmed' => 'primary',
                'preparing' => 'info',
                'ready' => 'secondary',
                'out_for_delivery' => 'orange',
                'delivered' => 'success',
                default => 'danger',
            };
            $paymentBadge = $order->payment_status == 'paid' ? 'success' : 'warning';
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-6">
                <p class="text-muted small mb-1">Order Number</p>
                <p class="fw-semibold mb-0">#{{ $order->order_number ?? 'N/A' }}</p>
            </div>
            <div class="col-6">
                <p class="text-muted small mb-1">Date</p>
                <p class="fw-semibold mb-0">{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'N/A' }}</p>
            </div>
            <div class="col-6">
                <p class="text-muted small mb-1">Order Status</p>
                <span class="badge bg-{{ $statusBadge }}">
                    {{ ucfirst(str_replace('_', ' ', $order->order_status ?? 'pending')) }}
                </span>
            </div>
            <div class="col-6">
                <p class="text-muted small mb-1">Payment Status</p>
                <span class="badge bg-{{ $paymentBadge }}">
                    {{ ucfirst($order->payment_status ?? 'pending') }}
                </span>
            </div>
            <div class="col-6">
                <p class="text-muted small mb-1">Payment Method</p>
                <p class="fw-semibold mb-0 text-capitalize">{{ str_replace('_', ' ', $order->payment_method ?? 'N/A') }}</p>
            </div>
        </div>

        @if($order->user)
        <div class="card border mb-4">
            <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-semibold small text-uppercase text-muted">Customer Information</h6>
            </div>
            <div class="card-body py-3">
                <div class="row g-2">
                    <div class="col-6">
                        <p class="text-muted small mb-1">Name</p>
                        <p class="fw-semibold mb-0">{{ $order->user->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">Email</p>
                        <p class="fw-semibold mb-0">{{ $order->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">Phone</p>
                        <p class="fw-semibold mb-0">{{ $order->user->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($order->address)
        <div class="card border mb-4">
            <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-semibold small text-uppercase text-muted">Delivery Address</h6>
            </div>
            <div class="card-body py-3">
                <div class="row g-2">
                    <div class="col-12">
                        <p class="text-muted small mb-1">Address</p>
                        <p class="fw-semibold mb-0">{{ $order->address->address ?? 'N/A' }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">City</p>
                        <p class="fw-semibold mb-0">{{ $order->address->city ?? 'N/A' }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">Phone</p>
                        <p class="fw-semibold mb-0">{{ $order->address->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="card border mb-4">
            <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-semibold small text-uppercase text-muted">Order Items</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end pe-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items ?? [] as $item)
                            <tr>
                                <td class="ps-3">{{ $item->food->name ?? 'Unknown Item' }}</td>
                                <td class="text-center">{{ $item->quantity ?? 0 }}</td>
                                <td class="text-end">${{ number_format($item->price ?? 0, 2) }}</td>
                                <td class="text-end pe-3">${{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No items found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border mb-4">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-semibold">${{ number_format($order->subtotal ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Delivery Fee</span>
                    <span class="fw-semibold">${{ number_format($order->delivery_fee ?? 0, 2) }}</span>
                </div>
                @if(($order->discount_amount ?? 0) > 0)
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</span>
                    <span class="fw-semibold">-${{ number_format($order->discount_amount ?? 0, 2) }}</span>
                </div>
                @endif
                <hr class="my-3">
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Total</span>
                    <span class="fw-bold receipt-total text-primary">${{ number_format($order->total_amount ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        @if($delivery)
        <div class="card border mb-4">
            <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-semibold small text-uppercase text-muted">Delivery Information</h6>
            </div>
            <div class="card-body py-3">
                <div class="row g-2">
                    <div class="col-6">
                        <p class="text-muted small mb-1">Delivery Status</p>
                        <p class="fw-semibold mb-0">{{ ucfirst(str_replace('_', ' ', $delivery->status ?? 'pending')) }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">Delivery Staff</p>
                        <p class="fw-semibold mb-0">{{ $delivery->deliveryStaff->name ?? 'Unassigned' }}</p>
                    </div>
                    @if($delivery->assigned_at)
                    <div class="col-6">
                        <p class="text-muted small mb-1">Assigned At</p>
                        <p class="fw-semibold mb-0">{{ \Carbon\Carbon::parse($delivery->assigned_at)->format('d M Y, h:i A') }}</p>
                    </div>
                    @endif
                    @if($delivery->picked_up_at)
                    <div class="col-6">
                        <p class="text-muted small mb-1">Picked Up At</p>
                        <p class="fw-semibold mb-0">{{ \Carbon\Carbon::parse($delivery->picked_up_at)->format('d M Y, h:i A') }}</p>
                    </div>
                    @endif
                    @if($delivery->delivered_at)
                    <div class="col-6">
                        <p class="text-muted small mb-1">Delivered At</p>
                        <p class="fw-semibold mb-0">{{ \Carbon\Carbon::parse($delivery->delivered_at)->format('d M Y, h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($order->notes)
        <div class="card border mb-4">
            <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-semibold small text-uppercase text-muted">Notes</h6>
            </div>
            <div class="card-body py-3">
                <p class="mb-0 text-muted">{{ $order->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="receipt-footer p-3 border-top bg-light text-center no-print">
        <div class="d-flex gap-2 justify-content-center">
            <button
                type="button"
                class="btn btn-success btn-sm"
                data-file="{{ 'receipt-' . ($order->order_number ?? $order->id) . '.png' }}"
                onclick="downloadReceiptImage(this)">
                <i class="bi bi-image me-1"></i> Save Image
            </button>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="bi bi-printer me-1"></i> Print Receipt
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                <i class="bi bi-x-lg me-1"></i> Close
            </button>
        </div>
    </div>
</div>
