<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Menampilkan daftar kursus (Halaman Index)
    public function index(Request $request)
    {
        $query = Course::query();

        // Fitur Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
        }

        // Fitur Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil data dan paginate (10 per halaman)
        $courses = $query->orderBy('id', 'asc')->paginate(10);

        return view('courses.index', compact('courses'));
    }

    // Menampilkan form tambah kursus
    public function create()
    {
        return view('courses.create');
    }

    // Menyimpan data kursus baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:10|unique:courses,code',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'fee'         => 'required|numeric|min:0', // <-- DIPERBAIKI: price jadi fee
            'quota'       => 'required|integer|min:1',
            'start_date'  => 'required|date',
            'status'      => 'required|in:Open,Closed,Draft',
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Kursus berhasil ditambahkan!');
    }

    // Menampilkan detail satu kursus
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    // Menampilkan form edit kursus
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    // Mengupdate data kursus
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:10|unique:courses,code,' . $course->id,
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'fee'         => 'required|numeric|min:0', // <-- DIPERBAIKI: price jadi fee
            'quota'       => 'required|integer|min:1',
            'start_date'  => 'required|date',
            'status'      => 'required|in:Open,Closed,Draft',
        ]);

        $course->update($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Kursus berhasil diperbarui!');
    }

    // Menghapus data kursus
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Kursus berhasil dihapus!');
    }

    // Method untuk AJAX cek ketersediaan kursi (DIPERLUKAN oleh route web.php)
    public function availability(Course $course)
    {
        $enrolledCount = $course->enrollments()->count();
        $remaining = max(0, $course->quota - $enrolledCount);

        return response()->json([
            'available' => $remaining > 0,
            'quota' => $course->quota,
            'enrolled' => $enrolledCount,
            'remaining' => $remaining
        ]);
    }
}