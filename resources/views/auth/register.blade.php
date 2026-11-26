@extends('layouts.app')

@section('title', 'Registrasi Akun — KursusKu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <span class="badge bg-purple-subtle text-purple px-3 py-2 mb-2">Pendaftaran Peserta Baru</span>
                        <h1 class="h3 fw-bold mb-1">Buat Akun KursusKu</h1>
                        <p class="text-muted small mb-0">Lengkapi data diri Anda untuk mulai mengikuti kursus.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger small" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.process') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Nama lengkap Anda" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                                <input type="text" id="username" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" placeholder="misal: budi_santoso" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Nomor HP</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="0812xxxxxxxx">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reg_password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="reg_password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                                <button class="btn btn-outline-secondary" type="button" data-toggle-password="reg_password" aria-label="Tampilkan atau sembunyikan password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="reg_password_confirmation" class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="reg_password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                                <button class="btn btn-outline-secondary" type="button" data-toggle-password="reg_password_confirmation" aria-label="Tampilkan atau sembunyikan konfirmasi password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-purple w-100 py-2 fw-semibold">
                            Daftar Sekarang
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center small text-muted mb-0">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}" class="fw-semibold text-purple text-decoration-none">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
