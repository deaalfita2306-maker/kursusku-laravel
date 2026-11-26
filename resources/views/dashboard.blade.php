@extends('layouts.app')

@section('title', 'Dashboard Pengguna — KursusKu')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge bg-purple-subtle text-purple px-3 py-2 mb-2">Dashboard Peserta</span>
            <h1 class="h3 fw-bold mb-1">Selamat Datang, {{ $user->name }}</h1>
            <p class="text-muted mb-0">Kelola profil dan pantau kursus yang Anda ikuti di KursusKu.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('courses.index') }}" class="btn btn-purple">
                <i class="bi bi-book me-1"></i>Jelajahi Kursus
            </a>
            <a href="{{ route('profile.show') }}" class="btn btn-outline-purple">
                <i class="bi bi-person me-1"></i>Profil Saya
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="text-muted small fw-semibold mb-1">KURSUS DIIKUTI</div>
                    <div class="display-6 fw-bold text-purple">{{ $myEnrollments->count() }}</div>
                    <div class="small text-muted mt-1">Program pelatihan terdaftar</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="text-muted small fw-semibold mb-1">KURSUS TERBUKA</div>
                    <div class="display-6 fw-bold text-dark">{{ $openCoursesCount }}</div>
                    <div class="small text-muted mt-1">Tersedia untuk pendaftaran</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="text-muted small fw-semibold mb-1">INFORMASI AKUN</div>
                    <div class="fw-bold text-dark">{{ $user->email }}</div>
                    <div class="small text-muted mt-1">Username: {{ $user->username ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
            <h2 class="h5 fw-bold mb-0">Kursus yang Anda Ikuti</h2>
            <a href="{{ route('courses.index') }}" class="small text-decoration-none text-purple fw-semibold">Lihat Semua Kursus &rarr;</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Nama Kursus</th>
                            <th class="px-4 py-3">Tanggal Mulai</th>
                            <th class="px-4 py-3">Status Pendaftaran</th>
                            <th class="px-4 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($myEnrollments as $enrollment)
                            <tr>
                                <td class="px-4 py-3 font-monospace">{{ $enrollment->course?->code ?? '-' }}</td>
                                <td class="px-4 py-3 fw-semibold">{{ $enrollment->course?->name ?? 'Kursus tidak ditemukan' }}</td>
                                <td class="px-4 py-3 text-muted">
                                    {{ $enrollment->course?->start_date ? $enrollment->course->start_date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    @if ($enrollment->course)
                                        <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-sm btn-outline-purple">Detail</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    Anda belum mengikuti kursus apa pun. <a href="{{ route('courses.index') }}" class="text-purple fw-semibold">Cari kursus sekarang</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
