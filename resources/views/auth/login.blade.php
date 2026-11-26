@extends('layouts.app')

@section('title', 'Login — KursusKu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-5 bg-purple text-white p-4 p-md-5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-white text-purple fw-bold" style="width: 40px; height: 40px; font-size: 1.2rem;">K</span>
                                <span class="fs-4 fw-bold">KursusKu</span>
                            </div>
                            <h1 class="h2 fw-bold mb-3">Selamat Datang Kembali</h1>
                            <p class="opacity-75 mb-4">
                                Masuk ke akun Anda untuk mengakses dashboard pembelajaran, mengelola profil, atau mengikuti kursus digital pilihan.
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-10 rounded-3 p-3 small">
                            <div class="fw-semibold mb-1"><i class="bi bi-shield-lock me-1"></i>Keamanan Akun</div>
                            <span class="opacity-75">Autentikasi dilindungi enkripsi password Laravel dan sesi terproteksi.</span>
                        </div>
                    </div>

                    <div class="col-lg-7 bg-white p-4 p-md-5">
                        <h2 class="h4 fw-bold mb-1">Masuk ke Akun</h2>
                        <p class="text-muted small mb-4">Gunakan email atau username Anda yang terdaftar.</p>

                        @if ($errors->any())
                            <div class="alert alert-danger small" role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.process') }}" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="login" class="form-label fw-semibold">Email atau Username</label>
                                <input
                                    type="text"
                                    id="login"
                                    name="login"
                                    value="{{ old('login') }}"
                                    class="form-control @error('login') is-invalid @enderror"
                                    placeholder="contoh@email.com atau username"
                                    required
                                    autofocus
                                >
                                @error('login')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="login_password" class="form-label fw-semibold">Password</label>
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none text-purple">Lupa password?</a>
                                </div>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        id="login_password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Masukkan password"
                                        required
                                    >
                                    <button class="btn btn-outline-secondary" type="button" data-toggle-password="login_password" aria-label="Tampilkan atau sembunyikan password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-secondary" for="remember">
                                    Ingat saya (Remember Me)
                                </label>
                            </div>

                            <button type="submit" class="btn btn-purple w-100 py-2 fw-semibold">
                                Masuk
                            </button>
                        </form>

                        <hr class="my-4">
                        <p class="text-center small text-muted mb-0">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="fw-semibold text-purple text-decoration-none">Daftar sebagai Peserta</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
