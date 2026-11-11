@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')

<style>
/* =========================================================
   RESET
========================================================= */

html,
body {
    margin: 0;
    padding: 0;
    background: #f7f8f1;
}


/* =========================================================
   CHECKOUT PAGE
========================================================= */

.checkout-page {
    width: 100%;
    background: #f7f8f1;

    /* Space for fixed navbar */
    padding-top: 12px;

    /* Only small space before footer */
    padding-bottom: 35px;

    /* IMPORTANT:
       Do NOT use min-height: 100vh here.
       It creates the huge empty space.
    */
    min-height: auto !important;
    height: auto !important;
}


.checkout-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}


/* =========================================================
   PAGE HEADER
========================================================= */

.checkout-page .page-header {
    margin-bottom: 28px;
}

.checkout-page .page-title {
    margin: 0;
    color: #173f25;
    font-size: 38px;
    line-height: 1.2;
    font-weight: 700;
}

.checkout-page .page-subtitle {
    margin: 6px 0 0;
    color: #718075;
    font-size: 16px;
}


/* =========================================================
   CARDS
========================================================= */

.checkout-page .card {
    background: #ffffff;
    border: 1px solid #e5eadf;
    border-radius: 18px;
    box-shadow: 0 8px 25px rgba(28, 65, 40, 0.06);
}

.checkout-page .card-body {
    padding: 24px;
}


/* =========================================================
   CARD TITLES
========================================================= */

.checkout-page h5 {
    color: #173f25;
}

.checkout-page h6 {
    color: #234b30;
}


/* =========================================================
   FORM INPUTS
========================================================= */

.checkout-page .form-label {
    color: #304238;
    font-weight: 500;
}

.checkout-page .form-control {
    min-height: 48px;
    border: 1px solid #dce3d8;
    border-radius: 10px;
    background: #ffffff;
    color: #26352b;
}

.checkout-page textarea.form-control {
    min-height: 90px;
}

.checkout-page .form-control:focus {
    border-color: #28733d;
    box-shadow: 0 0 0 3px rgba(40, 115, 61, 0.10);
}


/* =========================================================
   ADDRESS / PAYMENT LIST
========================================================= */

.checkout-page .list-group-item {
    border: 1px solid #e0e6dc;
    padding: 15px 18px;
    background: #ffffff;
    color: #26352b;
    transition: all 0.2s ease;
}

.checkout-page .list-group-item:hover {
    background: #f7faf5;
}

.checkout-page .list-group-item.active {
    background: #f0f7ed;
    border-color: #b9d7b8;
    color: #173f25;
}

.checkout-page .list-group-item + .list-group-item {
    border-top-width: 0;
}


/* =========================================================
   CHECKBOX / RADIO
========================================================= */

.checkout-page .form-check-input {
    margin-top: 3px;
}

.checkout-page .form-check-input:checked {
    background-color: #286b3b;
    border-color: #286b3b;
}


/* =========================================================
   BUTTON
========================================================= */

.checkout-page .btn-primary {
    background: #286b3b;
    border-color: #286b3b;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.25s ease;
}

.checkout-page .btn-primary:hover {
    background: #1e572f;
    border-color: #1e572f;
    transform: translateY(-1px);
    box-shadow: 0 7px 18px rgba(40, 107, 59, 0.20);
}


/* =========================================================
   ORDER SUMMARY
========================================================= */

.checkout-page .order-summary-card {
    position: sticky;
    top: 105px;
}

.checkout-page .text-primary {
    color: #286b3b !important;
}


/* =========================================================
   MAP
========================================================= */

.checkout-page #addressMap {
    width: 100%;
    height: 300px;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    overflow: hidden;
}

.checkout-page [id^="map-"] {
    overflow: hidden;
}


/* =========================================================
   ALERT
========================================================= */

.checkout-page .alert {
    border-radius: 12px;
}


/* =========================================================
   EMPTY CART
========================================================= */

.checkout-page .empty-state {
    background: #ffffff;
    border: 1px solid #e5eadf;
    border-radius: 18px;
    padding: 50px 30px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(28, 65, 40, 0.06);
}

.checkout-page .empty-state-icon {
    display: block;
    margin-bottom: 15px;
    font-size: 55px;
    color: #286b3b;
}

.checkout-page .empty-state-title {
    margin: 0;
    color: #173f25;
    font-size: 20px;
    font-weight: 600;
}


