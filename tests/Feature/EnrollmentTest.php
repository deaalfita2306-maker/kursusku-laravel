<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Regresi Pertemuan 9: Form Pendaftaran Publik (/pendaftaran) & Preview.
     */
    public function test_public_pendaftaran_route_is_accessible_and_renders(): void
    {
        $this->assertTrue(Route::has('pendaftaran'));
        $this->assertTrue(Route::has('enrollments.preview'));

        $response = $this->get('/pendaftaran');
        $response->assertStatus(200);
        $response->assertViewIs('enrollments.pendaftaran');
        $response->assertSee('Pendaftaran Kursus');
        $response->assertSee('name="name"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('id="availability"', false);
    }

    public function test_public_preview_validates_and_redirects(): void
    {
        $course = Course::create([
            'code' => 'WEB-101',
            'name' => 'Web Dasar',
            'fee' => 300000,
            'quota' => 25,
            'start_date' => '2026-11-20',
            'status' => 'open',
        ]);

        $payload = [
            'name' => 'Budi Pratama',
            'email' => 'budi@example.com',
            'course_id' => $course->id,
        ];

        $response = $this->post(route('enrollments.preview'), $payload);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
    }

    public function test_availability_route_returns_json_and_handles_404(): void
    {
        $course = Course::create([
            'code' => 'WEB-101',
            'name' => 'Web Dasar',
            'fee' => 300000,
            'quota' => 25,
            'start_date' => '2026-11-20',
            'status' => 'open',
        ]);

        $res = $this->get(route('courses.availability', ['course' => $course->id]));
        $res->assertStatus(200);
        $res->assertJson([
            'id' => $course->id,
            'code' => 'WEB-101',
            'name' => 'Web Dasar',
            'quota' => 25,
            'status' => 'open',
            'remaining' => 25,
        ]);

        $notFound = $this->get(route('courses.availability', ['course' => 9999]));
        $notFound->assertStatus(404);
    }

    /**
     * Pertemuan 12: Rute Relasional Enrollment Terdaftar.
     */
    public function test_relational_enrollment_routes_are_registered(): void
    {
        $this->assertTrue(Route::has('enrollments.index'));
        $this->assertTrue(Route::has('enrollments.create'));
        $this->assertTrue(Route::has('enrollments.store'));
    }

    /**
     * Skenario 1: Buka /enrollments/create, kedua dropdown menampilkan data database.
     */
    public function test_sc_01_create_form_renders_both_dropdowns_from_database(): void
    {
        $participant = Participant::create([
            'name' => 'Alya Rahma',
            'email' => 'alya@example.test',
            'phone' => '081200000001',
        ]);

        $course = Course::create([
            'code' => 'LRV-101',
            'name' => 'Laravel Dasar',
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        $draftCourse = Course::create([
            'code' => 'DFT-101',
            'name' => 'Draft Course',
            'fee' => 300000,
            'quota' => 20,
            'start_date' => '2026-11-30',
            'status' => 'draft',
        ]);

        $response = $this->get(route('enrollments.create'));
        $response->assertStatus(200);
        $response->assertViewIs('enrollments.create');
        $response->assertSee('Pendaftaran Enrollment');

        // Dropdown Participant menampilkan nama dan email
        $response->assertSee('Alya Rahma (alya@example.test)');

        // Dropdown Course menampilkan kode dan nama kursus yang berstatus 'open'
        $response->assertSee('LRV-101 — Laravel Dasar');

        // Kursus draft tidak boleh muncul
        $response->assertDontSee('DFT-101 — Draft Course');
    }

    /**
     * Skenario 2: Participant kosong ditolak.
     */
    public function test_sc_02_empty_participant_is_rejected(): void
    {
        $course = Course::create([
            'code' => 'LRV-101',
            'name' => 'Laravel Dasar',
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        $response = $this->post(route('enrollments.store'), [
            'participant_id' => '',
            'course_id' => $course->id,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['participant_id']);
    }

    /**
     * Skenario 3: Course kosong ditolak.
     */
    public function test_sc_03_empty_course_is_rejected(): void
    {
        $participant = Participant::create([
            'name' => 'Alya Rahma',
            'email' => 'alya@example.test',
            'phone' => '081200000001',
        ]);

        $response = $this->post(route('enrollments.store'), [
            'participant_id' => $participant->id,
            'course_id' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['course_id']);
    }

    /**
     * Skenario 4: Alya mendaftar Laravel Dasar, berhasil dengan status registered.
     */
    public function test_sc_04_alya_enrolls_laravel_dasar_succeeds_with_registered_status(): void
    {
        $alya = Participant::create([
            'name' => 'Alya Rahma',
            'email' => 'alya@example.test',
            'phone' => '081200000001',
        ]);

        $laravel = Course::create([
            'code' => 'LRV-101',
            'name' => 'Laravel Dasar',
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        $response = $this->post(route('enrollments.store'), [
            'participant_id' => $alya->id,
            'course_id' => $laravel->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('enrollments.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('enrollments', [
            'participant_id' => $alya->id,
            'course_id' => $laravel->id,
            'status' => 'registered',
        ]);
    }

    /**
     * Skenario 5: Alya mendaftar Laravel Dasar sekali lagi, ditolak sebagai duplikasi.
     */
    public function test_sc_05_duplicate_enrollment_same_participant_and_course_is_rejected(): void
    {
        $alya = Participant::create([
            'name' => 'Alya Rahma',
            'email' => 'alya@example.test',
            'phone' => '081200000001',
        ]);

        $laravel = Course::create([
            'code' => 'LRV-101',
            'name' => 'Laravel Dasar',
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        Enrollment::create([
            'participant_id' => $alya->id,
            'course_id' => $laravel->id,
            'status' => 'registered',
        ]);

        // Coba daftar lagi
        $response = $this->post(route('enrollments.store'), [
            'participant_id' => $alya->id,
            'course_id' => $laravel->id,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['course_id']);
        $this->assertEquals(1, Enrollment::count());
    }

    /**
     * Skenario 6: Alya mendaftar PHP Dasar, boleh berhasil (peserta sama, kursus beda).
     */
    public function test_sc_06_same_participant_different_course_succeeds(): void
    {
        $alya = Participant::create([
            'name' => 'Alya Rahma',
            'email' => 'alya@example.test',
            'phone' => '081200000001',
        ]);

        $laravel = Course::create([
            'code' => 'LRV-101',
            'name' => 'Laravel Dasar',
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        $php = Course::create([
            'code' => 'PHP-101',
            'name' => 'PHP Fundamental',
            'fee' => 400000,
            'quota' => 25,
            'start_date' => '2026-11-22',
            'status' => 'open',
        ]);

        Enrollment::create([
            'participant_id' => $alya->id,
            'course_id' => $laravel->id,
            'status' => 'registered',
        ]);

        $response = $this->post(route('enrollments.store'), [
            'participant_id' => $alya->id,
            'course_id' => $php->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('enrollments.index'));
        $this->assertDatabaseHas('enrollments', [
            'participant_id' => $alya->id,
            'course_id' => $php->id,
            'status' => 'registered',
        ]);
        $this->assertEquals(2, Enrollment::count());
    }

    /**
     * Skenario 7: Bima mendaftar Laravel Dasar, boleh berhasil (peserta beda, kursus sama).
     */
    public function test_sc_07_different_participant_same_course_succeeds(): void
    {
        $alya = Participant::create([
            'name' => 'Alya Rahma',
            'email' => 'alya@example.test',
            'phone' => '081200000001',
        ]);

        $bima = Participant::create([
            'name' => 'Bima Putra',
            'email' => 'bima@example.test',
            'phone' => '081200000002',
        ]);

        $laravel = Course::create([
            'code' => 'LRV-101',
            'name' => 'Laravel Dasar',
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        Enrollment::create([
            'participant_id' => $alya->id,
            'course_id' => $laravel->id,
            'status' => 'registered',
        ]);

        $response = $this->post(route('enrollments.store'), [
            'participant_id' => $bima->id,
            'course_id' => $laravel->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('enrollments.index'));
        $this->assertDatabaseHas('enrollments', [
            'participant_id' => $bima->id,
            'course_id' => $laravel->id,
            'status' => 'registered',
        ]);
        $this->assertEquals(2, Enrollment::count());
    }

    /**
     * Skenario 8: Buka /enrollments, laporan menampilkan nama participant, nama course, start date, dan status dengan benar.
     */
    public function test_sc_08_index_displays_relational_report_accurately(): void
    {
        $alya = Participant::create([
            'name' => 'Alya Rahma',
            'email' => 'alya@example.test',
            'phone' => '081200000001',
        ]);

        $laravel = Course::create([
            'code' => 'LRV-101',
            'name' => 'Laravel Dasar',
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        Enrollment::create([
            'participant_id' => $alya->id,
            'course_id' => $laravel->id,
            'status' => 'registered',
        ]);

        $response = $this->get(route('enrollments.index'));
        $response->assertStatus(200);
        $response->assertViewIs('enrollments.index');
        $response->assertSee('Alya Rahma');
        $response->assertSee('alya@example.test');
        $response->assertSee('Laravel Dasar');
        $response->assertSee('25 Nov 2026');
        $response->assertSee('Registered');
    }

    /**
     * Uji Cascade Delete A: Hapus Course dummy, pastikan Enrollment terhapus dan Participant tetap ada.
     */
    public function test_cascade_delete_a_course_deleted_enrollment_deleted_participant_remains(): void
    {
        $participant = Participant::create([
            'name' => 'Peserta Uji A',
            'email' => 'uji.a@example.test',
            'phone' => '081999999901',
        ]);

        $course = Course::create([
            'code' => 'TEST-CASCADE-COURSE',
            'name' => 'Kursus Uji Cascade A',
            'fee' => 100000,
            'quota' => 10,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        $enrollment = Enrollment::create([
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);

        $this->assertEquals(1, Enrollment::count());

        // Hapus Course dummy
        $course->delete();

        // Enrollment harus ikut terhapus (cascade)
        $this->assertDatabaseMissing('enrollments', ['id' => $enrollment->id]);
        $this->assertEquals(0, Enrollment::count());

        // Participant harus tetap ada
        $this->assertDatabaseHas('participants', ['id' => $participant->id]);
    }

    /**
     * Uji Cascade Delete B: Hapus Participant dummy, pastikan Enrollment terhapus dan Course tetap ada.
     */
    public function test_cascade_delete_b_participant_deleted_enrollment_deleted_course_remains(): void
    {
        $participant = Participant::create([
            'name' => 'Peserta Uji B',
            'email' => 'uji.b@example.test',
            'phone' => '081999999902',
        ]);

        $course = Course::create([
            'code' => 'TEST-CASCADE-PART',
            'name' => 'Kursus Uji Cascade B',
            'fee' => 100000,
            'quota' => 10,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        $enrollment = Enrollment::create([
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);

        $this->assertEquals(1, Enrollment::count());

        // Hapus Participant dummy
        $participant->delete();

        // Enrollment harus ikut terhapus (cascade)
        $this->assertDatabaseMissing('enrollments', ['id' => $enrollment->id]);
        $this->assertEquals(0, Enrollment::count());

        // Course harus tetap ada
        $this->assertDatabaseHas('courses', ['id' => $course->id]);
    }

    /**
     * Uji Relasi Eloquent Antar Entitas.
     */
    public function test_eloquent_relationships(): void
    {
        $participant = Participant::create([
            'name' => 'Alya Rahma',
            'email' => 'alya@example.test',
            'phone' => '081200000001',
        ]);

        $course = Course::create([
            'code' => 'LRV-101',
            'name' => 'Laravel Dasar',
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        $enrollment = Enrollment::create([
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);

        // Participant relations
        $this->assertTrue($participant->enrollments->contains($enrollment));
        $this->assertTrue($participant->courses->contains($course));

        // Course relations
        $this->assertTrue($course->enrollments->contains($enrollment));
        $this->assertTrue($course->participants->contains($participant));

        // Enrollment relations
        $this->assertEquals($participant->id, $enrollment->participant->id);
        $this->assertEquals($course->id, $enrollment->course->id);
    }
}
