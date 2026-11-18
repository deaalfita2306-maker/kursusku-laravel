<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $siteName = 'KursusKu';
        $tagline = 'Belajar, daftar, dan kelola kursus dalam satu tempat.';

        $featured = [
            [
                'code' => 'WEB-01',
                'name' => 'Web Development',
                'category' => 'Teknologi',
                'description' => 'Pelajari fondasi pengembangan web interaktif mulai dari struktur HTML, styling modern CSS, hingga dasar logika JavaScript terapan.',
                'price' => 750000,
                'participants' => 18,
                'capacity' => 20,
                'status' => 'Tersedia',
                'seats_left' => 2,
                'start_date' => '2026-10-05',
            ],
            [
                'code' => 'UIUX-02',
                'name' => 'UI/UX Design',
                'category' => 'Desain',
                'description' => 'Kuasai prinsip perancangan antarmuka, riset pengguna, wireframing, hierarki visual, dan pembuatan prototype interaktif.',
                'price' => 850000,
                'participants' => 8,
                'capacity' => 20,
                'status' => 'Tersedia',
                'seats_left' => 12,
                'start_date' => '2026-10-30',
            ],
            [
                'code' => 'LAR-03',
                'name' => 'Laravel Dasar',
                'category' => 'Teknologi',
                'description' => 'Pahami arsitektur MVC, routing terstruktur, controller, Blade templating, dan pengelolaan data backend menggunakan framework Laravel.',
                'price' => 500000,
                'participants' => 14,
                'capacity' => 20,
                'status' => 'Tersedia',
                'seats_left' => 6,
                'start_date' => '2026-11-01',
            ],
            [
                'code' => 'DATA-04',
                'name' => 'Data Analysis',
                'category' => 'Teknologi',
                'description' => 'Eksplorasi teknik pengolahan data terstruktur, formulasi kalkulasi bisnis, analisis tren, dan visualisasi wawasan performa.',
                'price' => 900000,
                'participants' => 15,
                'capacity' => 20,
                'status' => 'Tersedia',
                'seats_left' => 5,
                'start_date' => '2026-10-25',
            ],
            [
                'code' => 'MKT-05',
                'name' => 'Digital Marketing',
                'category' => 'Bisnis',
                'description' => 'Strategi pemasaran digital menyeluruh mencakup optimasi pencarian, kampanye media sosial, analisis audiens, dan metrik konversi.',
                'price' => 800000,
                'participants' => 20,
                'capacity' => 20,
                'status' => 'Penuh',
                'seats_left' => 0,
                'start_date' => '2026-10-15',
            ],
            [
                'code' => 'DSG-06',
                'name' => 'Desain Grafis',
                'category' => 'Desain',
                'description' => 'Kembangkan kompetensi visual melalui penguasaan komposisi tata letak, perpaduan warna, tipografi, dan aset branding profesional.',
                'price' => 650000,
                'participants' => 12,
                'capacity' => 20,
                'status' => 'Tersedia',
                'seats_left' => 8,
                'start_date' => '2026-10-10',
            ],
        ];

        $advantages = [
            [
                'title' => 'Kursus Terarah',
                'description' => 'Kurikulum terstruktur berorientasi keterampilan industri nyata untuk memandu alur belajar bertahap.',
            ],
            [
                'title' => 'Materi Praktis',
                'description' => 'Latihan berbasis studi kasus dan implementasi langsung agar pemahaman konsep lebih kokoh.',
            ],
            [
                'title' => 'Pendaftaran Mudah',
                'description' => 'Proses seleksi kelas dan formulir pendaftaran yang ringkas, transparan, dan terarah.',
            ],
        ];

        $facilities = [
            'Modul digital terstruktur',
            'Sertifikat resmi penyelesaian',
            'Akses forum diskusi kelas interaktif',
        ];

        $steps = [
            [
                'number' => '1',
                'title' => 'Pilih Kursus',
                'desc' => 'Tentukan bidang dan materi kursus yang relevan dengan minat pengembangan karier Anda.',
            ],
            [
                'number' => '2',
                'title' => 'Periksa Kapasitas',
                'desc' => 'Lihat jadwal mulai, harga, dan sisa kursi kelas yang masih tersedia.',
            ],
            [
                'number' => '3',
                'title' => 'Daftar Kelas',
                'desc' => 'Lengkapi data pendaftaran dan tentukan fokus bidang peminatan studi Anda.',
            ],
            [
                'number' => '4',
                'title' => 'Mulai Belajar',
                'desc' => 'Dapatkan konfirmasi pendaftaran serta akses materi pembelajaran secara terpandu.',
            ],
        ];

        return view('home', compact(
            'siteName',
            'tagline',
            'featured',
            'advantages',
            'facilities',
            'steps'
        ));
    }
}
