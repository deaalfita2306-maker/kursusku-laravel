@extends('layouts.app')

@section('title', 'Dashboard Admin — KursusKu')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge bg-purple text-white px-3 py-2 mb-2">Panel Administrator</span>
            <h1 class="h3 fw-bold mb-1">Dashboard Admin KursusKu</h1>
            <p class="text-muted mb-0">Ringkasan pengguna, kursus, dan aktivitas pendaftaran sistem.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('courses.create') }}" class="btn btn-purple">
                <i class="bi bi-plus-lg me-1"></i>Tambah Kursus
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-purple">
                <i class="bi bi-people me-1"></i>Kelola Pengguna
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="text-muted small fw-semibold mb-1">TOTAL PENGGUNA</div>
                    <div class="display-6 fw-bold text-purple">{{ $totalUsers }}</div>
                    <div class="small text-muted mt-1">{{ $totalAdmins }} Admin &bull; {{ $totalRegularUsers }} User</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="text-muted small fw-semibold mb-1">TOTAL KURSUS</div>
                    <div class="display-6 fw-bold text-dark">{{ $totalCourses }}</div>
                    <div class="small text-muted mt-1">{{ $openCourses }} Kursus Open</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="text-muted small fw-semibold mb-1">TOTAL PESERTA</div>
                    <div class="display-6 fw-bold text-dark">{{ $totalParticipants }}</div>
                    <div class="small text-muted mt-1">Data tabel participants</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="text-muted small fw-semibold mb-1">TOTAL ENROLLMENT</div>
                    <div class="display-6 fw-bold text-dark">{{ $totalEnrollments }}</div>
                    <div class="small text-muted mt-1">Pendaftaran kursus aktif</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                    <h2 class="h6 fw-bold mb-0">Pengguna Terbaru</h2>
                    <a href="{{ route('admin.users.index') }}" class="small text-decoration-none text-purple fw-semibold">Lihat Semua &rarr;</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-2">Nama</th>
                                    <th class="px-4 py-2">Role</th>
                                    <th class="px-4 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentUsers as $u)
                                    <tr>
                                        <td class="px-4 py-2">
                                            <div class="fw-semibold">{{ $u->name }}</div>
                                            <div class="small text-muted">{{ $u->email }}</div>
                                        </td>
                                        <td class="px-4 py-2">
                                            <span class="badge {{ $u->isAdmin() ? 'bg-purple' : 'bg-secondary' }}">{{ strtoupper($u->role) }}</span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <span class="badge {{ $u->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                                {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada pengguna.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                    <h2 class="h6 fw-bold mb-0">Aktivitas Enrollment Terbaru</h2>
                    <a href="{{ route('enrollments.index') }}" class="small text-decoration-none text-purple fw-semibold">Lihat Semua &rarr;</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-2">Peserta</th>
                                    <th class="px-4 py-2">Kursus</th>
                                    <th class="px-4 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentEnrollments as $e)
                                    <tr>
                                        <td class="px-4 py-2">
                                            <div class="fw-semibold">{{ $e->participant?->name ?? '-' }}</div>
                                            <div class="small text-muted">{{ $e->participant?->email ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-2">{{ $e->course?->name ?? '-' }}</td>
                                        <td class="px-4 py-2">
                                            <span class="badge bg-primary-subtle text-primary">{{ ucfirst($e->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada enrollment.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
