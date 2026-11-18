<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', ($siteName ?? 'KursusKu') . ' — Platform Pembelajaran Kursus Digital')</title>

    <!-- Vite Assets (Bootstrap CSS & JS, jQuery, App Logic) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- KursusKu Custom Stylesheet (Purple & White Theme) -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <!-- Navbar Reusable -->
    @include('partials.navbar')

    <!-- Flash Message Success -->
    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                <span class="me-2 fw-bold">&#10003;</span>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Area Konten Utama -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">{{ $siteName ?? 'KursusKu' }}</div>
                    <p class="footer-tagline">Belajar, daftar, dan kelola kursus dalam satu tempat.</p>
                </div>
                <div>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}" class="footer-link">Beranda</a></li>
                        <li><a href="{{ route('courses.index') }}" class="footer-link">Data Kursus</a></li>
                        <li><a href="{{ route('enrollments.index') }}" class="footer-link">Data Enrollment</a></li>
                        <li><a href="{{ route('enrollments.create') }}" class="footer-link">Pendaftaran</a></li>
                        <li><a href="{{ route('home') }}#katalog" class="footer-link">Katalog</a></li>
                        <li><a href="{{ route('home') }}#kontak" class="footer-link">Kontak</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ $siteName ?? 'KursusKu' }}. Seluruh hak cipta dilindungi undang-undang.</p>
                <p>Implementasi Praktikum Pertemuan 12 &mdash; Relational Database KursusKu (Participants, Courses, Enrollments)</p>
            </div>
        </div>
    </footer>

</body>
</html>
