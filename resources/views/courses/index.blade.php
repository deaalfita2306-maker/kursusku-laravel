@extends('layouts.app')

@section('title', 'Daftar Kursus — ' . ($siteName ?? 'KursusKu'))

@section('content')
<section class="section section-alt py-5">
    <div class="container">
        <!-- Section Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <span class="section-tag">Manajemen Kursus</span>
                <h1 class="section-title mb-1">Daftar Kursus</h1>
                <p class="section-desc mb-0">
                    Kelola data program kursus yang tersimpan di database MySQL KursusKu.
                </p>
            </div>
            <div>
                <a href="{{ route('courses.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 px-3 py-2 shadow-sm">
                    <span class="fw-bold">+</span>
                    <span>Tambah Kursus</span>
                </a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                
                {{-- 🔴 TAMBAHAN 1: FORM SEARCH & FILTER (Method GET, Tanpa @csrf) --}}
                <form method="GET" action="{{ route('courses.index') }}" class="row g-3 mb-4">
                    <div class="col-md-5">
                        <input type="search" name="q" value="{{ $search ?? '' }}" 
                               class="form-control" placeholder="Cari kode atau nama kursus..." 
                               id="live-search"> {{-- ID ini untuk persiapan Ajax nanti --}}
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select" id="status-filter"> {{-- ID untuk Ajax --}}
                            <option value="">Semua status</option>
                            <option value="open" @selected(($status ?? '') === 'open')>Open</option>
                            <option value="closed" @selected(($status ?? '') === 'closed')>Closed</option>
                            <option value="draft" @selected(($status ?? '') === 'draft')>Draft</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary w-100">
                            Reset
                        </a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th scope="col" class="py-3 px-3 text-center" style="width: 50px;">No</th>
                                <th scope="col" class="py-3 px-3">Kode</th>
                                <th scope="col" class="py-3 px-3">Nama Kursus</th>
                                <th scope="col" class="py-3 px-3">Biaya</th>
                                <th scope="col" class="py-3 px-3 text-center">Kuota</th>
                                <th scope="col" class="py-3 px-3">Tanggal Mulai</th>
                                <th scope="col" class="py-3 px-3 text-center">Status</th>
                                <th scope="col" class="py-3 px-3 text-center" style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($courses as $course)
                                <tr>
                                    <td class="py-3 px-3 text-center text-muted fw-semibold">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <a href="{{ route('courses.show', $course) }}" class="text-decoration-none">
                                            <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                                {{ $course->code }}
                                            </span>
                                        </a>
                                    </td>
                                    <td class="py-3 px-3">
                                        <a href="{{ route('courses.show', $course) }}" class="fw-bold text-dark text-decoration-none d-block">
                                            {{ $course->name }}
                                        </a>
                                        @if ($course->description)
                                            <small class="text-muted d-block text-truncate" style="max-width: 260px;">
                                                {{ $course->description }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 fw-semibold text-secondary">
                                        Rp {{ number_format($course->fee, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="badge bg-light text-dark border px-2 py-1">
                                            {{ $course->quota }} Peserta
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-secondary">
                                        {{ $course->start_date ? $course->start_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        @if ($course->status === 'open')
                                            <span class="badge bg-success-subtle text-success border border-success px-2 py-1">Open</span>
                                        @elseif ($course->status === 'draft')
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1">Draft</span>
                                        @elseif ($course->status === 'closed')
                                            <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">Closed</span>
                                        @else
                                            <span class="badge bg-light text-dark border px-2 py-1">{{ ucfirst($course->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary btn-sm px-2 py-1" title="Lihat Detail">Detail</a>
                                            <a href="{{ route('courses.edit', $course) }}" class="btn btn-outline-secondary btn-sm px-2 py-1" title="Edit">Edit</a>
                                            <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus \'{{ $course->name }}\' ({{ $course->code }})?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-2 py-1" title="Hapus">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- 🔴 TAMBAHAN 2: EMPTY STATE KHUSUS PENCARIAN (Bukan "Database Kosong") --}}
                                <tr>
                                    <td colspan="8" class="py-5 text-center text-muted">
                                        <div class="py-4">
                                            <div class="display-6 text-muted mb-3">🔍</div>
                                            <h5 class="fw-bold text-secondary">Tidak Ada Hasil Pencarian</h5>
                                            <p class="text-muted mb-3">
                                                Tidak ada kursus yang sesuai dengan kata kunci atau filter yang Anda masukkan.
                                            </p>
                                            <a href="{{ route('courses.index') }}" class="btn btn-outline-primary btn-sm">
                                                Reset Pencarian
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- 🔴 TAMBAHAN 3: PAGINATION LINKS --}}
            @if ($courses->hasPages())
                <div class="card-footer bg-white border-top py-3 px-4">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection