<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KursusKu')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, sans-serif;
            background-color: #f8f5fe;
            color: #1a1625;
            font-size: 16px;
        }
        .text-purple { color: #6929c4 !important; }
        .bg-purple { background-color: #6929c4 !important; }
        .bg-purple-subtle { background-color: #ece4fd !important; }
        .btn-purple {
            background-color: #6929c4;
            border-color: #6929c4;
            color: #ffffff;
        }
        .btn-purple:hover, .btn-purple:focus {
            background-color: #551c96;
            border-color: #551c96;
            color: #ffffff;
        }
        .btn-outline-purple {
            color: #6929c4;
            border-color: #6929c4;
        }
        .btn-outline-purple:hover {
            background-color: #6929c4;
            color: #ffffff;
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    @include('partials.navbar')

    <main class="flex-grow-1">
        @if (session('success') || session('error') || session('status'))
            <div class="container pt-3">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif
                @if (session('status'))
                    <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>{{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white border-top py-4 mt-5">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-muted small">
            <span>&copy; {{ date('Y') }} <strong>KursusKu</strong>. Platform Pembelajaran Digital.</span>
            <div class="d-flex gap-3">
                <a href="{{ route('home') }}" class="text-decoration-none text-muted">Beranda</a>
                <a href="{{ route('courses.index') }}" class="text-decoration-none text-muted">Kursus</a>
                <a href="{{ route('info') }}" class="text-decoration-none text-muted">Informasi</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-toggle-password]');
            if (!btn) return;
            const targetId = btn.getAttribute('data-toggle-password');
            const input = document.getElementById(targetId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            const icon = btn.querySelector('i');
            if (icon) {
                icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
