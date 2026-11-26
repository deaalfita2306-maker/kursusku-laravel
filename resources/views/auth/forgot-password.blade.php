@extends('layouts.app')

@section('title', 'Lupa Password — KursusKu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5 bg-white">
                    <h1 class="h4 fw-bold mb-2">Lupa Password</h1>
                    <p class="text-muted small mb-4">
                        Masukkan alamat email akun Anda. Sistem akan membuat token pemulihan dan mengirimkan tautan reset password.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger small" role="alert">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" novalidate>
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email Terdaftar</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="nama@email.com"
                                required
                                autofocus
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-purple w-100 py-2 fw-semibold">
                            Kirim Tautan Reset Password
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="small text-decoration-none text-purple">
                            &larr; Kembali ke halaman login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
