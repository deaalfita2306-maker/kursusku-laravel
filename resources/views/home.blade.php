@extends('layouts.app')

@section('title', $siteName . ' — Platform Pembelajaran Kursus Digital')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <span class="pill-badge">Pendidikan Berkualitas</span>
                <h1 class="hero-title">
                    Bangun Keahlian Masa Depan Bersama <span class="text-purple">{{ $siteName }}</span>
                </h1>
                <p class="hero-tagline">
                    {{ $tagline }}
                </p>
                <div class="hero-cta">
                    <a href="#katalog" class="btn btn-primary">Lihat Katalog Kursus</a>
                    <a href="{{ route('enrollments.create') }}" class="btn btn-outline">Pendaftaran Online</a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-num">6+</span>
                        <span class="stat-label">Pilihan Kursus Relevan</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">100%</span>
                        <span class="stat-label">Kurikulum Praktis</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">Terarah</span>
                        <span class="stat-label">Sertifikat Kelulusan</span>
                    </div>
                </div>
            </div>

            <div class="hero-media-card">
                <img 
                    src="{{ asset('images/hero-kursus.jpg') }}" 
                    alt="Suasana pembelajaran interaktif di KursusKu" 
                    class="hero-image"
                >
                <div class="media-caption">
                    <div>
                        <div class="media-caption-title">Kelas Interaktif & Terbimbing</div>
                        <div class="media-caption-sub">Kombinasi teori fondasi dan studi kasus riil</div>
                    </div>
                    <span class="status-badge status-available">Batch Aktif</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Keunggulan Section -->
<section id="keunggulan" class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Mengapa KursusKu</span>
            <h2 class="section-title">Keunggulan Belajar Kami</h2>
            <p class="section-desc">
                Fokus pada materi fundamental terapan untuk mendukung percepatan pemahaman peserta didik.
            </p>
        </div>

        <div class="advantages-grid">
            @foreach ($advantages as $advantage)
                <div class="advantage-card">
                    <div class="advantage-icon-wrapper">&bull;</div>
                    <h3 class="advantage-title">{{ $advantage['title'] }}</h3>
                    <p class="advantage-desc">{{ $advantage['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Katalog Kursus Section -->
<section id="katalog" class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Katalog Pembelajaran</span>
            <h2 class="section-title">Pilihan Kursus Unggulan</h2>
            <p class="section-desc">
                Pilih program kelas yang sesuai dengan target kompetensi dan kebutuhan belajar Anda.
            </p>
        </div>

        <div class="courses-grid">
            @foreach ($featured as $course)
                <article class="course-card">
                    <div>
                        <div class="card-top">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span class="course-code">{{ $course['code'] }}</span>
                                <span class="course-category">{{ $course['category'] }}</span>
                            </div>
                            <span class="status-badge {{ $course['status'] === 'Tersedia' ? 'status-available' : 'status-full' }}">
                                {{ $course['status'] }}
                            </span>
                        </div>

                        <h3 class="course-title">{{ $course['name'] }}</h3>
                        <p class="course-description">{{ $course['description'] }}</p>

                        <div class="course-meta">
                            <div class="meta-row">
                                <span class="meta-label">Mulai Kelas:</span>
                                <span class="meta-value">{{ date('d M Y', strtotime($course['start_date'])) }}</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-label">Peserta Terdaftar:</span>
                                <span class="meta-value">{{ $course['participants'] }} / {{ $course['capacity'] }} Orang</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-label">Sisa Kursi:</span>
                                <span class="meta-value">{{ $course['seats_left'] }} Kursi</span>
                            </div>
                            <div class="progress-track" aria-label="Keterisian Kelas">
                                <div 
                                    class="progress-bar" 
                                    style="width: {{ ($course['participants'] / $course['capacity']) * 100 }}%;"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="price-box">
                            <span class="price-label">Investasi</span>
                            <span class="price-amount">Rp {{ number_format($course['price'], 0, ',', '.') }}</span>
                        </div>
                        <div>
                            @if ($course['status'] === 'Tersedia')
                                <a href="{{ route('enrollments.create') }}" class="btn btn-primary btn-sm">Daftar Kelas</a>
                            @else
                                <span class="btn btn-disabled btn-sm">Penuh</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<!-- Fasilitas & Keuntungan Belajar -->
<section id="fasilitas" class="section">
    <div class="container">
        <div class="facilities-banner">
            <h2 class="facilities-banner-title">Fasilitas Standar Pembelajaran</h2>
            <p class="facilities-banner-sub">
                Setiap peserta pada program kursus KursusKu berhak mendapatkan sarana pendukung berikut:
            </p>
            <ul class="facilities-list">
                @foreach ($facilities as $facility)
                    <li class="facility-item">
                        <span class="facility-check">&#10003;</span>
                        <span>{{ $facility }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Alur Pendaftaran -->
        <div id="alur">
            <div class="section-header">
                <span class="section-tag">Tahapan Mudah</span>
                <h2 class="section-title">Cara Mendaftar Kursus</h2>
                <p class="section-desc">
                    Empat langkah sederhana untuk memulai perjalanan peningkatan keahlian digital Anda.
                </p>
            </div>

            <div class="steps-grid">
                @foreach ($steps as $step)
                    <div class="step-card">
                        <div class="step-num">{{ $step['number'] }}</div>
                        <h3 class="step-title">{{ $step['title'] }}</h3>
                        <p class="step-desc">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Kontak & Informasi -->
<section id="kontak" class="section section-alt">
    <div class="container">
        <div class="contact-card">
            <div>
                <h2 class="contact-info-title">Pusat Layanan & Konsultasi Kelas</h2>
                <p class="contact-info-desc">
                    Membutuhkan panduan pemilihan kelas atau informasi ketersediaan jadwal pembelajaran kelompok? Tim KursusKu siap membantu Anda.
                </p>
                <p class="contact-info-desc" style="margin-bottom: 0;">
                    Jam Operasional: Senin &ndash; Jumat, 08.00 &ndash; 17.00 WIB.
                </p>
            </div>
            <div class="contact-actions">
                <div class="contact-channel">
                    <div>
                        <span class="channel-label">Alamat Surel Resmi</span>
                        <span class="channel-val">info@kursusku.test</span>
                    </div>
                    <a href="mailto:info@kursusku.test" class="btn btn-outline btn-sm">Kirim Pesan</a>
                </div>
                <div class="contact-channel">
                    <div>
                        <span class="channel-label">Referensi Teknologi</span>
                        <span class="channel-val">Laravel &amp; PHP Modern</span>
                    </div>
                    <a href="https://laravel.com" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm">Dokumentasi</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
