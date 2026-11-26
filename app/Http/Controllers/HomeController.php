<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $siteName = 'KursusKu';
        $tagline = 'Platform pelatihan digital terstruktur dengan kurikulum praktis, mentor berpengalaman, dan sertifikat kompetensi.';

        $featured = [
            [
                'code' => 'WEB-01',
                'name' => 'Web Development',
                'category' => 'Programming',
                'description' => 'Belajar struktur HTML, CSS modern, dan responsivitas antarmuka web.',
                'price' => 750000,
                'start_date' => '2026-12-01',
                'participants' => 18,
                'capacity' => 25,
                'seats_left' => 7,
                'status' => 'Tersedia',
            ],
            [
                'code' => 'UIUX-02',
                'name' => 'UI/UX Design',
                'category' => 'Design',
                'description' => 'Merancang pengalaman pengguna dan prototipe antarmuka aplikasi.',
                'price' => 850000,
                'start_date' => '2026-12-05',
                'participants' => 15,
                'capacity' => 20,
                'seats_left' => 5,
                'status' => 'Tersedia',
            ],
            [
                'code' => 'LAR-03',
                'name' => 'Laravel Dasar',
                'category' => 'Backend',
                'description' => 'Belajar route, controller, Blade, Eloquent ORM, dan arsitektur MVC.',
                'price' => 500000,
                'start_date' => '2026-12-10',
                'participants' => 20,
                'capacity' => 20,
                'seats_left' => 0,
                'status' => 'Penuh',
            ],
            [
                'code' => 'DATA-04',
                'name' => 'Data Analysis',
                'category' => 'Data',
                'description' => 'Mengolah data bisnis dengan SQL, statistik dasar, dan visualisasi.',
                'price' => 750000,
                'start_date' => '2026-12-12',
                'participants' => 12,
                'capacity' => 25,
                'seats_left' => 13,
                'status' => 'Tersedia',
            ],
            [
                'code' => 'MKT-05',
                'name' => 'Digital Marketing',
                'category' => 'Marketing',
                'description' => 'Strategi kampanye digital, SEO, dan analitik performa pemasaran.',
                'price' => 500000,
                'start_date' => '2026-12-15',
                'participants' => 10,
                'capacity' => 30,
                'seats_left' => 20,
                'status' => 'Tersedia',
            ],
            [
                'code' => 'DSG-06',
                'name' => 'Desain Grafis',
                'category' => 'Creative',
                'description' => 'Prinsip tipografi, komposisi visual, dan pembuatan aset branding.',
                'price' => 500000,
                'start_date' => '2026-12-18',
                'participants' => 14,
                'capacity' => 20,
                'seats_left' => 6,
                'status' => 'Tersedia',
            ],
        ];

        $advantages = [
            [
                'title' => 'Kurikulum Terstruktur',
                'description' => 'Materi disusun bertahap dari fundamental hingga studi kasus industri.',
            ],
            [
                'title' => 'Mentor Praktisi',
                'description' => 'Dibimbing langsung oleh pengajar berpengalaman di bidang teknologi.',
            ],
            [
                'title' => 'Sertifikat Kelulusan',
                'description' => 'Peserta yang menyelesaikan program berhak memperoleh sertifikat resmi.',
            ],
        ];

        $facilities = [
            'Modul Digital Lengkap & Terstruktur',
            'Sesi Konsultasi Kelas Bersama Mentor',
            'Proyek Portofolio Berbasis Studi Kasus',
            'Sertifikat Kelulusan Resmi KursusKu',
        ];

        $steps = [
            [
                'number' => '01',
                'title' => 'Buat Akun',
                'desc' => 'Registrasi akun peserta KursusKu secara gratis.',
            ],
            [
                'number' => '02',
                'title' => 'Pilih Kursus',
                'desc' => 'Telusuri katalog kursus yang tersedia dan sesuai minat Anda.',
            ],
            [
                'number' => '03',
                'title' => 'Daftar & Belajar',
                'desc' => 'Ikuti pendaftaran kursus dan pantau progres belajar di dashboard.',
            ],
            [
                'number' => '04',
                'title' => 'Raih Sertifikat',
                'desc' => 'Selesaikan proyek akhir dan dapatkan sertifikat kompetensi.',
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

    public function info()
    {
        return view('pages.info');
    }
}
