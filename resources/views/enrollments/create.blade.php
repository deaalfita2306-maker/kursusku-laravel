@extends('layouts.app')

@section('title', 'Pendaftaran Enrollment Kursus — ' . ($siteName ?? 'KursusKu'))

@section('content')
<section class="section section-alt py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <!-- Section Header -->
                <div class="section-header text-center mb-4">
                    <span class="section-tag">Relasi Kursus & Peserta</span>
                    <h1 class="section-title">Pendaftaran Enrollment</h1>
                    <p class="section-desc">
                        Hubungkan data peserta dengan program kursus yang aktif (berstatus Open) ke dalam sistem relasional KursusKu.
                    </p>
                </div>

                <!-- Form Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5 bg-white">
                        <form method="POST" action="{{ route('enrollments.store') }}" novalidate>
                            @csrf

                            <!-- Dropdown Participant -->
                            <div class="mb-4">
                                <label for="participant_id" class="form-label fw-bold text-secondary">
                                    Pilih Peserta (Participant) <span class="text-danger">*</span>
                                </label>
                                <select 
                                    class="form-select form-select-lg @error('participant_id') is-invalid @enderror" 
                                    id="participant_id" 
                                    name="participant_id" 
                                    required
                                >
                                    <option value="">-- Pilih Peserta Terdaftar --</option>
                                    @foreach ($participants as $p)
                                        <option value="{{ $p->id }}" {{ old('participant_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ $p->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('participant_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">Pilih peserta yang telah terdaftar di database.</div>
                            </div>

                            <!-- Dropdown Course -->
                            <div class="mb-4">
                                <label for="course_id" class="form-label fw-bold text-secondary">
                                    Pilih Program Kursus (Course) <span class="text-danger">*</span>
                                </label>
                                <select 
                                    class="form-select form-select-lg @error('course_id') is-invalid @enderror" 
                                    id="course_id" 
                                    name="course_id" 
                                    required
                                >
                                    <option value="">-- Pilih Kursus (Status: Open) --</option>
                                    @foreach ($courses as $c)
                                        <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->code }} — {{ $c->name }} (Rp {{ number_format($c->fee, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">Hanya kursus yang berstatus <strong>Open</strong> yang dapat dipilih.</div>
                            </div>

                            <!-- Informasi Status Otomatis -->
                            <div class="alert alert-light border p-3 rounded-3 mb-4 d-flex align-items-center gap-2">
                                <span class="badge bg-purple-subtle text-purple border">Info</span>
                                <small class="text-muted">
                                    Status pendaftaran akan secara otomatis ditetapkan sebagai <strong>registered</strong> oleh sistem.
                                </small>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 pt-2 border-top">
                                <a href="{{ route('enrollments.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                                    Simpan Enrollment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Link Cepat ke Laporan -->
                <div class="text-center mt-3">
                    <a href="{{ route('enrollments.index') }}" class="text-decoration-none small text-muted">
                        &larr; Lihat Laporan Seluruh Enrollment
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
