<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        /* =========================================================
           SHARED GREEN ADMIN THEME
           ========================================================= */
        :root {
            --green-primary: #16a34a;
            --green-dark: #15803d;
            --green-light: #f0fdf4;
            --green-soft: #dcfce7;

            --text-main: #17211b;
            --text-muted: #6b7280;
            --border-light: #e5e7eb;
            --bg-page: #f8faf9;
            --white: #ffffff;

            --radius: 14px;
            --shadow-soft: 0 2px 12px rgba(15, 23, 42, 0.05);
            --shadow-hover: 0 8px 25px rgba(15, 23, 42, 0.08);
        }

        body {
            background: var(--bg-page);
            color: var(--text-main);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* PAGE HEADER */
        .page-header {
            margin-bottom: 28px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 14px;
            margin: 0;
        }

        /* CARDS */
        .card,
        .data-card {
            background: var(--white);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            transition: box-shadow 0.25s ease;
        }

        .card:hover,
        .data-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .card-header,
        .data-card-header {
            background: #ffffff !important;
            border-bottom: 1px solid #f0f2f1 !important;
            padding: 18px 20px !important;
        }

        .card-title,
        .data-card-title {
            color: var(--text-main);
            font-size: 16px;
            font-weight: 650;
            margin: 0;
        }

        .card-body,
        .data-card-body {
            padding: 20px !important;
        }

        /* BUTTONS */
        .btn-primary {
            background: var(--green-primary) !important;
            border-color: var(--green-primary) !important;
            color: #ffffff !important;
            border-radius: 9px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 3px 8px rgba(22, 163, 74, 0.18);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: var(--green-dark) !important;
            border-color: var(--green-dark) !important;
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(22, 163, 74, 0.22);
        }

        .btn-outline-primary {
            color: var(--green-primary) !important;
            border-color: #bbf7d0 !important;
            background: #ffffff !important;
        }

        .btn-outline-primary:hover {
            background: var(--green-light) !important;
            border-color: var(--green-primary) !important;
            color: var(--green-dark) !important;
        }

        .btn-icon {
            width: 34px;
            height: 34px;
            padding: 0 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px !important;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        /* TABLES */
        .data-card .table {
            margin-bottom: 0;
        }

        .data-card .table thead th {
            background: #f8faf9;
            color: #6b7280;
            border-bottom: 1px solid var(--border-light);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 14px 16px;
        }

        .data-card .table tbody td {
            padding: 15px 16px;
            border-bottom: 1px solid #f1f3f2;
            color: #374151;
            font-size: 14px;
            vertical-align: middle;
        }

        .data-card .table tbody tr:last-child td {
            border-bottom: none;
        }

        .data-card .table tbody tr {
            transition: background 0.2s ease;
        }

        .data-card .table tbody tr:hover {
            background: #f8faf9;
        }

        /* BADGES */
        .badge {
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge.bg-success {
            background: #dcfce7 !important;
            color: #15803d !important;
        }

        .badge.bg-warning {
            background: #fef3c7 !important;
            color: #92400e !important;
        }

        .badge.bg-primary,
        .badge.bg-info,
        .badge.bg-secondary {
            background: var(--green-light) !important;
            color: var(--green-dark) !important;
        }

        .badge.bg-danger {
            background: #fee2e2 !important;
            color: #b91c1c !important;
        }

        .text-danger {
            color: #dc2626 !important;
        }

        .text-success {
            color: #16a34a !important;
        }

        /* AVATAR */
        .avatar-primary {
            background: var(--green-light) !important;
            color: var(--green-primary) !important;
            font-weight: 600;
        }

        .avatar {
            border-radius: 50%;
        }

        /* EMPTY STATE */
        .empty-state {
            padding: 45px 20px;
            text-align: center;
        }

        .empty-state-icon {
            width: 55px;
            height: 55px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--green-light);
            color: var(--green-primary);
            border-radius: 50%;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .empty-state-title {
            color: var(--text-main);
            font-weight: 600;
            margin-bottom: 5px;
        }

        .empty-state-text {
            color: var(--text-muted);
            font-size: 13px;
        }

        /* MODALS */
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.15);
            overflow: hidden;
        }

        .modal-header {
            padding: 18px 20px;
            background: #ffffff;
            border-bottom: 1px solid var(--border-light);
        }

        .modal-title {
            color: var(--text-main);
            font-size: 16px;
            font-weight: 650;
        }

        .modal-body {
            padding: 25px 20px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .modal-body .text-warning {
            color: #f59e0b !important;
        }

        .modal-body strong {
            color: var(--text-main);
        }

        .modal-footer {
            padding: 15px 20px;
            background: #fafafa;
            border-top: 1px solid var(--border-light);
        }

        .modal-footer .btn-outline-secondary {
            border-color: var(--border-light);
            color: #6b7280;
            background: #ffffff;
            border-radius: 8px;
            font-weight: 500;
        }

        .modal-footer .btn-outline-secondary:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .modal-footer .btn-danger {
            background: #dc2626;
            border-color: #dc2626;
            border-radius: 8px;
            font-weight: 600;
        }

        .modal-footer .btn-danger:hover {
            background: #b91c1c;
            border-color: #b91c1c;
        }

        /* FORMS */
        .form-label {
            color: var(--text-main);
            font-weight: 500;
            font-size: 13.5px;
            margin-bottom: 6px;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border-light);
            border-radius: 9px;
            padding: 10px 14px;
            color: var(--text-main);
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--green-primary);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.10);
            outline: none;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #ef4444;
            background-image: none;
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        /* ACTION BUTTONS */
        .action-btns {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .action-btns .btn {
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .action-btns .btn-outline-primary {
            color: var(--green-primary) !important;
            border-color: #bbf7d0 !important;
            background: #ffffff !important;
        }

        .action-btns .btn-outline-primary:hover {
            background: var(--green-light) !important;
            border-color: var(--green-primary) !important;
            color: var(--green-dark) !important;
            transform: translateY(-1px);
        }

        .action-btns .btn-outline-danger {
            color: #dc2626 !important;
            border-color: #fecaca !important;
            background: #ffffff !important;
        }

        .action-btns .btn-outline-danger:hover {
            background: #fef2f2 !important;
            border-color: #ef4444 !important;
            color: #b91c1c !important;
            transform: translateY(-1px);
        }

        /* IMAGE THUMBNAILS */
        .img-thumb-md {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid var(--border-light);
            background: var(--green-light);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .data-card tbody tr:hover .img-thumb-md {
            transform: scale(1.04);
        }

        .img-thumb-md.bg-light {
            background: var(--green-light) !important;
            border-color: #dcfce7 !important;
        }

        .img-thumb-md.bg-light i {
            color: #86a98f !important;
        }

        /* ALERT */
        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 10px;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            border-radius: 10px;
        }

        /* STAT CARDS */
        .stat-card {
            position: relative;
            background: var(--white);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            padding: 22px;
            min-height: 150px;
            box-shadow: var(--shadow-soft);
            transition: all 0.25s ease;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
            border-color: #d1d5db;
        }

        .stat-card::after {
            content: "";
            position: absolute;
            width: 80px;
            height: 80px;
            right: -25px;
            top: -25px;
            background: var(--green-light);
            border-radius: 50%;
            opacity: 0.7;
        }

        .stat-card-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 20px;
            position: relative;
            z-index: 1;
        }

        .stat-card-icon.primary,
        .stat-card-icon.info,
        .stat-card-icon.warning,
        .stat-card-icon.danger,
        .stat-card-gradient .stat-card-icon {
            background: var(--green-light) !important;
            color: var(--green-primary) !important;
        }

        .stat-card-label {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .stat-card-value {
            color: var(--text-main);
            font-size: 25px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .stat-card-gradient {
            background: #ffffff !important;
            color: var(--text-main) !important;
            border: 1px solid var(--border-light) !important;
        }

        .stat-card-gradient .stat-card-label,
        .stat-card-gradient .stat-card-value {
            color: var(--text-main) !important;
        }

        /* FILTER CARD */
        .filter-card {
            background: var(--white);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            box-shadow: var(--shadow-soft);
            padding: 18px 20px;
            margin-bottom: 20px;
        }

        .filter-card .form-label {
            color: var(--text-main);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .filter-card .form-select {
            border: 1px solid var(--border-light);
            border-radius: 9px;
            color: #374151;
            font-size: 14px;
            padding: 9px 12px;
            box-shadow: none;
            transition: all 0.2s ease;
        }

        .filter-card .form-select:focus {
            border-color: #86efac;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.08);
        }

        .filter-card .btn-outline-secondary {
            border-color: var(--border-light);
            color: #6b7280;
            background: #ffffff;
            border-radius: 9px;
            padding: 9px 14px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .filter-card .btn-outline-secondary:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #374151;
            transform: translateY(-1px);
        }

        .filter-card .btn-outline-secondary i {
            margin-right: 5px;
        }

        /* DATE PRESET */
        .date-preset {
            border-color: var(--border-light) !important;
            color: #4b5563 !important;
            background: #ffffff !important;
            border-radius: 8px !important;
            padding: 7px 14px !important;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .date-preset:hover,
        .date-preset.active {
            background: var(--green-light) !important;
            border-color: var(--green-primary) !important;
            color: var(--green-primary) !important;
        }

        /* CHART CONTAINER */
        .chart-container {
            position: relative;
            width: 100%;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .page-header {
                margin-bottom: 20px;
            }

            .page-title {
                font-size: 23px;
            }

            .stat-card {
                padding: 18px;
            }

            .stat-card-value {
                font-size: 22px;
            }

            .card-body,
            .data-card-body {
                padding: 15px !important;
            }

            .card-header,
            .data-card-header {
                padding: 15px !important;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 15px;
            }

            .page-header .btn-primary {
                width: 100%;
                justify-content: center;
            }

            .filter-card {
                padding: 15px;
            }

            .filter-card .btn-outline-secondary {
                width: 100%;
            }

            .data-card-header {
                padding: 15px;
            }

            .data-card .table thead th,
            .data-card .table tbody td {
                padding: 12px 14px;
            }

            .img-thumb-md {
                width: 45px;
                height: 45px;
            }

            .action-btns .btn {
                width: 32px;
                height: 32px;
            }
        }
    </style>
<body>
    <div class="d-flex">
        @include('admin.components.sidebar')
        <div class="flex-grow-1 main-content" id="mainContent">
            @include('admin.components.topbar')
            <div class="page-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
    @include('shared.receipt-modal')
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
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggle = document.getElementById('sidebarToggle');

            function toggleSidebar() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                }
            }

            if (toggle) {
                toggle.addEventListener('click', toggleSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }
        });
    </script>
</body>
</html>
