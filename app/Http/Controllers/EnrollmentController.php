<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    /**
     * Menampilkan laporan daftar seluruh data pendaftaran (Pertemuan 12).
     * Menggunakan eager loading with(['participant', 'course']) untuk efisiensi query relasi.
     */
    public function index(): View
    {
        $enrollments = Enrollment::with(['participant', 'course'])->latest()->get();

        return view('enrollments.index', compact('enrollments'));
    }

    /**
     * Menampilkan formulir pendaftaran enrollment relasional (Pertemuan 12).
     * Mengambil participants diurutkan berdasarkan nama dan courses berstatus 'open'.
     */
    public function create(): View
    {
        $participants = Participant::orderBy('name')->get();
        $courses = Course::where('status', 'open')->orderBy('name')->get();

        return view('enrollments.create', [
            'siteName' => 'KursusKu',
            'participants' => $participants,
            'courses' => $courses,
        ]);
    }

    /**
     * Menyimpan data enrollment baru dengan proteksi dua lapis terhadap duplikasi (Pertemuan 12).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'participant_id' => 'required|integer|exists:participants,id',
            'course_id' => 'required|integer|exists:courses,id',
        ], [
            'participant_id.required' => 'Silakan pilih peserta.',
            'participant_id.integer' => 'Data peserta tidak valid.',
            'participant_id.exists' => 'Peserta yang dipilih tidak terdaftar di sistem.',
            'course_id.required' => 'Silakan pilih program kursus.',
            'course_id.integer' => 'Data kursus tidak valid.',
            'course_id.exists' => 'Kursus yang dipilih tidak terdaftar di sistem.',
        ]);

        // Lapis 1: Validasi logika aplikasi untuk mencegah peserta mendaftar kursus yang sama dua kali
        $isDuplicate = Enrollment::where('participant_id', $request->participant_id)
            ->where('course_id', $request->course_id)
            ->exists();

        if ($isDuplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'course_id' => 'Peserta sudah terdaftar pada kursus ini. Silakan pilih kursus lain.',
                ]);
        }

        // Lapis 2: Penyimpanan Eloquent dengan penanganan exception composite unique database
        try {
            Enrollment::create([
                'participant_id' => $request->participant_id,
                'course_id' => $request->course_id,
                'status' => 'registered', // Status awal ditetapkan otomatis oleh sistem
            ]);
        } catch (QueryException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'course_id' => 'Peserta sudah terdaftar pada kursus ini.',
                ]);
        }

        return redirect()
            ->route('enrollments.index')
            ->with('success', 'Pendaftaran kursus (enrollment) berhasil disimpan!');
    }

    /**
     * Menampilkan formulir pendaftaran interaktif publik dengan cek Ajax (Pertemuan 9).
     */
    public function pendaftaran(): View
    {
        try {
            $courses = Course::where('status', 'open')->get();
        } catch (\Throwable $e) {
            $courses = collect();
        }

        if ($courses->isEmpty()) {
            $courses = collect([
                (object) ['id' => 1, 'name' => 'Web Dasar'],
                (object) ['id' => 2, 'name' => 'PHP Dasar'],
                (object) ['id' => 3, 'name' => 'Laravel Dasar'],
            ]);
        }

        return view('enrollments.pendaftaran', [
            'siteName' => 'KursusKu',
            'courses' => $courses,
        ]);
    }

    /**
     * Validasi data pendaftaran kursus interaktif (Pertemuan 9).
     */
    public function preview(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email',
            'course_id' => 'required|integer',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal 3 karakter.',
            'name.max' => 'Nama lengkap maksimal 100 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'course_id.required' => 'Silakan pilih program kursus.',
            'course_id.integer' => 'Pilihan kursus tidak valid.',
        ]);

        return redirect()
            ->route('pendaftaran')
            ->withInput($request->all())
            ->with('success', 'Formulir pendaftaran berhasil divalidasi!');
    }
}
