<?php

namespace Tests\Feature;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CourseCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_routes_are_registered_and_named(): void
    {
        $this->assertTrue(Route::has('courses.index'));
        $this->assertTrue(Route::has('courses.create'));
        $this->assertTrue(Route::has('courses.store'));
        $this->assertTrue(Route::has('courses.show'));
        $this->assertTrue(Route::has('courses.edit'));
        $this->assertTrue(Route::has('courses.update'));
        $this->assertTrue(Route::has('courses.destroy'));
        $this->assertTrue(Route::has('courses.availability'));
    }

    public function test_course_index_renders_successfully_with_empty_state(): void
    {
        $response = $this->get(route('courses.index'));

        $response->assertStatus(200);
        $response->assertViewIs('courses.index');
        $response->assertSee('Daftar Kursus');
        $response->assertSee('Belum Ada Data Kursus');
        $response->assertSee(route('courses.create'));
    }

    public function test_course_create_form_renders_all_required_inputs(): void
    {
        $response = $this->get(route('courses.create'));

        $response->assertStatus(200);
        $response->assertViewIs('courses.create');
        $response->assertSee('Tambah Kursus Baru');
        $response->assertSee('action="' . route('courses.store') . '"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('name="code"', false);
        $response->assertSee('name="name"', false);
        $response->assertSee('name="description"', false);
        $response->assertSee('name="fee"', false);
        $response->assertSee('name="quota"', false);
        $response->assertSee('name="start_date"', false);
        $response->assertSee('name="status"', false);
        $response->assertSee('Simpan Kursus');
        $response->assertSee(route('courses.index'));
    }

    /**
     * Skenario 1: Duplicate code saat create — harus ditolak
     */
    public function test_sc_01_duplicate_code_on_create_is_rejected(): void
    {
        Course::create([
            'code' => 'WEB-101',
            'name' => 'Web Dasar Eksisting',
            'fee' => 300000,
            'quota' => 25,
            'start_date' => '2026-11-20',
            'status' => 'open',
        ]);

        $payload = [
            'code' => 'WEB-101', // Kode duplikat
            'name' => 'Web Dasar Baru',
            'fee' => 350000,
            'quota' => 20,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['code']);
    }

    /**
     * Skenario 2: Nama kosong — harus ditolak
     */
    public function test_sc_02_empty_name_is_rejected(): void
    {
        $payload = [
            'code' => 'PHP-101',
            'name' => '', // kosong
            'fee' => 400000,
            'quota' => 25,
            'start_date' => '2026-11-22',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Skenario 3: Nama terlalu pendek ('AI') — harus ditolak
     */
    public function test_sc_03_name_too_short_is_rejected(): void
    {
        $payload = [
            'code' => 'AI-101',
            'name' => 'AI', // Hanya 2 karakter, min:3
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Skenario 4: Fee negatif ('-1000') — harus ditolak
     */
    public function test_sc_04_negative_fee_is_rejected(): void
    {
        $payload = [
            'code' => 'PHP-101',
            'name' => 'PHP Fundamental',
            'fee' => -1000, // negatif
            'quota' => 25,
            'start_date' => '2026-11-22',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['fee']);
    }

    /**
     * Skenario 5: Fee bukan integer ('abc') — harus ditolak
     */
    public function test_sc_05_non_integer_fee_is_rejected(): void
    {
        $payload = [
            'code' => 'PHP-101',
            'name' => 'PHP Fundamental',
            'fee' => 'abc', // bukan integer
            'quota' => 25,
            'start_date' => '2026-11-22',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['fee']);
    }

    /**
     * Skenario 6: Quota 0 — harus ditolak
     */
    public function test_sc_06_zero_quota_is_rejected(): void
    {
        $payload = [
            'code' => 'LRV-101',
            'name' => 'Laravel Dasar',
            'fee' => 500000,
            'quota' => 0, // 0 di luar between:1,500
            'start_date' => '2026-11-25',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['quota']);
    }

    /**
     * Skenario 7: Quota 501 — harus ditolak
     */
    public function test_sc_07_quota_501_is_rejected(): void
    {
        $payload = [
            'code' => 'LRV-101',
            'name' => 'Laravel Dasar',
            'fee' => 500000,
            'quota' => 501, // 501 di luar between:1,500
            'start_date' => '2026-11-25',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['quota']);
    }

    /**
     * Skenario 8: Quota minimum 1 — harus diterima
     */
    public function test_sc_08_minimum_quota_1_is_accepted(): void
    {
        $payload = [
            'code' => 'MIN-001',
            'name' => 'Kursus Privat 1 On 1',
            'fee' => 1000000,
            'quota' => 1, // batas bawah valid
            'start_date' => '2026-11-30',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertStatus(302);
        $response->assertRedirect(route('courses.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('courses', ['code' => 'MIN-001', 'quota' => 1]);
    }

    /**
     * Skenario 9: Quota maksimum 500 — harus diterima
     */
    public function test_sc_09_maximum_quota_500_is_accepted(): void
    {
        $payload = [
            'code' => 'MAX-500',
            'name' => 'Webinar Akbar Teknologi',
            'fee' => 50000,
            'quota' => 500, // batas atas valid
            'start_date' => '2026-12-01',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertStatus(302);
        $response->assertRedirect(route('courses.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('courses', ['code' => 'MAX-500', 'quota' => 500]);
    }

    /**
     * Skenario 10: Tanggal invalid — harus ditolak
     */
    public function test_sc_10_invalid_date_is_rejected(): void
    {
        $payload = [
            'code' => 'SQL-101',
            'name' => 'MySQL Dasar',
            'fee' => 350000,
            'quota' => 30,
            'start_date' => 'bukan-tanggal-valid', // invalid date
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['start_date']);
    }

    /**
     * Skenario 11: Status invalid ('active') — harus ditolak
     */
    public function test_sc_11_invalid_status_is_rejected(): void
    {
        $payload = [
            'code' => 'UI-101',
            'name' => 'Bootstrap UI',
            'fee' => 300000,
            'quota' => 25,
            'start_date' => '2026-12-01',
            'status' => 'active', // di luar draft, open, closed
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['status']);
    }

    /**
     * Skenario 12: Update dengan code sendiri tanpa mengubahnya — harus berhasil
     */
    public function test_sc_12_update_with_own_code_succeeds(): void
    {
        $course = Course::create([
            'code' => 'WEB-101',
            'name' => 'Web Dasar',
            'fee' => 300000,
            'quota' => 25,
            'start_date' => '2026-11-20',
            'status' => 'open',
        ]);

        $updatePayload = [
            'code' => 'WEB-101', // tetap sama
            'name' => 'Web Dasar Modern & Responsive', // diperbarui
            'description' => 'Materi telah diperbarui dengan Flexbox dan CSS Grid.',
            'fee' => 320000, // diperbarui
            'quota' => 28, // diperbarui
            'start_date' => '2026-11-20',
            'status' => 'open',
        ];

        $response = $this->put(route('courses.update', $course), $updatePayload);

        $response->assertStatus(302);
        $response->assertRedirect(route('courses.show', $course));
        $response->assertSessionHas('success', 'Data kursus berhasil diperbarui!');

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'code' => 'WEB-101',
            'name' => 'Web Dasar Modern & Responsive',
            'fee' => 320000,
            'quota' => 28,
        ]);
    }

    /**
     * Skenario 13: Update dengan code milik Course lain — harus ditolak
     */
    public function test_sc_13_update_with_another_course_code_is_rejected(): void
    {
        Course::create([
            'code' => 'WEB-101',
            'name' => 'Web Dasar',
            'fee' => 300000,
            'quota' => 25,
            'start_date' => '2026-11-20',
            'status' => 'open',
        ]);

        $courseB = Course::create([
            'code' => 'PHP-101',
            'name' => 'PHP Fundamental',
            'fee' => 400000,
            'quota' => 25,
            'start_date' => '2026-11-22',
            'status' => 'open',
        ]);

        // Coba ubah kode courseB menjadi WEB-101
        $updatePayload = [
            'code' => 'WEB-101', // milik course lain!
            'name' => 'PHP Lanjutan',
            'fee' => 450000,
            'quota' => 20,
            'start_date' => '2026-11-22',
            'status' => 'open',
        ];

        $response = $this->put(route('courses.update', $courseB), $updatePayload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['code']);
    }

    /**
     * Skenario 14: Update normal — perubahan tersimpan dan tampil di detail
     */
    public function test_sc_14_normal_update_persists_and_renders_on_show(): void
    {
        $course = Course::create([
            'code' => 'LRV-101',
            'name' => 'Laravel Dasar',
            'description' => 'Materi awal',
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        $updatePayload = [
            'code' => 'LRV-201',
            'name' => 'Laravel Advanced Masterclass',
            'description' => 'Materi routing, middleware, eloquent, dan REST API.',
            'fee' => 650000,
            'quota' => 15,
            'start_date' => '2026-12-01',
            'status' => 'closed',
        ];

        $response = $this->put(route('courses.update', $course), $updatePayload);

        $response->assertStatus(302);
        $response->assertRedirect(route('courses.show', $course));
        $response->assertSessionHas('success');

        // Periksa halaman show
        $showResponse = $this->get(route('courses.show', $course));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('LRV-201');
        $showResponse->assertSee('Laravel Advanced Masterclass');
        $showResponse->assertSee('Rp 650.000');
        $showResponse->assertSee('15 Peserta');
        $showResponse->assertSee('Closed');
    }

    /**
     * Skenario 15: Delete kursus dummy — data hilang dari index dan flash success tampil
     */
    public function test_sc_15_delete_dummy_course_removes_data_and_shows_flash(): void
    {
        $dummy = Course::create([
            'code' => 'DEL-001',
            'name' => 'Kursus Uji Hapus',
            'fee' => 100000,
            'quota' => 10,
            'start_date' => '2026-11-28',
            'status' => 'draft',
        ]);

        $response = $this->delete(route('courses.destroy', $dummy));

        $response->assertStatus(302);
        $response->assertRedirect(route('courses.index'));
        $response->assertSessionHas('success', "Kursus 'Kursus Uji Hapus' berhasil dihapus dari database!");

        $this->assertDatabaseMissing('courses', [
            'id' => $dummy->id,
            'code' => 'DEL-001',
        ]);

        $indexResponse = $this->get(route('courses.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertDontSee('DEL-001');
    }

    /**
     * Pengujian Detail (Show) & Edit Views
     */
    public function test_show_route_renders_successfully_and_returns_404_for_unknown(): void
    {
        $course = Course::create([
            'code' => 'UI-101',
            'name' => 'Bootstrap UI',
            'description' => 'Belajar membuat antarmuka responsif.',
            'fee' => 300000,
            'quota' => 25,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $response = $this->get(route('courses.show', $course));
        $response->assertStatus(200);
        $response->assertViewIs('courses.show');
        $response->assertSee('UI-101');
        $response->assertSee('Bootstrap UI');
        $response->assertSee('Rp 300.000');
        $response->assertSee('Draft');

        // Test 404
        $notFound = $this->get('/courses/99999');
        $notFound->assertStatus(404);
    }

    public function test_edit_route_renders_with_existing_database_values(): void
    {
        $course = Course::create([
            'code' => 'JS-101',
            'name' => 'JavaScript Dasar',
            'description' => 'Materi DOM dan Event.',
            'fee' => 400000,
            'quota' => 20,
            'start_date' => '2026-12-05',
            'status' => 'open',
        ]);

        $response = $this->get(route('courses.edit', $course));
        $response->assertStatus(200);
        $response->assertViewIs('courses.edit');
        $response->assertSee('value="JS-101"', false);
        $response->assertSee('value="JavaScript Dasar"', false);
        $response->assertSee('Materi DOM dan Event.');
        $response->assertSee('value="400000"', false);
        $response->assertSee('value="20"', false);
        $response->assertSee('value="2026-12-05"', false);
        $response->assertSee('action="' . route('courses.update', $course) . '"', false);
        $response->assertSee('name="_method" value="PUT"', false);
    }

    public function test_availability_route_reads_from_database_eloquent(): void
    {
        $course = Course::create([
            'code' => 'SQL-101',
            'name' => 'MySQL Dasar',
            'fee' => 350000,
            'quota' => 30,
            'start_date' => '2026-11-28',
            'status' => 'open',
        ]);

        // Access via ID
        $responseById = $this->get(route('courses.availability', ['course' => $course->id]));
        $responseById->assertStatus(200);
        $responseById->assertJson([
            'id' => $course->id,
            'code' => 'SQL-101',
            'name' => 'MySQL Dasar',
            'quota' => 30,
            'status' => 'open',
            'remaining' => 30,
        ]);

        // Access via Code
        $responseByCode = $this->get(route('courses.availability', ['course' => 'SQL-101']));
        $responseByCode->assertStatus(200);
        $responseByCode->assertJson([
            'id' => $course->id,
            'code' => 'SQL-101',
            'name' => 'MySQL Dasar',
            'quota' => 30,
            'status' => 'open',
            'remaining' => 30,
        ]);
    }
}
