@extends('layouts.app')

@section('title', 'Laporan Data Enrollment — ' . ($siteName ?? 'KursusKu'))

@section('content')
<section class="section section-alt py-5">
    <div class="container">
        <!-- Section Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <span class="section-tag">Laporan Relasional</span>
                <h1 class="section-title mb-1">Data Enrollment</h1>
                <p class="section-desc mb-0">
                    Laporan pendaftaran peserta pada program kursus berbasis relasi Eloquent MySQL.
                </p>
            </div>
            <div>
                <a href="{{ route('enrollments.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 px-3 py-2 shadow-sm">
                    <span class="fw-bold">+</span>
                    <span>Tambah Enrollment</span>
                </a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th scope="col" class="py-3 px-3 text-center" style="width: 50px;">No</th>
                                <th scope="col" class="py-3 px-3">Nama Participant</th>
                                <th scope="col" class="py-3 px-3">Email Participant</th>
                                <th scope="col" class="py-3 px-3">Nama Course</th>
                                <th scope="col" class="py-3 px-3">Tanggal Mulai</th>
                                <th scope="col" class="py-3 px-3 text-center">Status Enrollment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($enrollments as $enrollment)
                                <tr>
                                    <td class="py-3 px-3 text-center text-muted fw-semibold">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="fw-bold text-dark">
                                            {{ $enrollment->participant?->name ?? '-' }}
                                        </div>
                                        @if ($enrollment->participant?->phone)
                                            <small class="text-muted d-block">
                                                &#128222; {{ $enrollment->participant->phone }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-secondary font-monospace small">
                                        {{ $enrollment->participant?->email ?? '-' }}
                                    </td>
                                    <td class="py-3 px-3">
                                        @if ($enrollment->course)
                                            <span class="badge bg-light text-dark border font-monospace me-1">
                                                {{ $enrollment->course->code }}
                                            </span>
                                            <span class="fw-semibold text-dark">
                                                {{ $enrollment->course->name }}
                                            </span>
                                        @else
                                            <span class="text-muted fst-italic">Kursus tidak ditemukan</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-secondary">
                                        {{-- Tanggal mulai dibaca murni dari relasi Course --}}
                                        {{ $enrollment->course?->start_date ? $enrollment->course->start_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        @if ($enrollment->status === 'registered')
                                            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1">
                                                Registered
                                            </span>
                                        @elseif ($enrollment->status === 'confirmed')
                                            <span class="badge bg-success-subtle text-success border border-success px-3 py-1">
                                                Confirmed
                                            </span>
                                        @elseif ($enrollment->status === 'cancelled')
                                            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-1">
                                                Cancelled
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark border px-3 py-1">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-5 text-center text-muted">
                                        <div class="py-4">
                                            <div class="display-6 text-muted mb-3">&#128221;</div>
                                            <h5 class="fw-bold text-secondary">Belum Ada Data Enrollment</h5>
                                            <p class="text-muted mb-3">Belum ada peserta yang didaftarkan ke kursus. Silakan tambahkan enrollment pertama.</p>
                                            <a href="{{ route('enrollments.create') }}" class="btn btn-outline-primary btn-sm">
                                                + Tambah Enrollment Baru
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($enrollments->isNotEmpty())
                <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Total <strong>{{ $enrollments->count() }}</strong> enrollment aktif tersimpan di database MySQL.
                    </small>
                    <a href="{{ route('enrollments.create') }}" class="btn btn-sm btn-link text-decoration-none">
                        + Daftarkan Peserta Lain
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
