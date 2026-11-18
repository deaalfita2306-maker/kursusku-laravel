@extends('layouts.app')

@section('title', 'Detail Kursus ' . $course->code . ' — ' . ($siteName ?? 'KursusKu'))

@section('content')
<section class="section section-alt py-5">
    <div class="container">
        <!-- Breadcrumb & Navigasi Atas -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}" class="text-decoration-none">Daftar Kursus</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $course->code }}</li>
                    </ol>
                </nav>
                <h1 class="section-title mb-0">Rincian Data Kursus</h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1">
                    <span>&larr;</span> Kembali
                </a>
                <a href="{{ route('courses.edit', $course) }}" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm">
                    <span>&#9998;</span> Edit Kursus
                </a>
            </div>
        </div>

        <!-- Detail Card -->
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <!-- Card Header -->
                    <div class="card-header bg-white border-bottom p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-light text-dark border font-monospace fs-6 px-3 py-2">
                                {{ $course->code }}
                            </span>
                            <h2 class="h4 mb-0 fw-bold text-dark">{{ $course->name }}</h2>
                        </div>
                        <div>
                            @if ($course->status === 'open')
                                <span class="badge bg-success-subtle text-success border border-success fs-6 px-3 py-2">
                                    &#10003; Open (Pendaftaran Dibuka)
                                </span>
                            @elseif ($course->status === 'draft')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary fs-6 px-3 py-2">
                                    &#9998; Draft (Konsep)
                                </span>
                            @elseif ($course->status === 'closed')
                                <span class="badge bg-danger-subtle text-danger border border-danger fs-6 px-3 py-2">
                                    &times; Closed (Ditutup)
                                </span>
                            @else
                                <span class="badge bg-light text-dark border fs-6 px-3 py-2">
                                    {{ ucfirst($course->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4 p-md-5 bg-white">
                        <!-- Grid Informasi Utama -->
                        <div class="row g-4 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="p-3 bg-light rounded-3 border">
                                    <small class="text-muted d-block fw-semibold mb-1">BIAYA PELATIHAN</small>
                                    <div class="fs-4 fw-bold text-dark">
                                        Rp {{ number_format($course->fee, 0, ',', '.') }}
                                    </div>
                                    <small class="text-muted">Per peserta / program</small>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 bg-light rounded-3 border">
                                    <small class="text-muted d-block fw-semibold mb-1">KUOTA KAPASITAS</small>
                                    <div class="fs-4 fw-bold text-dark">
                                        {{ $course->quota }} Peserta
                                    </div>
                                    <small class="text-muted">Maksimal daya tampung kelas</small>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 bg-light rounded-3 border">
                                    <small class="text-muted d-block fw-semibold mb-1">TANGGAL MULAI</small>
                                    <div class="fs-4 fw-bold text-dark">
                                        {{ $course->start_date ? $course->start_date->format('d M Y') : '-' }}
                                    </div>
                                    <small class="text-muted">Jadwal pelaksanaan perdana</small>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi Materi -->
                        <div class="mb-4">
                            <h3 class="h6 fw-bold text-secondary text-uppercase mb-2">Deskripsi Kursus</h3>
                            <div class="p-3 bg-light rounded-3 border text-secondary leading-relaxed">
                                @if ($course->description)
                                    <p class="mb-0" style="white-space: pre-line;">{{ $course->description }}</p>
                                @else
                                    <span class="text-muted fst-italic">Belum ada deskripsi yang ditambahkan untuk kursus ini.</span>
                                @endif
                            </div>
                        </div>

                        <!-- Metadata Waktu -->
                        <div class="row g-3 pt-3 border-top text-muted small">
                            <div class="col-12 col-sm-6">
                                Ditambahkan pada: <strong>{{ $course->created_at ? $course->created_at->format('d M Y H:i') : '-' }}</strong>
                            </div>
                            <div class="col-12 col-sm-6 text-sm-end">
                                Terakhir diperbarui: <strong>{{ $course->updated_at ? $course->updated_at->format('d M Y H:i') : '-' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer: Aksi -->
                    <div class="card-footer bg-light border-top p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div>
                            <form 
                                action="{{ route('courses.destroy', $course) }}" 
                                method="POST" 
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus \'{{ $course->name }}\' ({{ $course->code }})? Tindakan ini tidak dapat dibatalkan.');"
                                class="d-inline"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    &#128465; Hapus Kursus
                                </button>
                            </form>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
                                &larr; Kembali ke Daftar
                            </a>
                            <a href="{{ route('courses.edit', $course) }}" class="btn btn-primary shadow-sm">
                                &#9998; Edit Kursus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
