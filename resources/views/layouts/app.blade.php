<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KursusKu - @yield('title', 'Daftar Kursus')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #7B2D8E;
            --dark-purple: #5A1E6B;
            --light-purple: #9B4DCA;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        /* Navbar Ungu */
        .navbar-purple {
            background-color: var(--primary-purple) !important;
            padding: 15px 0;
        }

        .navbar-purple .navbar-brand {
            color: white !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-purple .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
        }

        .navbar-purple .nav-link:hover {
            color: white !important;
        }

        /* Tombol Ungu */
        .btn-purple {
            background-color: var(--primary-purple);
            color: white;
            border: none;
        }

        .btn-purple:hover {
            background-color: var(--dark-purple);
            color: white;
        }

        /* Table Header Gelap */
        .table-dark-header thead {
            background-color: #2c2c2c;
            color: white;
        }

        .table-dark-header thead th {
            border: none;
            padding: 12px;
            font-weight: 600;
        }

        /* Badge Status */
        .badge-open {
            background-color: #28a745;
            color: white;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
        }

        .badge-closed {
            background-color: #dc3545;
            color: white;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
        }

        .badge-draft {
            background-color: #6c757d;
            color: white;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
        }

        /* Tombol Aksi */
        .btn-detail {
            background-color: #17a2b8;
            color: white;
            border: none;
            padding: 4px 12px;
            font-size: 0.85rem;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #000;
            border: none;
            padding: 4px 12px;
            font-size: 0.85rem;
        }

        .btn-hapus {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 4px 12px;
            font-size: 0.85rem;
        }

        /* Pagination */
        .pagination .page-link {
            color: var(--primary-purple);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-purple);
            border-color: var(--primary-purple);
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar Ungu -->
    <nav class="navbar navbar-purple">
        <div class="container">
            <a class="navbar-brand" href="/">KursusKu</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">Beranda</a>
                <a class="nav-link" href="/pendaftaran">Pendaftaran</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container py-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>