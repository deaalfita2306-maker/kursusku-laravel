<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $participant = Participant::where('email', $user->email)->first();
        $myEnrollments = $participant
            ? Enrollment::with('course')
                ->where('participant_id', $participant->id)
                ->latest()
                ->get()
            : collect();

        $openCoursesCount = Course::where('status', 'open')->count();

        return view('dashboard', [
            'user' => $user,
            'myEnrollments' => $myEnrollments,
            'openCoursesCount' => $openCoursesCount,
        ]);
    }

    public function admin()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();
        $totalCourses = Course::count();
        $openCourses = Course::where('status', 'open')->count();
        $totalParticipants = Participant::count();
        $totalEnrollments = Enrollment::count();

        $recentUsers = User::latest()->take(5)->get();
        $recentEnrollments = Enrollment::with(['participant', 'course'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'totalCourses',
            'openCourses',
            'totalParticipants',
            'totalEnrollments',
            'recentUsers',
            'recentEnrollments'
        ));
    }
}
