@extends('layouts.customer')

@section('title', 'Shopping Cart')

@section('content')
<section class="customer-section">
    <div class="container">
        <div class="page-header">
            <div>
                <h1 class="page-title">Shopping Cart</h1>
                <p class="page-subtitle">Review your items before checkout</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($cart->items->count() > 0)
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="data-card">
                        <div class="data-card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cart->items as $item)
                                            @php
                                                $food = $item->food;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        @if($food)
                                                            <a href="{{ route('foods.show', $food->id) }}" class="d-flex align-items-center gap-3 text-decoration-none">
                                                                <img src="{{ $food->image ? asset('storage/' . $food->image) : asset('images/placeholder-food.jpg') }}" alt="{{ $food->name }}" class="img-thumb-md">
                                                                <div>
                                                                    <h6 class="mb-0 fw-semibold text-dark">{{ $food->name }}</h6>
                                                                    @php
                                                                        $rating = $food->reviews->avg('rating') ?? 0;
                                                                        $fullStars = floor($rating);
                                                                        $halfStar = $rating - $fullStars >= 0.5;
                                                                    @endphp
                                                                    <div class="d-flex align-items-center gap-1">
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            @if($i <= $fullStars)
                                                                                <i class="bi bi-star-fill text-warning" style="font-size: 0.7rem;"></i>
                                                                            @elseif($i == $fullStars + 1 && $halfStar)
                                                                                <i class="bi bi-star-half text-warning" style="font-size: 0.7rem;"></i>
                                                                            @else
                                                                                <i class="bi bi-star text-muted" style="font-size: 0.7rem;"></i>
                                                                            @endif
                                                                        @endfor
                                                                        <span class="text-muted small">({{ $food->reviews->count() }})</span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        @else
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div class="img-thumb-md d-flex align-items-center justify-content-center bg-light">
                                                                    <i class="bi bi-image text-muted"></i>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-0 fw-semibold text-muted">Food no longer available</h6>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="fw-semibold">${{ number_format($item->price, 2) }}</td>
                                                <td>
                                                    <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center gap-2">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="quantity-input">
                                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="this.nextElementSibling.stepDown()">-</button>
                                                            <input type="number" class="form-control form-control-sm text-center" name="quantity" value="{{ $item->quantity }}" min="1" max="99" onchange="this.form.submit()" style="width:50px;">
                                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td class="fw-semibold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                                <td>
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Remove"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#removeCartItemModal"
                                                        data-id="{{ $item->id }}"
                                                        data-name="{{ $food?->name ?? 'this item' }}"
                                                        data-qty="{{ $item->quantity }}"
                                                        data-total="{{ number_format($item->price * $item->quantity, 2) }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span>${{ number_format($cart->total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Delivery Fee</span>
                                <span>${{ number_format(2.00, 2) }}</span>
                            </div>
                            @php $discount = 0; @endphp
                            @if($discount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Discount</span>
                                    <span>-${{ number_format($discount, 2) }}</span>
                                </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold text-primary">${{ number_format($cart->total + 2.00, 2) }}</span>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 py-2">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-cart-x empty-state-icon"></i>
                <p class="empty-state-title">Your cart is empty</p>
                <p class="empty-state-text">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('foods.index') }}" class="btn btn-primary mt-3">Browse Foods</a>
            </div>
        @endif
    </div>
</section>

<!-- =========================================================
      REMOVE ITEM CONFIRMATION MODAL
      Kept outside .data-card so the card's overflow cannot clip
      or shift the fixed-position modal.
========================================================= -->

<div class="modal fade" id="removeCartItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>

                <p class="mt-3 mb-1">Are you sure you want to remove</p>

                <p class="fw-semibold mb-1" id="removeCartItemName">this item</p>

                <p class="text-muted small mb-0" id="removeCartItemMeta"></p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    Keep Item
                </button>

                <form action="{{ route('cart.remove') }}" method="POST" id="removeCartItemForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="cart_item_id" id="removeCartItemId" value="">

                    <button type="submit" class="btn btn-danger btn-sm" id="removeCartItemSubmit">
                        <i class="bi bi-trash me-1"></i> Yes, Remove
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const modal = document.getElementById('removeCartItemModal');

        const form = document.getElementById('removeCartItemForm');

        const idInput = document.getElementById('removeCartItemId');

        const nameEl = document.getElementById('removeCartItemName');

        const metaEl = document.getElementById('removeCartItemMeta');

        const submitBtn = document.getElementById('removeCartItemSubmit');

        if (!modal || !form || !idInput || !nameEl || !metaEl) {
            return;
        }

        modal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            if (!button) {
                return;
            }

            const name = button.getAttribute('data-name') || 'this item';

            const qty = button.getAttribute('data-qty') || '1';

            const total = button.getAttribute('data-total') || '0.00';

            nameEl.textContent = name;

            idInput.value = button.getAttribute('data-id') || '';

            metaEl.textContent = 'Quantity ' + qty + ' · Subtotal $' + total;

            if (submitBtn) {
                submitBtn.disabled = false;

                submitBtn.innerHTML = '<i class="bi bi-trash me-1"></i> Yes, Remove';
            }

        });

        // guard against a double submit while the request is in flight
        form.addEventListener('submit', function () {

            if (!submitBtn) {
                return;
            }

            submitBtn.disabled = true;

            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Removing';

        });

    });
</script>
@endsection
