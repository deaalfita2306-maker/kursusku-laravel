<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) ($request->query('q') ?? $request->query('search') ?? ''));
        $status = strtolower(trim((string) $request->query('status', '')));

        $query = Course::query();

        if (!Auth::check() || !Auth::user()->isAdmin()) {
            $query->where('status', '!=', 'draft');
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['open', 'closed', 'draft'], true)) {
            $query->where('status', $status);
        }

        $courses = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();

        return view('courses.index', compact('courses', 'search', 'status'));
    }

    public function create()
    {
        Gate::authorize('create', Course::class);

        return view('courses.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Course::class);

        $request->merge([
            'status' => strtolower((string) $request->input('status', 'draft')),
        ]);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:courses,code'],
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'fee' => ['required', 'integer', 'min:0'],
            'quota' => ['required', 'integer', 'between:1,500'],
            'start_date' => ['required', 'date'],
            'status' => ['required', 'in:draft,open,closed'],
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Kursus berhasil ditambahkan!');
    }

    public function show(Course $course)
    {
        Gate::authorize('view', $course);

        $isEnrolled = false;
        if (Auth::check()) {
            $participant = Participant::where('email', Auth::user()->email)->first();
            if ($participant) {
                $isEnrolled = Enrollment::where('participant_id', $participant->id)
                    ->where('course_id', $course->id)
                    ->exists();
            }
        }

        return view('courses.show', compact('course', 'isEnrolled'));
    }

    public function edit(Course $course)
    {
        Gate::authorize('update', $course);

        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        Gate::authorize('update', $course);

        $request->merge([
            'status' => strtolower((string) $request->input('status', $course->status)),
        ]);

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('courses', 'code')->ignore($course->id),
            ],
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'fee' => ['required', 'integer', 'min:0'],
            'quota' => ['required', 'integer', 'between:1,500'],
            'start_date' => ['required', 'date'],
            'status' => ['required', 'in:draft,open,closed'],
        ]);

        $course->update($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Kursus berhasil diperbarui!');
    }

    public function destroy(Course $course)
    {
        Gate::authorize('delete', $course);

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Kursus berhasil dihapus!');
    }

    public function availability(Course $course)
    {
        $enrolledCount = $course->enrollments()->count();
        $remaining = max(0, $course->quota - $enrolledCount);

        return response()->json([
            'id' => $course->id,
            'code' => $course->code,
            'name' => $course->name,
            'status' => $course->status,
            'available' => $remaining > 0 && $course->status === 'open',
            'quota' => $course->quota,
            'enrolled' => $enrolledCount,
            'remaining' => $remaining,
        ]);
    }
}
