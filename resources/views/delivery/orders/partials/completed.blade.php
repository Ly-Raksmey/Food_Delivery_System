@php
    $emptyIcon = 'bi-check-circle';
    $emptyText = 'No completed orders yet';
    $emptySub = 'Delivered orders will show up here for review.';
    $badgeClass = 'delivered';
    $badgeLabel = 'Delivered';
    $accentClass = 'delivered';
@endphp

<div class="delivery-orders-list-view" data-view-active="list">
    @forelse ($orders as $delivery)
        @php
            $order = $delivery->order;
            $customer = $order->user ?? null;
            $address = $order->address ?? null;
            $items = $order->items ?? collect();
            $primaryItem = $items->first();
        @endphp
        <div class="delivery-list-card">
            <div class="delivery-list-row">
                <div class="delivery-list-main">
                    <div class="delivery-list-thumb">
                        @if ($primaryItem && $primaryItem->food && $primaryItem->food->image)
                            <img src="{{ asset('storage/' . $primaryItem->food->image) }}" alt="{{ $primaryItem->food->name ?? 'Item' }}">
                        @else
                            <div class="placeholder"><i class="bi bi-image"></i></div>
                        @endif
                    </div>
                    <div class="delivery-list-info">
                        <div class="delivery-list-order-id">#{{ $order->order_number ?? 'N/A' }}</div>
                        <div class="delivery-list-meta">
                            <span><i class="bi bi-clock"></i> {{ $order->created_at?->diffForHumans() ?? '' }}</span>
                            <span><i class="bi bi-person"></i> <span class="name">{{ $customer->name ?? 'Unknown' }}</span></span>
                            <span><i class="bi bi-telephone"></i> <span class="phone">{{ $customer->phone ?? 'N/A' }}</span></span>
                        </div>
                    </div>
                </div>
                <div class="delivery-list-right">
                    <div class="delivery-list-address">
                        <i class="bi bi-geo-alt" style="color: var(--premium-green); margin-right: 4px;"></i> {{ $address->address ?? 'N/A' }}
                    </div>
                    <div class="delivery-list-status {{ $badgeClass }}">
                        <span class="delivery-list-status-dot"></span> {{ $badgeLabel }}
                    </div>
                    <div class="delivery-list-total">${{ number_format($order->total_amount ?? 0, 2) }}</div>
                    <div class="delivery-list-actions">
                        <a href="{{ route('delivery.orders.show', $order) }}" class="delivery-outline-btn">View Details</a>
                        <button type="button" class="delivery-outline-btn" data-receipt-url="{{ route('delivery.orders.receipt', $order) }}" data-bs-toggle="modal" data-bs-target="#receiptModal">
                            <i class="bi bi-receipt"></i> Receipt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="delivery-empty-state">
            <div class="empty-icon"><i class="bi {{ $emptyIcon }}"></i></div>
            <h3>{{ $emptyText }}</h3>
            <p>{{ $emptySub }}</p>
        </div>
    @endforelse
</div>

<div class="delivery-orders-card-view" data-view-active="card" style="display:none;">
    @forelse ($orders as $delivery)
        @php
            $order = $delivery->order;
            $customer = $order->user ?? null;
            $address = $order->address ?? null;
            $items = $order->items ?? collect();
        @endphp
        <div class="delivery-card-item">
            <div class="delivery-card-accent {{ $accentClass }}"></div>
            <div class="delivery-card-body">
                <div class="delivery-card-top">
                    <div>
                        <div class="delivery-card-title">#{{ $order->order_number ?? 'N/A' }}</div>
                        <div class="delivery-card-meta">
                            <span><i class="bi bi-clock"></i> {{ $order->created_at?->diffForHumans() ?? '' }}</span>
                            <span><i class="bi bi-person"></i> <span class="name">{{ $customer->name ?? 'Unknown' }}</span></span>
                            <span><i class="bi bi-telephone"></i> <span class="phone">{{ $customer->phone ?? 'N/A' }}</span></span>
                        </div>
                    </div>
                    <div class="delivery-list-status {{ $badgeClass }}">
                        <span class="delivery-list-status-dot"></span> {{ $badgeLabel }}
                    </div>
                </div>

                <div class="delivery-card-items">
                    @forelse ($items as $item)
                        <div class="delivery-card-item-row">
                            <div class="delivery-card-item-thumb">
                                @if ($item->food && $item->food->image)
                                    <img src="{{ asset('storage/' . $item->food->image) }}" alt="{{ $item->food->name ?? 'Item' }}">
                                @else
                                    <div class="placeholder"><i class="bi bi-image"></i></div>
                                @endif
                            </div>
                            <div class="delivery-card-item-name">{{ $item->food->name ?? 'Unknown Item' }}</div>
                            <div class="delivery-card-item-qty">Qty: {{ $item->quantity ?? 1 }}</div>
                            <div class="delivery-card-item-price">${{ number_format(($item->price ?? 0) * ($item->quantity ?? 1), 2) }}</div>
                        </div>
                    @empty
                        <div class="delivery-card-item-name" style="color: var(--premium-muted);">No items found</div>
                    @endforelse
                </div>

                <div class="delivery-card-footer">
                    <div class="delivery-card-address">
                        <i class="bi bi-geo-alt"></i> {{ $address->address ?? 'N/A' }}
                    </div>
                    <div class="delivery-card-actions">
                        <a href="{{ route('delivery.orders.show', $order) }}" class="delivery-outline-btn">View Details</a>
                        <button type="button" class="delivery-outline-btn" data-receipt-url="{{ route('delivery.orders.receipt', $order) }}" data-bs-toggle="modal" data-bs-target="#receiptModal">
                            <i class="bi bi-receipt"></i> Receipt
                        </button>
                    </div>
                </div>
            </div>
            <div class="delivery-card-top" style="padding: 0 20px 16px; align-items: flex-end;">
                <div></div>
                <div class="delivery-card-price">${{ number_format($order->total_amount ?? 0, 2) }}</div>
            </div>
        </div>
    @empty
        <div class="delivery-empty-state">
            <div class="empty-icon"><i class="bi {{ $emptyIcon }}"></i></div>
            <h3>{{ $emptyText }}</h3>
            <p>{{ $emptySub }}</p>
        </div>
    @endforelse
</div>
