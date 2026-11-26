@extends('layouts.app')

@section('title', 'Profil Saya — KursusKu')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="badge bg-purple-subtle text-purple px-3 py-2 mb-2">Pengaturan Akun</span>
            <h1 class="h3 fw-bold mb-0">Profil Pengguna</h1>
        </div>
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    @if ($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto Profil {{ $user->name }}" class="rounded-circle object-fit-cover mb-3 border" style="width: 110px; height: 110px;">
                    @else
                        <div class="rounded-circle bg-purple text-white d-inline-flex align-items-center justify-content-center fw-bold mb-3" style="width: 110px; height: 110px; font-size: 2.2rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <h2 class="h5 fw-bold mb-1">{{ $user->name }}</h2>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    <span class="badge {{ $user->isAdmin() ? 'bg-purple' : 'bg-secondary' }} text-uppercase px-3 py-1 mb-3">
                        Role: {{ $user->role }}
                    </span>

                    <hr>
                    <div class="text-start small">
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">Username</span>
                            <span class="fw-semibold">{{ $user->username ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">Nomor HP</span>
                            <span class="fw-semibold">{{ $user->phone ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">Login Terakhir</span>
                            <span class="fw-semibold">{{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                    </div>

                    <a href="#form-edit-profil" class="btn btn-purple w-100 mt-3">
                        <i class="bi bi-pencil-square me-1"></i>Edit Profil
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4" id="form-edit-profil">
                <div class="card-header bg-white border-bottom p-4">
                    <h3 class="h5 fw-bold mb-0">Ubah Informasi Profil</h3>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold">Username</label>
                                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" class="form-control @error('username') is-invalid @enderror" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Nomor HP</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="profile_photo" class="form-label fw-semibold">Foto Profil (Opsional)</label>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="form-control @error('profile_photo') is-invalid @enderror">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format: JPG, PNG, WEBP. Maksimal 2MB.</div>
                        </div>

                        <button type="submit" class="btn btn-purple px-4">Simpan Perubahan Profil</button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h3 class="h5 fw-bold mb-0">Ubah Password</h3>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('profile.password') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                <button class="btn btn-outline-secondary" type="button" data-toggle-password="current_password" aria-label="Tampilkan atau sembunyikan password saat ini">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="new_password" class="form-label fw-semibold">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" id="new_password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                                    <button class="btn btn-outline-secondary" type="button" data-toggle-password="new_password" aria-label="Tampilkan atau sembunyikan password baru">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" id="new_password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                                    <button class="btn btn-outline-secondary" type="button" data-toggle-password="new_password_confirmation" aria-label="Tampilkan atau sembunyikan konfirmasi password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-outline-purple px-4">Perbarui Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
