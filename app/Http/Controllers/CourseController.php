<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Menampilkan daftar kursus dari database MySQL (Pertemuan 10 & 11).
     */
    public function index(): View
    {
        $courses = Course::latest()->get();

        return view('courses.index', compact('courses'));
    }

    /**
     * Menampilkan formulir tambah kursus baru (Pertemuan 10 & 11).
     */
    public function create(): View
    {
        return view('courses.create');
    }

    /**
     * Menyimpan data kursus baru ke database MySQL menggunakan Form Request (Pertemuan 10 & 11).
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        Course::create($request->validated());

        return redirect()
            ->route('courses.index')
            ->with('success', 'Data kursus berhasil disimpan ke database!');
    }

    /**
     * Menampilkan rincian detail data kursus menggunakan Route Model Binding (Pertemuan 11).
     */
    public function show(Course $course): View
    {
        return view('courses.show', compact('course'));
    }

    /**
     * Menampilkan formulir edit kursus dengan data awal dari database (Pertemuan 11).
     */
    public function edit(Course $course): View
    {
        return view('courses.edit', compact('course'));
    }

    /**
     * Memperbarui data kursus di database menggunakan UpdateCourseRequest (Pertemuan 11).
     */
    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Data kursus berhasil diperbarui!');
    }

    /**
     * Menghapus data kursus dari database MySQL (Pertemuan 11).
     */
    public function destroy(Course $course): RedirectResponse
    {
        $courseName = $course->name;
        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', "Kursus '{$courseName}' berhasil dihapus dari database!");
    }

    /**
     * Endpoint JSON ketersediaan kursi kursus untuk integrasi jQuery Ajax (Pertemuan 9, 10 & 11).
     * Membaca data langsung dari tabel courses di database MySQL menggunakan Model Eloquent Course.
     */
    public function availability($course): JsonResponse
    {
        $courseModel = null;

        try {
            // Cari kursus berdasarkan ID angka atau Code string
            $courseModel = is_numeric($course)
                ? Course::find($course)
                : Course::where('code', $course)->first();
        } catch (\Throwable $e) {
            $courseModel = null;
        }

        if ($courseModel) {
            return response()->json([
                'id' => $courseModel->id,
                'code' => $courseModel->code,
                'name' => $courseModel->name,
                'quota' => (int) $courseModel->quota,
                'status' => $courseModel->status,
                'remaining' => (int) $courseModel->quota, // Kompatibilitas dengan JS Ajax Pertemuan 9
            ]);
        }

        // Fallback untuk dummy data Pertemuan 9 jika diakses via ID 1, 2, 3
        $fallback = [
            1 => ['id' => 1, 'code' => 'WEB-101', 'name' => 'Web Dasar', 'quota' => 25, 'status' => 'open', 'remaining' => 12],
            2 => ['id' => 2, 'code' => 'PHP-101', 'name' => 'PHP Dasar', 'quota' => 25, 'status' => 'open', 'remaining' => 8],
            3 => ['id' => 3, 'code' => 'LRV-101', 'name' => 'Laravel Dasar', 'quota' => 20, 'status' => 'open', 'remaining' => 4],
        ];

        if (is_numeric($course) && isset($fallback[(int) $course])) {
            return response()->json($fallback[(int) $course]);
        }

        abort(404, 'Kursus tidak ditemukan.');
    }
}