/* =========================================================
   HR
========================================================= */

.checkout-page hr {
    margin: 18px 0;
    border-color: #e5e9e2;
    opacity: 1;
}


/* =========================================================
   FOOTER
========================================================= */

.checkout-page + footer {
    margin-top: 0 !important;
}


/* =========================================================
   IMPORTANT:
   Override common layout classes that can create
   unnecessary full-screen height.
========================================================= */

.checkout-page.customer-section,
.customer-section.checkout-page {
    min-height: auto !important;
    height: auto !important;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 991px) {

    .checkout-page {
        padding-top: 100px;
        padding-bottom: 30px;
    }

    .checkout-page .order-summary-card {
        position: static;
    }

    .checkout-page .page-title {
        font-size: 32px;
    }
}


@media (max-width: 576px) {

    .checkout-page {
        padding-top: 90px;
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 25px;
    }

    .checkout-page .card-body {
        padding: 18px;
    }

    .checkout-page .page-title {
        font-size: 28px;
    }

    .checkout-page .page-subtitle {
        font-size: 14px;
    }
}
</style>


<!-- =========================================================
     CHECKOUT
========================================================= -->

<section class="checkout-page">

    <div class="container checkout-container">

        <!-- =================================================
             PAGE HEADER
        ================================================== -->

        <div class="page-header">

            <h1 class="page-title">
                Checkout
            </h1>

            <p class="page-subtitle">
                Complete your order
            </p>

        </div>


        <!-- =================================================
             EMPTY CART
        ================================================== -->

        @if($cart->items->isEmpty())

            <div class="empty-state">

                <i class="bi bi-cart-x empty-state-icon"></i>

                <p class="empty-state-title">
                    Your cart is empty
                </p>

                <a href="{{ route('foods.index') }}"
                   class="btn btn-primary mt-3">

                    Browse Foods

                </a>

            </div>

        @else


            <!-- =================================================
                 ERROR
            ================================================== -->

            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show"
                     role="alert">

                    <i class="bi bi-exclamation-circle-fill me-1"></i>

                    {{ session('error') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <!-- =================================================
                 SUCCESS
            ================================================== -->

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show"
                     role="alert">

                    <i class="bi bi-check-circle-fill me-1"></i>

                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <!-- =================================================
                 COUPON
            ================================================== -->

            <div class="card mb-4">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">

                        <i class="bi bi-ticket-perforated me-2 text-primary"></i>

                        Coupon

                    </h5>


                    @if($coupon)

                        <div class="alert alert-success
                                    d-flex
                                    justify-content-between
                                    align-items-center
                                    mb-0">

                            <div>

                                <strong>
                                    {{ $coupon->code }}
                                </strong>

                                <small class="d-block text-muted">

                                    @if($coupon->type === 'percentage')

                                        {{ $coupon->value }}% off

                                        @if($coupon->maximum_discount)

                                            (max
                                            ${{ number_format($coupon->maximum_discount, 2) }})

                                        @endif

                                    @else

                                        ${{ number_format($coupon->value, 2) }}
                                        off

                                    @endif

                                </small>

                            </div>


                            <form action="{{ route('coupon.remove') }}"
                                  method="POST">

                                @csrf

                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger">

                                    Remove

                                </button>

                            </form>

                        </div>

                    @else

                        <form action="{{ route('coupon.apply') }}"
                              method="POST">

                            @csrf

                            <div class="input-group">

                                <input type="text"
                                       name="code"
                                       class="form-control"
                                       placeholder="Enter coupon code"
                                       value="{{ old('code') }}"
                                       required>

                                <button type="submit"
                                        class="btn btn-primary">

                                    Apply

                                </button>

                            </div>

                        </form>

                    @endif

                </div>

            </div>


            <!-- =================================================
                 CHECKOUT FORM
            ================================================== -->

            <form action="{{ route('checkout.store') }}"
                  method="POST"
                  id="checkoutForm">

                @csrf


                <input type="hidden"
                       name="khqr_payment_type"
                       id="khqrPaymentType"
                       value="{{ old('khqr_payment_type', 'delivery') }}">


                <div class="row g-4">


                    <!-- =================================================
                         LEFT
                    ================================================== -->

                    <div class="col-lg-8">


                        <!-- =============================================
                             DELIVERY ADDRESS
                        ============================================== -->

                        <div class="card mb-4">

                            <div class="card-body">

                                <h5 class="fw-bold mb-3">

                                    <i class="bi bi-geo-alt me-2 text-primary"></i>

                                    Delivery Address

                                </h5>


                                <!-- Address Type -->

                                <div class="list-group mb-3">

                                    <!-- Saved -->

                                    <label class="list-group-item d-flex gap-2">

                                        <input class="form-check-input flex-shrink-0"
                                               type="radio"
                                               name="address_type"
                                               value="saved"
                                               {{ old('address_type', 'saved') == 'saved' ? 'checked' : '' }}
                                               required>

                                        <div>

                                            <span class="fw-semibold">
                                                Use saved address
                                            </span>

                                        </div>

                                    </label>


                                    <!-- New -->

                                    <label class="list-group-item d-flex gap-2">

                                        <input class="form-check-input flex-shrink-0"
                                               type="radio"
                                               name="address_type"
                                               value="new"
                                               {{ old('address_type') == 'new' ? 'checked' : '' }}>

                                        <div>

                                            <span class="fw-semibold">
                                                Add new address for this order
                                            </span>

                                        </div>

                                    </label>

                                </div>


                                @error('address_type')

                                    <div class="text-danger small mb-3">

                                        {{ $message }}

                                    </div>

                                @enderror


                                <!-- =====================================
                                     SAVED ADDRESS
                                ====================================== -->

                                <div id="savedAddressSection">

                                    @if($addresses->count() > 0)

                                        <div class="list-group">

                                            @foreach($addresses as $address)

                                                <label class="list-group-item d-flex gap-2">

                                                    <input class="form-check-input flex-shrink-0"
                                                           type="radio"
                                                           name="address_id"
                                                           value="{{ $address->id }}"
                                                           {{ old('address_id', $addresses->first()->id ?? '') == $address->id ? 'checked' : '' }}>


                                                    <div class="flex-grow-1">

                                                        <span class="fw-semibold">

                                                            {{ $address->recipient_name }}

                                                        </span>


                                                        <p class="mb-0 text-muted small">

                                                            {{ $address->address }}

                                                        </p>


                                                        <p class="mb-0 text-muted small">

                                                            {{ $address->city }}

                                                        </p>


                                                        <p class="mb-0 text-muted small">

                                                            {{ $address->phone }}

                                                        </p>





                                                        @if($address->latitude && $address->longitude)

                                                            <div class="mt-2"
                                                                 id="map-{{ $address->id }}"
                                                                 style="
                                                                    height:150px;
                                                                    border-radius:8px;
                                                                    border:1px solid #dee2e6;
                                                                 "
                                                                 data-lat="{{ $address->latitude }}"
                                                                 data-lng="{{ $address->longitude }}"
                                                                 data-address="{{ $address->address }}">
                                                            </div>

                                                        @endif



                                                        

                                                    </div>

                                                </label>

                                            @endforeach

                                        </div>

                                    @else

                                        <div class="alert alert-warning">

                                            You don't have any saved addresses.
                                            Please add a new address below.

                                        </div>

                                    @endif

                                </div>


                                @error('address_id')

                                    <div class="text-danger small mt-2">

                                        {{ $message }}

                                    </div>

                                @enderror


                                <!-- =====================================
                                     NEW ADDRESS
                                ====================================== -->

                                <div id="newAddressSection"
                                     class="d-none mt-3">

                                    <div class="card border">

                                        <div class="card-body">

                                            <h6 class="fw-semibold mb-3">

                                                New Address Details

                                            </h6>


                                            <!-- MAP -->

                                            <div class="mb-3">

                                                <label class="form-label">

                                                    Pin Address on Map

                                                </label>


                                                <div id="addressMap"></div>


                                                <input type="hidden"
                                                       id="latitude"
                                                       name="latitude"
                                                       value="{{ old('latitude') }}">


                                                <input type="hidden"
                                                       id="longitude"
                                                       name="longitude"
                                                       value="{{ old('longitude') }}">


                                                <small class="text-muted">

                                                    Click on the map to set your
                                                    delivery location.

                                                </small>


                                                <div id="mapStatus"
                                                     class="small mt-1">
                                                </div>

                                            </div>


                                            <!-- FORM -->

                                            <div class="row g-3">


                                                <!-- Recipient -->

                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        Recipient Name

                                                    </label>


                                                    <input type="text"
                                                           class="form-control @error('recipient_name') is-invalid @enderror"
                                                           id="recipientName"
                                                           name="recipient_name"
                                                           value="{{ old('recipient_name') }}">


                                                    @error('recipient_name')

                                                        <div class="text-danger small mt-1">

                                                            {{ $message }}

                                                        </div>

                                                    @enderror

                                                </div>


                                                <!-- Phone -->

                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        Phone

                                                    </label>


                                                    <input type="text"
                                                           class="form-control @error('phone') is-invalid @enderror"
                                                           id="phone"
                                                           name="phone"
                                                           value="{{ old('phone') }}">


                                                    @error('phone')

                                                        <div class="text-danger small mt-1">

                                                            {{ $message }}

                                                        </div>

                                                    @enderror

                                                </div>


                                                <!-- Address -->

                                                <div class="col-12">

                                                    <label class="form-label">

                                                        Address

                                                    </label>


                                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                                              id="address"
                                                              name="address"
                                                              rows="2">{{ old('address') }}</textarea>


                                                    @error('address')

                                                        <div class="text-danger small mt-1">

                                                            {{ $message }}

                                                        </div>

                                                    @enderror

                                                </div>


                                                <!-- City -->

                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        City

                                                    </label>


                                                    <input type="text"
                                                           class="form-control @error('city') is-invalid @enderror"
                                                           id="city"
                                                           name="city"
                                                           value="{{ old('city') }}">


                                                    @error('city')

                                                        <div class="text-danger small mt-1">

                                                            {{ $message }}

                                                        </div>

                                                    @enderror

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- =================================================
                             PAYMENT METHOD
                        ================================================== -->

                        <div class="card mb-4">

                            <div class="card-body">

                                <h5 class="fw-bold mb-3">

                                    <i class="bi bi-wallet2 me-2 text-primary"></i>

                                    Payment Method

                                </h5>


                                <div class="list-group">


                                    <!-- CASH -->

                                    <label class="list-group-item d-flex gap-2">

                                        <input class="form-check-input flex-shrink-0"
                                               type="radio"
                                               name="payment_method"
                                               value="cash"
                                               {{ old('payment_method', 'cash') == 'cash' ? 'checked' : '' }}
                                               required>

                                        <div>

                                            <span class="fw-semibold">

                                                Cash on Delivery

                                            </span>

                                            <p class="mb-0 text-muted small">

                                                Pay with cash when your order arrives.

                                            </p>

                                        </div>

                                    </label>


                                    <!-- KHQR -->

                                    <label class="list-group-item d-flex gap-2">

                                        <input class="form-check-input flex-shrink-0"
                                               type="radio"
                                               name="payment_method"
                                               value="khqr"
                                               {{ old('payment_method') == 'khqr' ? 'checked' : '' }}>

                                        <div>

                                            <span class="fw-semibold">

                                                KHQR

                                            </span>

                                            <p class="mb-0 text-muted small">

                                                Pay using KHQR mobile banking.

                                            </p>

                                        </div>

                                    </label>

                                </div>


                                @error('payment_method')

                                    <div class="text-danger small mt-2">

                                        {{ $message }}

                                    </div>

                                @enderror


                                <!-- =====================================
                                     KHQR OPTIONS
                                ====================================== -->

                                <div id="khqrPaymentSection"
                                     class="d-none mt-3">

                                    <div class="card border">

                                        <div class="card-body">

                                            <h6 class="fw-semibold mb-3">

                                                KHQR Payment Option

                                            </h6>


                                            <div class="list-group">


                                                <!-- PAY NOW -->

                                                <label class="list-group-item d-flex gap-2">

                                                    <input class="form-check-input flex-shrink-0"
                                                           type="radio"
                                                           name="khqr_payment_type_option"
                                                           value="now"
                                                           {{ old('khqr_payment_type', 'delivery') == 'now' ? 'checked' : '' }}>

                                                    <div>

                                                        <span class="fw-semibold">

                                                            Pay Now

                                                        </span>

                                                        <p class="mb-0 text-muted small">

                                                            Scan QR code and pay immediately.

                                                        </p>

                                                    </div>

                                                </label>


                                                <!-- DELIVERY -->

                                                <label class="list-group-item d-flex gap-2">

                                                    <input class="form-check-input flex-shrink-0"
                                                           type="radio"
                                                           name="khqr_payment_type_option"
                                                           value="delivery"
                                                           {{ old('khqr_payment_type', 'delivery') == 'delivery' ? 'checked' : '' }}>

                                                    <div>

                                                        <span class="fw-semibold">

                                                            Pay by Delivery

                                                        </span>

                                                        <p class="mb-0 text-muted small">

                                                            Pay when the order is delivered.

                                                        </p>

                                                    </div>

                                                </label>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         RIGHT - ORDER SUMMARY
                    ================================================== -->

                    <div class="col-lg-4">

                        <div class="card order-summary-card">

                            <div class="card-body">

                                <h5 class="fw-bold mb-3">

                                    Order Summary

                                </h5>


                                <!-- CART ITEMS -->

                                @foreach($cart->items as $item)

                                    <div class="d-flex justify-content-between align-items-start mb-3">

                                        <div class="pe-2">

                                            <div class="fw-semibold">

                                                {{ $item->food->name }}

                                            </div>

                                            <small class="text-muted">

                                                Quantity:
                                                {{ $item->quantity }}

                                            </small>

                                        </div>


                                        <span class="fw-semibold">

                                            ${{ number_format($item->price * $item->quantity, 2) }}

                                        </span>

                                    </div>

                                @endforeach


                                <hr>


                                <!-- SUBTOTAL -->

                                <div class="d-flex justify-content-between mb-2">

                                    <span class="text-muted">

                                        Subtotal

                                    </span>


                                    <span>

                                        ${{ number_format($subtotal, 2) }}

                                    </span>

                                </div>


                                <!-- DELIVERY -->

                                <div class="d-flex justify-content-between mb-2">

                                    <span class="text-muted">

                                        Delivery Fee

                                    </span>


                                    <span>

                                        ${{ number_format($deliveryFee, 2) }}

                                    </span>

                                </div>


                                <!-- DISCOUNT -->

                                @if($discount > 0)

                                    <div class="d-flex justify-content-between mb-2 text-success">

                                        <span>

                                            Discount

                                        </span>


                                        <span>

                                            -${{ number_format($discount, 2) }}

                                        </span>

                                    </div>

                                @endif


                                <hr>


                                <!-- TOTAL -->

                                <div class="d-flex justify-content-between align-items-center mb-3">

                                    <span class="fw-bold">

                                        Total

                                    </span>


                                    <span class="fw-bold text-primary fs-5">

                                        ${{ number_format($total, 2) }}

                                    </span>

                                </div>


                                <!-- NORMAL PLACE ORDER -->

                                <button type="submit"
                                        name="place_order"
                                        value="1"
                                        id="placeOrderBtn"
                                        class="btn btn-primary w-100 py-2">

                                    Place Order

                                </button>


                                <!-- KHQR PAY NOW -->

                                <button type="button"
                                        id="khqrPlaceOrderBtn"
                                        class="btn btn-primary w-100 py-2 d-none"
                                        data-total="{{ number_format($total, 2) }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#khqrModal">

                                    <i class="bi bi-qr-code me-1"></i>

                                    Pay Now with KHQR

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        @endif

    </div>

</section>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       ADDRESS
    ===================================================== */

    const addressTypeRadios =
        document.querySelectorAll(
            'input[name="address_type"]'
        );

    const savedAddressSection =
        document.getElementById(
            'savedAddressSection'
        );

    const newAddressSection =
        document.getElementById(
            'newAddressSection'
        );


    function toggleAddress() {

        const selected =
            document.querySelector(
                'input[name="address_type"]:checked'
            );


        if (!selected) {
            return;
        }


        if (selected.value === 'new') {

            savedAddressSection.classList.add(
                'd-none'
            );

            newAddressSection.classList.remove(
                'd-none'
            );


            document
                .querySelectorAll(
                    'input[name="address_id"]'
                )
                .forEach(function (input) {

                    input.checked = false;
                    input.removeAttribute('required');

                });


            setTimeout(function () {

                initAddressMap();

            }, 150);

        }

        else {

            savedAddressSection.classList.remove(
                'd-none'
            );

            newAddressSection.classList.add(
                'd-none'
            );


            const addressInputs =
                document.querySelectorAll(
                    'input[name="address_id"]'
                );


            addressInputs.forEach(function (input) {

                input.setAttribute(
                    'required',
                    'required'
                );

            });

        }

    }


    addressTypeRadios.forEach(function (radio) {

        radio.addEventListener(
            'change',
            toggleAddress
        );

    });


    toggleAddress();


    /* =====================================================
       PAYMENT
    ===================================================== */

    const paymentRadios =
        document.querySelectorAll(
            'input[name="payment_method"]'
        );


    const khqrOptions =
        document.querySelectorAll(
            'input[name="khqr_payment_type_option"]'
        );


    const khqrSection =
        document.getElementById(
            'khqrPaymentSection'
        );


    const placeOrderBtn =
        document.getElementById(
            'placeOrderBtn'
        );


    const khqrPlaceOrderBtn =
        document.getElementById(
            'khqrPlaceOrderBtn'
        );


    const khqrHidden =
        document.getElementById(
            'khqrPaymentType'
        );


    function togglePayment() {

        const payment =
            document.querySelector(
                'input[name="payment_method"]:checked'
            );


        if (!payment) {
            return;
        }


        /* ---------------------------------------------
           CASH
        --------------------------------------------- */

        if (payment.value === 'cash') {

            khqrSection.classList.add(
                'd-none'
            );

            placeOrderBtn.classList.remove(
                'd-none'
            );

            khqrPlaceOrderBtn.classList.add(
                'd-none'
            );

            khqrHidden.value = 'delivery';

            return;

        }


        /* ---------------------------------------------
           KHQR
        --------------------------------------------- */

        khqrSection.classList.remove(
            'd-none'
        );


        const selected =
            document.querySelector(
                'input[name="khqr_payment_type_option"]:checked'
            );


        if (!selected) {
            return;
        }


        khqrHidden.value =
            selected.value;


        /* PAY NOW */

        if (selected.value === 'now') {

            placeOrderBtn.classList.add(
                'd-none'
            );

            khqrPlaceOrderBtn.classList.remove(
                'd-none'
            );

        }

        /* PAY BY DELIVERY */

        else {

            placeOrderBtn.classList.remove(
                'd-none'
            );

            khqrPlaceOrderBtn.classList.add(
                'd-none'
            );

        }

    }


    paymentRadios.forEach(function (radio) {

        radio.addEventListener(
            'change',
            togglePayment
        );

    });


    khqrOptions.forEach(function (radio) {

        radio.addEventListener(
            'change',
            togglePayment
        );

    });


    togglePayment();


    /* =====================================================
       KHQR PAY NOW
    ===================================================== */

    if (khqrPlaceOrderBtn) {

        khqrPlaceOrderBtn.addEventListener(
            'click',
            function () {

                khqrHidden.value = 'now';

            }
        );

    }

});


