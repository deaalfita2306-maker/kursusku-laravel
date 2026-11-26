<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $enrollments = Enrollment::with(['participant', 'course'])->latest()->get();
        } else {
            $participant = Participant::where('email', $user->email)->first();
            $enrollments = $participant
                ? Enrollment::with(['participant', 'course'])
                    ->where('participant_id', $participant->id)
                    ->latest()
                    ->get()
                : collect();
        }

        return view('enrollments.index', compact('enrollments'));
    }

    public function create()
    {
        $participants = Participant::orderBy('name')->get();
        $courses = Course::where('status', 'open')->orderBy('name')->get();

        return view('enrollments.create', compact('participants', 'courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'participant_id' => ['required', 'exists:participants,id'],
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::unique('enrollments')->where(
                    fn ($query) => $query->where('participant_id', $request->participant_id)
                ),
            ],
        ], [
            'course_id.unique' => 'Participant ini sudah terdaftar pada course tersebut.',
        ]);

        $data['status'] = 'registered';
        Enrollment::create($data);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment berhasil disimpan.');
    }

    public function enrollSelf(Course $course)
    {
        if ($course->status !== 'open') {
            return back()->with('error', 'Pendaftaran kursus ini sedang tidak dibuka.');
        }

        if ($course->available_quota <= 0) {
            return back()->with('error', 'Maaf, kuota kursus ini sudah penuh.');
        }

        $user = Auth::user();

        $participant = Participant::firstOrCreate(
            ['email' => $user->email],
            [
                'name' => $user->name,
                'phone' => $user->phone,
            ]
        );

        $alreadyEnrolled = Enrollment::where('participant_id', $participant->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadyEnrolled) {
            return back()->with('error', 'Anda sudah terdaftar pada kursus ini.');
        }

        Enrollment::create([
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Pendaftaran kursus "' . $course->name . '" berhasil!');
    }

    public function pendaftaran()
    {
        $courses = Course::where('status', 'open')->orderBy('name')->get();

        return view('enrollments.pendaftaran', compact('courses'));
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:120'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $course = Course::findOrFail($validated['course_id']);

        return redirect()->route('pendaftaran')
            ->with('success', 'Pratinjau pendaftaran untuk ' . $validated['name'] . ' pada kursus ' . $course->name . ' berhasil divalidasi.');
    }
}
