<!-- Header & Navigasi Reusable (Pertemuan 9, 10, 11 & 12 - Bootstrap Navbar) -->
<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-kursusku">
        <div class="container">
            <a class="navbar-brand brand-link" href="{{ route('home') }}">
                <span class="brand-badge">K</span>
                <span class="brand-text">{{ $siteName ?? 'KursusKu' }}</span>
            </a>

            <!-- Tombol Hamburger Responsif Bootstrap -->
            <button 
                class="navbar-toggler" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#kursuskuNavbar" 
                aria-controls="kursuskuNavbar" 
                aria-expanded="false" 
                aria-label="Toggle navigasi"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Navigasi Collapse -->
            <div class="collapse navbar-collapse" id="kursuskuNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                            Data Kursus
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('enrollments.index') ? 'active' : '' }}" href="{{ route('enrollments.index') }}">
                            Data Enrollment
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('enrollments.create') ? 'active' : '' }}" href="{{ route('enrollments.create') }}">
                            Pendaftaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#katalog">
                            Katalog Kursus
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#keunggulan">
                            Keunggulan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#fasilitas">
                            Fasilitas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#kontak">
                            Kontak
                        </a>
                    </li>
                </ul>

                <div class="nav-action-wrapper mt-3 mt-lg-0">
                    <a href="{{ route('enrollments.create') }}" class="btn btn-primary btn-sm w-100">
                        Daftar Kursus
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>
