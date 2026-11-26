@extends('layouts.app')

@section('title', 'Manajemen Pengguna — KursusKu')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge bg-purple text-white px-3 py-2 mb-2">Administrasi</span>
            <h1 class="h3 fw-bold mb-0">Manajemen Pengguna</h1>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-purple">
            <i class="bi bi-person-plus me-1"></i>Tambah Pengguna
        </a>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-4">
        <div class="col-md-6">
            <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Cari nama, username, atau email...">
        </div>
        <div class="col-md-3">
            <select name="role" class="form-select">
                <option value="">Semua Role</option>
                <option value="admin" @selected($role === 'admin')>Admin</option>
                <option value="user" @selected($role === 'user')>User</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-purple">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama & Username</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        <tr>
                            <td class="px-4 py-3 text-muted">{{ $users->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3">
                                <div class="fw-semibold">{{ $u->name }}</div>
                                <div class="small text-muted">{{ '@' . ($u->username ?? '-') }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $u->email }}</td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $u->isAdmin() ? 'bg-purple' : 'bg-secondary' }}">{{ strtoupper($u->role) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $u->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                    {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                @if (auth()->id() !== $u->id)
                                    <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Tidak ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>
@endsection
