<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['code' => 'DB-01',  'name' => 'Basis Data Normalisasi',      'description' => 'Teori dan praktik normalisasi database.', 'fee' => 500000, 'quota' => 25, 'start_date' => '2026-10-01', 'status' => 'Closed'],
            ['code' => 'WEB-07', 'name' => 'Bootstrap Praktis',           'description' => 'Membangun UI cepat dan konsisten.',         'fee' => 450000, 'quota' => 40, 'start_date' => '2026-12-20', 'status' => 'Open'],
            ['code' => 'WEB-10', 'name' => 'Deploy Aplikasi Web',         'description' => 'CI/CD dan deployment ke server produksi.',  'fee' => 850000, 'quota' => 10, 'start_date' => '2026-12-28', 'status' => 'Open'],
            ['code' => 'UI-01',  'name' => 'Desain Antarmuka',            'description' => 'Prinsip UI/UX dasar untuk software dev.',   'fee' => 550000, 'quota' => 30, 'start_date' => '2026-10-15', 'status' => 'Closed'],
            ['code' => 'SEC-01', 'name' => 'Ethical Hacking Dasar',       'description' => 'Pengenalan cybersecurity dan penetration.', 'fee' => 900000, 'quota' => 15, 'start_date' => '2027-02-25', 'status' => 'Draft'],
            ['code' => 'MOB-01', 'name' => 'Flutter Dasar',               'description' => 'Pengenalan Dart dan Flutter widget tree.',  'fee' => 700000, 'quota' => 25, 'start_date' => '2026-10-25', 'status' => 'Closed'],
            ['code' => 'MOB-02', 'name' => 'Flutter State Management',    'description' => 'Implementasi Provider, Riverpod, dan BLoC.', 'fee' => 800000, 'quota' => 15, 'start_date' => '2026-10-30', 'status' => 'Closed'],
            ['code' => 'WEB-09', 'name' => 'Git untuk Tim',               'description' => 'Version control, branching, dan collab.',   'fee' => 400000, 'quota' => 30, 'start_date' => '2026-12-25', 'status' => 'Open'],
            ['code' => 'NET-01', 'name' => 'Jaringan Komputer',           'description' => 'Dasar-dasar jaringan, OSI Layer, dan pro.', 'fee' => 500000, 'quota' => 30, 'start_date' => '2026-11-01', 'status' => 'Closed'],
            ['code' => 'WEB-06', 'name' => 'jQuery dan Ajax',             'description' => 'Manipulasi DOM dan request async dengan.',  'fee' => 700000, 'quota' => 20, 'start_date' => '2026-12-18', 'status' => 'Open'],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}