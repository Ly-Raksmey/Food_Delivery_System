<div class="modal fade" id="khqrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title w-100">KHQR Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="text-muted mb-2">Scan the QR code to pay</p>
                <div class="d-flex justify-content-center mb-3">
                    <img src="{{ asset('image/image.png') }}" alt="KHQR" style="width: 220px; height: 220px; object-fit: contain;">
                </div>
                <div class="alert alert-warning py-2 px-3 d-inline-block">
                    <small>Total Amount</small>
                    <div class="fw-bold fs-5" id="khqrModalTotal">$0.00</div>
                </div>
                <p class="text-muted small mt-2 mb-0">Please complete payment before placing the order.</p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" style="background-color: #ff6b6b; border-color: #ff6b6b;" id="khqrConfirmBtn" data-context="checkout" data-order-id="">
                    <i class="bi bi-check-lg me-1"></i> Place Order
                </button>
            </div>
        </div>
    </div>
</div>
