@extends('layouts.app')

@section('title', 'Pendaftaran Kursus — ' . ($siteName ?? 'KursusKu'))

@section('content')
<section class="section section-alt py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <!-- Section Header -->
                <div class="section-header text-center mb-4">
                    <span class="section-tag">Formulir Pendaftaran</span>
                    <h1 class="section-title">Pendaftaran Kursus</h1>
                    <p class="section-desc">
                        Lengkapi identitas Anda dan pilih program kursus untuk memeriksa ketersediaan kursi secara real-time.
                    </p>
                </div>

                <!-- Form Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5 bg-white">
                        <form method="POST" action="{{ route('enrollments.preview') }}" novalidate>
                            @csrf

                            <!-- Field Nama -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold text-secondary">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    placeholder="Masukkan nama lengkap Anda"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Field Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold text-secondary">
                                    Alamat Email <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    placeholder="nama@email.com"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Pilihan Kursus -->
                            <div class="mb-3">
                                <label for="course_id" class="form-label fw-bold text-secondary">
                                    Pilihan Kursus <span class="text-danger">*</span>
                                </label>
                                <select 
                                    class="form-select form-select-lg @error('course_id') is-invalid @enderror" 
                                    id="course_id" 
                                    name="course_id"
                                    required
                                >
                                    <option value="">-- Pilih Program Kursus --</option>
                                    @foreach ($courses as $c)
                                        <option value="{{ data_get($c, 'id') }}" {{ old('course_id') == data_get($c, 'id') ? 'selected' : '' }}>
                                            {{ data_get($c, 'name') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Area Informasi Ketersediaan Kursi (jQuery Ajax) -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted small d-block">
                                    Status Ketersediaan Kursi:
                                </label>
                                <div id="availability" class="rounded-3">
                                    <div class="alert alert-info py-2 px-3 mb-0 text-sm">
                                        Silakan pilih kursus untuk melihat sisa ketersediaan kursi.
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Kirim Pendaftaran -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                    Kirim Pendaftaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Box Bawah -->
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        Pengecekan sisa kursi dilakukan secara asinkron menggunakan jQuery Ajax tanpa memuat ulang halaman.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
