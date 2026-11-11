<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sire Restaurant') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700;9..144,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body class="sire-body">
    @include('customer.components.navbar')
    <main class="sire-main">
        @yield('content')
    </main>
    @include('customer.components.footer')
    @include('customer.components.video-modal')
    @include('shared.receipt-modal')
    @include('shared.khqr-modal')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                    const modalTotal = document.getElementById('khqrModalTotal');
                    if (modalTotal && total) {
                        modalTotal.textContent = '$' + parseFloat(total).toFixed(2);
                    }
                });

                document.getElementById('khqrConfirmBtn').addEventListener('click', function() {
                    confirmKhqrPayment();
                });
            }
        });
    </script>
</body>
</html>