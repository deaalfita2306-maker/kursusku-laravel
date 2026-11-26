<header class="site-header bg-white border-bottom shadow-sm sticky-top">
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-purple" href="{{ route('home') }}">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-purple text-white fw-bold" style="width: 34px; height: 34px;">K</span>
                <span>{{ $siteName ?? 'KursusKu' }}</span>
            </a>

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

            <div class="collapse navbar-collapse" id="kursuskuNavbar">
                @guest
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold text-purple' : '' }}" href="{{ route('home') }}">
                                Beranda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('courses.*') ? 'active fw-semibold text-purple' : '' }}" href="{{ route('courses.index') }}">
                                Kursus
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('info') ? 'active fw-semibold text-purple' : '' }}" href="{{ route('info') }}">
                                Informasi
                            </a>
                        </li>
                    </ul>
                    <div class="d-flex flex-column flex-lg-row gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-purple btn-sm px-3">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-purple btn-sm px-3">Registrasi</a>
                    </div>
                @endguest

                @auth
                    @if (auth()->user()->isAdmin())
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold text-purple' : '' }}" href="{{ route('home') }}">
                                    Beranda
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('courses.*') ? 'active fw-semibold text-purple' : '' }}" href="{{ route('courses.index') }}">
                                    Kursus
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-semibold text-purple' : '' }}" href="{{ route('admin.dashboard') }}">
                                    Dashboard Admin
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active fw-semibold text-purple' : '' }}" href="{{ route('admin.users.index') }}">
                                    Manajemen Pengguna
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('enrollments.*') ? 'active fw-semibold text-purple' : '' }}" href="{{ route('enrollments.index') }}">
                                    Enrollment
                                </a>
                            </li>
                        </ul>
                    @else
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold text-purple' : '' }}" href="{{ route('home') }}">
                                    Beranda
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('courses.*') ? 'active fw-semibold text-purple' : '' }}" href="{{ route('courses.index') }}">
                                    Kursus
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold text-purple' : '' }}" href="{{ route('dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                        </ul>
                    @endif

                    <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2">
                        <a href="{{ route('profile.show') }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-2 {{ request()->routeIs('profile.*') ? 'border-primary text-purple fw-semibold' : '' }}">
                            <i class="bi bi-person-circle"></i>
                            <span>{{ auth()->user()->name }}</span>
                            <span class="badge {{ auth()->user()->isAdmin() ? 'bg-purple' : 'bg-secondary' }} text-uppercase" style="font-size: 0.65rem;">
                                {{ auth()->user()->role }}
                            </span>
                        </a>
                        <a href="{{ route('profile.show') }}" class="nav-link d-lg-none">Profil</a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>
</header>
