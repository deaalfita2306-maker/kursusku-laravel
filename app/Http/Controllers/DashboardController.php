<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Participant;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalCourses'      => Course::count(),
            'totalParticipants' => Participant::count(),
            'activeEnrollments' => Enrollment::where('status', 'active')->count(),
            'attendanceRate'    => 0, // sesuaikan dengan logika kamu
            'activities'        => [], // sesuaikan dengan model activity
        ];

        return view('dashboard', $data);
    }
}