@extends('layouts.app')

@section('title', 'Reset Password — KursusKu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5 bg-white">
                    <h1 class="h4 fw-bold mb-2">Atur Ulang Password</h1>
                    <p class="text-muted small mb-4">
                        Masukkan password baru untuk akun Anda.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger small" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reset_password" class="form-label fw-semibold">Password Baru</label>
                            <div class="input-group">
                                <input type="password" id="reset_password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                                <button class="btn btn-outline-secondary" type="button" data-toggle-password="reset_password" aria-label="Tampilkan atau sembunyikan password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="reset_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" id="reset_password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                                <button class="btn btn-outline-secondary" type="button" data-toggle-password="reset_password_confirmation" aria-label="Tampilkan atau sembunyikan konfirmasi password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-purple w-100 py-2 fw-semibold">
                            Simpan Password Baru
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