/* =========================================================
   KHQR CONFIRM - submit checkout form to complete order
   Called by the KHQR modal "Place Order" button in the layout.
========================================================= */
function confirmKhqrPayment() {
    const form = document.getElementById('checkoutForm');
    if (!form) {
        console.error('Checkout form not found.');
        return;
    }

    const khqrHidden = document.getElementById('khqrPaymentType');
    if (khqrHidden) {
        khqrHidden.value = 'now';
    }

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    form.submit();
}


/* =========================================================
   MAP
========================================================= */

let addressMap = null;

let addressMarker = null;


function initAddressMap() {

    if (addressMap) {

        setTimeout(function () {

            addressMap.invalidateSize();

        }, 100);

        return;

    }


    if (
        typeof L === 'undefined'
    ) {

        console.error(
            'Leaflet is not loaded.'
        );

        return;

    }


    const mapElement =
        document.getElementById(
            'addressMap'
        );


    if (!mapElement) {
        return;
    }


    addressMap =
        L.map(
            'addressMap'
        ).setView(
            [11.5564, 104.9282],
            13
        );


    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            attribution:
                '&copy; OpenStreetMap contributors'
        }
    ).addTo(
        addressMap
    );


    /* =====================================================
       EXISTING LOCATION
    ===================================================== */

    const oldLat =
        document.getElementById(
            'latitude'
        ).value;


    const oldLng =
        document.getElementById(
            'longitude'
        ).value;


    if (oldLat && oldLng) {

        const lat =
            parseFloat(oldLat);

        const lng =
            parseFloat(oldLng);


        if (
            !isNaN(lat) &&
            !isNaN(lng)
        ) {

            addressMap.setView(
                [lat, lng],
                15
            );


            addressMarker =
                L.marker(
                    [lat, lng]
                ).addTo(
                    addressMap
                );

        }

    }


    /* =====================================================
       CLICK MAP
    ===================================================== */

    addressMap.on(
        'click',
        function (e) {

            const lat =
                e.latlng.lat.toFixed(7);

            const lng =
                e.latlng.lng.toFixed(7);


            document.getElementById(
                'latitude'
            ).value = lat;


            document.getElementById(
                'longitude'
            ).value = lng;


            /* Remove old marker */

            if (addressMarker) {

                addressMap.removeLayer(
                    addressMarker
                );

            }


            /* Add marker */

            addressMarker =
                L.marker(
                    [lat, lng]
                ).addTo(
                    addressMap
                );


            document.getElementById(
                'mapStatus'
            ).innerHTML =
                '<span class="text-success">' +
                '<i class="bi bi-check-circle"></i> ' +
                'Location selected' +
                '</span>';


            reverseGeocode(
                lat,
                lng
            );

        }
    );


    setTimeout(function () {

        addressMap.invalidateSize();

    }, 200);

}


