<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Delivery Dashboard') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    @include('delivery.components.sidebar')

    <div class="delivery-wrapper">
        <main class="delivery-main">
            @include('delivery.components.topbar')

            <div class="delivery-container">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    @include('shared.receipt-modal')
    @include('shared.khqr-modal')

    <div class="delivery-bottom-nav">
        <a href="{{ route('delivery.dashboard') }}" class="delivery-bottom-nav-item {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('delivery.orders.index') }}" class="delivery-bottom-nav-item {{ request()->routeIs('delivery.orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag"></i>
            <span>Orders</span>
        </a>
        <a href="{{ route('delivery.report') }}" class="delivery-bottom-nav-item {{ request()->routeIs('delivery.report') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>History</span>
        </a>
        <a href="{{ route('delivery.profile.index') }}" class="delivery-bottom-nav-item {{ request()->routeIs('delivery.profile.*') ? 'active' : '' }}">
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('deliverySidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggler = document.getElementById('sidebarToggler');

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
                document.body.style.overflow = '';
            }

            if (toggler) {
                toggler.addEventListener('click', function() {
                    if (sidebar.classList.contains('open')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            document.querySelectorAll('.delivery-sidebar .delivery-nav-item, .delivery-sidebar button[type="submit"]').forEach(function(el) {
                el.addEventListener('click', function() {
                    if (window.innerWidth <= 991.98) {
                        closeSidebar();
                    }
                });
            });

            const statusToggle = document.getElementById('statusToggle');
            if (statusToggle) {
                statusToggle.addEventListener('click', function() {
                    const dot = this.querySelector('.delivery-status-dot');
                    const text = this.querySelector('.delivery-status-text');
                    const isOnline = dot.classList.contains('delivery-status-online');

                    if (isOnline) {
                        dot.classList.remove('delivery-status-online');
                        dot.classList.add('delivery-status-offline');
                        if (text) text.textContent = 'Offline';
                    } else {
                        dot.classList.remove('delivery-status-offline');
                        dot.classList.add('delivery-status-online');
                        if (text) text.textContent = 'Online';
                    }
                });
            }

            const receiptModal = document.getElementById('receiptModal');
            if (receiptModal) {
                receiptModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const url = button.getAttribute('data-receipt-url');
                    if (!url) return;

                    const modalContent = document.getElementById('receiptModalContent');
                    modalContent.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html, application/xhtml+xml',
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        modalContent.innerHTML = html;
                    })
                    .catch(() => {
                        modalContent.innerHTML = '<div class="p-4 text-center text-danger">Failed to load receipt. Please try again.</div>';
                    });
                });
            }

            const khqrModal = document.getElementById('khqrModal');
            if (khqrModal) {
                khqrModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const total = button.getAttribute('data-total');
                    const orderId = button.getAttribute('data-order-id');
                    const modalTotal = document.getElementById('khqrModalTotal');
                    const confirmBtn = document.getElementById('khqrConfirmBtn');

                    if (modalTotal && total) {
                        modalTotal.textContent = '$' + parseFloat(total).toFixed(2);
                    }

                    if (confirmBtn && orderId) {
                        confirmBtn.setAttribute('data-order-id', orderId);
                        confirmBtn.setAttribute('data-context', 'delivery');
                        confirmBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Confirm Payment Received';
                    }
                });

                document.getElementById('khqrConfirmBtn').addEventListener('click', function() {
                    const context = this.getAttribute('data-context');
                    const orderId = this.getAttribute('data-order-id');

                    if (context === 'delivery' && orderId) {
                        confirmDeliveryKhqrPayment(orderId);
                    }
                });
            }
        });

        function confirmDeliveryKhqrPayment(orderId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/delivery/orders/${orderId}/deliver-and-pay`;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        }

        document.querySelectorAll('.delivery-table-row').forEach(function(row) {
            row.addEventListener('click', function(e) {
                if (e.target.closest('a, button, input, select, textarea')) {
                    return;
                }
                const href = this.getAttribute('data-href');
                if (href) {
                    window.location.href = href;
                }
            });
        });
    </script>
</body>
</html>
