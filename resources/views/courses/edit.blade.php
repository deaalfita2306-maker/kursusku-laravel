@extends('layouts.app')

@section('title', 'Edit Kursus ' . $course->code . ' — ' . ($siteName ?? 'KursusKu'))

@section('content')
<section class="section section-alt py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <!-- Section Header -->
                <div class="section-header text-center mb-4">
                    <span class="section-tag">Formulir Perubahan Data</span>
                    <h1 class="section-title">Edit Kursus</h1>
                    <p class="section-desc">
                        Perbarui rincian informasi kursus <strong>{{ $course->code }} — {{ $course->name }}</strong>.
                    </p>
                </div>

                <!-- Form Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5 bg-white">
                        <form method="POST" action="{{ route('courses.update', $course) }}" novalidate>
                            @csrf
                            @method('PUT')

                            <!-- Baris 1: Kode & Nama Kursus -->
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-4">
                                    <label for="code" class="form-label fw-bold text-secondary">
                                        Kode Kursus <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('code') is-invalid @enderror" 
                                        id="code" 
                                        name="code" 
                                        value="{{ old('code', $course->code) }}" 
                                        placeholder="Misal: WEB-101"
                                        maxlength="20"
                                        required
                                    >
                                    @error('code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">Maks. 20 karakter, harus unik.</div>
                                </div>

                                <div class="col-12 col-md-8">
                                    <label for="name" class="form-label fw-bold text-secondary">
                                        Nama Kursus <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('name') is-invalid @enderror" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name', $course->name) }}" 
                                        placeholder="Misal: Web Dasar Modern"
                                        minlength="3"
                                        maxlength="120"
                                        required
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">Min. 3 karakter, maks. 120 karakter.</div>
                                </div>
                            </div>

                            <!-- Baris 2: Deskripsi Kursus -->
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold text-secondary">
                                    Deskripsi Kursus
                                </label>
                                <textarea 
                                    class="form-control @error('description') is-invalid @enderror" 
                                    id="description" 
                                    name="description" 
                                    rows="3" 
                                    maxlength="2000"
                                    placeholder="Jelaskan ringkasan materi, target capaian, atau prasyarat kursus..."
                                >{{ old('description', $course->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">Maksimal 2000 karakter.</div>
                            </div>

                            <!-- Baris 3: Biaya, Kuota, & Status -->
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-4">
                                    <label for="fee" class="form-label fw-bold text-secondary">
                                        Biaya (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('fee') is-invalid @enderror" 
                                        id="fee" 
                                        name="fee" 
                                        value="{{ old('fee', $course->fee) }}" 
                                        min="0"
                                        step="1000"
                                        required
                                    >
                                    @error('fee')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">Minimal 0 (gratis).</div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="quota" class="form-label fw-bold text-secondary">
                                        Kuota Peserta <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('quota') is-invalid @enderror" 
                                        id="quota" 
                                        name="quota" 
                                        value="{{ old('quota', $course->quota) }}" 
                                        min="1"
                                        max="500"
                                        required
                                    >
                                    @error('quota')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">Rentang kuota 1 - 500.</div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="status" class="form-label fw-bold text-secondary">
                                        Status Kursus <span class="text-danger">*</span>
                                    </label>
                                    <select 
                                        class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required
                                    >
                                        <option value="" disabled>-- Pilih Status --</option>
                                        <option value="draft" {{ old('status', $course->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="open" {{ old('status', $course->status) === 'open' ? 'selected' : '' }}>Open (Buka Pendaftaran)</option>
                                        <option value="closed" {{ old('status', $course->status) === 'closed' ? 'selected' : '' }}>Closed (Tutup)</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Baris 4: Tanggal Mulai -->
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <label for="start_date" class="form-label fw-bold text-secondary">
                                        Tanggal Mulai <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('start_date') is-invalid @enderror" 
                                        id="start_date" 
                                        name="start_date" 
                                        value="{{ old('start_date', $course->start_date ? $course->start_date->format('Y-m-d') : '') }}" 
                                        required
                                    >
                                    @error('start_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 pt-3 border-top">
                                <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-secondary px-4 py-2">
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