/* =========================================================
   REVERSE GEOCODE
========================================================= */

function reverseGeocode(
    lat,
    lng
) {

    const url =
        'https://nominatim.openstreetmap.org/reverse' +
        '?format=json' +
        '&lat=' + lat +
        '&lon=' + lng +
        '&addressdetails=1';


    const status =
        document.getElementById(
            'mapStatus'
        );


    status.innerHTML =
        '<span class="text-muted">' +
        '<i class="bi bi-hourglass-split"></i> ' +
        'Getting address...' +
        '</span>';


    fetch(
        url,
        {
            headers: {
                'Accept-Language': 'en'
            }
        }
    )


    .then(function (response) {

        return response.json();

    })


    .then(function (data) {

        if (
            !data ||
            !data.address
        ) {

            return;

        }


        const addr =
            data.address;


        const street =
            addr.road ||
            addr.pedestrian ||
            addr.street ||
            '';


        const houseNumber =
            addr.house_number ||
            '';


        const city =
            addr.city ||
            addr.town ||
            addr.village ||
            addr.state ||
            '';


        let fullAddress = '';


        if (
            houseNumber &&
            street
        ) {

            fullAddress =
                houseNumber +
                ' ' +
                street;

        }

        else if (street) {

            fullAddress =
                street;

        }

        else {

            fullAddress =
                city;

        }


        const addressInput =
            document.getElementById(
                'address'
            );


        const cityInput =
            document.getElementById(
                'city'
            );


        if (
            addressInput &&
            fullAddress
        ) {

            addressInput.value =
                fullAddress;

        }


        if (
            cityInput &&
            city
        ) {

            cityInput.value =
                city;

        }


        status.innerHTML =
            '<span class="text-success">' +
            '<i class="bi bi-check-circle"></i> ' +
            'Address found!' +
            '</span>';

    })


    .catch(function (error) {

        console.error(
            'Reverse geocoding error:',
            error
        );


        status.innerHTML =
            '<span class="text-warning">' +
            '<i class="bi bi-exclamation-triangle"></i> ' +
            'Location saved. Address could not be detected.' +
            '</span>';

    });

}


