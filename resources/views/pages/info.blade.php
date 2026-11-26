@extends('layouts.app')

@section('title', 'Informasi — KursusKu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5 bg-white">
                    <span class="badge bg-purple-subtle text-purple px-3 py-2 mb-3">Tentang Platform</span>
                    <h1 class="h2 fw-bold mb-3">Informasi Program KursusKu</h1>
                    <p class="text-secondary mb-4">
                        KursusKu adalah platform pembelajaran digital yang dirancang untuk membantu peserta menguasai keterampilan teknologi dan bisnis secara terstruktur bersama mentor praktisi.
                    </p>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <h2 class="h6 fw-bold text-purple mb-2">Cara Mengikuti Kursus</h2>
                                <p class="small text-muted mb-0">
                                    Buat akun peserta melalui halaman Registrasi, masuk ke akun Anda, lalu pilih kursus berstatus Open untuk mendaftar langsung dari halaman detail kursus.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <h2 class="h6 fw-bold text-purple mb-2">Kontak & Bantuan</h2>
                                <p class="small text-muted mb-0">
                                    Email: halo@kursusku.test<br>
                                    Jam Operasional: Senin - Jumat, 08:00 - 17:00 WIB
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('courses.index') }}" class="btn btn-purple">Lihat Daftar Kursus</a>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-outline-purple">Daftar Akun Baru</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
