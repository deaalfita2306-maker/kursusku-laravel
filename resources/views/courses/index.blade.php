@extends('layouts.app')

@section('title', 'Daftar Kursus — KursusKu')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Daftar Kursus</h1>
            <p class="text-muted small mb-0">Temukan program pelatihan digital yang sesuai dengan kebutuhan Anda.</p>
        </div>
        @auth
            @if (auth()->user()->isAdmin())
                <a href="{{ route('courses.create') }}" class="btn btn-purple">
                    + Tambah Kursus
                </a>
            @endif
        @endauth
    </div>

    <form method="GET" action="{{ route('courses.index') }}" class="row g-2 mb-4">
        <div class="col-md-6">
            <input type="search" id="live-search" name="q" value="{{ $search }}" class="form-control" placeholder="Cari kode atau nama kursus">
        </div>
        <div class="col-md-3">
            <select id="status-filter" name="status" class="form-select">
                <option value="">Semua status</option>
                <option value="open" @selected($status === 'open')>Open</option>
                <option value="closed" @selected($status === 'closed')>Closed</option>
                @auth
                    @if (auth()->user()->isAdmin())
                        <option value="draft" @selected($status === 'draft')>Draft</option>
                    @endif
                @endauth
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-purple">Cari</button>
            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px" class="px-3 py-3">No</th>
                        <th class="px-3 py-3">Kode</th>
                        <th class="px-3 py-3">Nama Kursus</th>
                        <th class="px-3 py-3">Fee</th>
                        <th class="px-3 py-3">Status</th>
                        <th style="width: 220px" class="px-3 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td class="px-3 py-3">{{ $courses->firstItem() + $loop->index }}</td>
                            <td class="px-3 py-3 font-monospace">{{ $course->code }}</td>
                            <td class="px-3 py-3 fw-semibold">{{ $course->name }}</td>
                            <td class="px-3 py-3">Rp {{ number_format($course->fee, 0, ',', '.') }}</td>
                            <td class="px-3 py-3">
                                <span class="badge
                                    @if($course->status === 'open') bg-success
                                    @elseif($course->status === 'closed') bg-danger
                                    @elseif($course->status === 'draft') bg-warning text-dark
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-end">
                                <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-purple">Detail</a>
                                @auth
                                    @if (auth()->user()->isAdmin())
                                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kursus ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    @endif
                                @endauth
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Belum Ada Data Kursus
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">
            Menampilkan {{ $courses->firstItem() ?? 0 }}–{{ $courses->lastItem() ?? 0 }} dari {{ $courses->total() }} kursus
        </small>
        {{ $courses->links() }}
    </div>
</div>
@endsection