/* =========================================================
   SAVED ADDRESS MINI MAPS
========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        if (
            typeof L === 'undefined'
        ) {
            return;
        }


        document
            .querySelectorAll(
                '[id^="map-"]'
            )
            .forEach(
                function (container) {

                    try {

                        const lat =
                            parseFloat(
                                container.dataset.lat
                            );


                        const lng =
                            parseFloat(
                                container.dataset.lng
                            );


                        if (
                            isNaN(lat) ||
                            isNaN(lng)
                        ) {

                            return;

                        }


                        const miniMap =
                            L.map(
                                container.id,
                                {
                                    center: [
                                        lat,
                                        lng
                                    ],

                                    zoom: 15,

                                    zoomControl: false,

                                    attributionControl: false,

                                    dragging: false,

                                    scrollWheelZoom: false,

                                    doubleClickZoom: false,

                                    touchZoom: false
                                }
                            );


                        L.tileLayer(
                            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
                        ).addTo(
                            miniMap
                        );


                        L.marker(
                            [lat, lng]
                        ).addTo(
                            miniMap
                        );


                        setTimeout(function () {

                            miniMap.invalidateSize();

                        }, 100);

                    }

                    catch (error) {

                        console.error(
                            'Mini map error:',
                            error
                        );

                    }

                }
            );

    }
);

</script>

@endsection