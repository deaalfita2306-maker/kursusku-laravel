@extends('layouts.app')

@section('title', 'Tambah Pengguna — KursusKu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold mb-0">Tambah Pengguna Baru</h1>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali</a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5 bg-white">
                    <form method="POST" action="{{ route('admin.users.store') }}" novalidate>
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold">Username</label>
                                <input type="text" id="username" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" required>
                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Nomor HP</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="role" class="form-label fw-semibold">Role</label>
                                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="user" @selected(old('role', 'user') === 'user')>User</option>
                                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                                </select>
                                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', true))>
                                    <label class="form-check-label fw-semibold" for="is_active">Akun Aktif</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="user_password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <input type="password" id="user_password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    <button class="btn btn-outline-secondary" type="button" data-toggle-password="user_password"><i class="bi bi-eye"></i></button>
                                </div>
                                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="user_password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" id="user_password_confirmation" name="password_confirmation" class="form-control" required>
                                    <button class="btn btn-outline-secondary" type="button" data-toggle-password="user_password_confirmation"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-purple px-4">Simpan Pengguna</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
