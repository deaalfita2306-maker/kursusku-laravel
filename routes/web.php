<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\DashboardController; // <-- TAMBAHKAN INI

// ==================== LOGIN ====================
Route::get('/login', function () {
    if (session('is_admin_logged_in')) {
        return redirect('/dashboard'); // <-- UBAH KE DASHBOARD
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    if ($request->username === 'admin' && $request->password === 'admin123') {
        session(['is_admin_logged_in' => true, 'admin_name' => 'Administrator']);
        return redirect('/dashboard')->with('success', 'Login berhasil! Selamat datang, Admin.'); // <-- UBAH KE DASHBOARD
    }

    return back()->withErrors(['login' => 'Username atau password salah!'])->withInput();
})->name('login.process');

Route::post('/logout', function () {
    session()->forget(['is_admin_logged_in', 'admin_name']);
    return redirect('/login');
})->name('logout');

// ==================== PUBLIK ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pendaftaran', [EnrollmentController::class, 'pendaftaran'])->name('pendaftaran');
Route::post('/pendaftaran/preview', [EnrollmentController::class, 'preview'])->name('enrollments.preview');

// ==================== DILINDUNGI (CEK MANUAL) ====================

// 1. ROUTE DASHBOARD (BARU)
Route::get('/dashboard', function () {
    if (!session('is_admin_logged_in')) return redirect('/login');
    return app(DashboardController::class)->index();
})->name('dashboard');

// 2. ROUTE COURSES
Route::get('/courses', function (Request $request) {
    if (!session('is_admin_logged_in')) return redirect('/login');
    return app(CourseController::class)->index($request);
})->name('courses.index');

Route::get('/courses/create', function () {
    if (!session('is_admin_logged_in')) return redirect('/login');
    return app(CourseController::class)->create();
})->name('courses.create');

Route::post('/courses', function (Request $request) {
    if (!session('is_admin_logged_in')) return redirect('/login');
    return app(CourseController::class)->store($request);
})->name('courses.store');

Route::get('/courses/{course}', function ($course) {
    if (!session('is_admin_logged_in')) return redirect('/login');
    return app(CourseController::class)->show($course);
})->name('courses.show');

Route::get('/courses/{course}/edit', function ($course) {
    if (!session('is_admin_logged_in')) return redirect('/login');
    return app(CourseController::class)->edit($course);
})->name('courses.edit');

Route::put('/courses/{course}', function (Request $request, $course) {
    if (!session('is_admin_logged_in')) return redirect('/login');
    return app(CourseController::class)->update($request, $course);
})->name('courses.update');

Route::delete('/courses/{course}', function ($course) {
    if (!session('is_admin_logged_in')) return redirect('/login');
    return app(CourseController::class)->destroy($course);
})->name('courses.destroy');

Route::get('/courses/{course}/availability', function ($course) {
    if (!session('is_admin_logged_in')) return redirect('/login');
    return app(CourseController::class)->availability($course);
})->name('courses.availability');

// 3. ROUTE ENROLLMENTS
Route::get('/enrollments', function () {
    if (!session('is_admin_logged_in')) return redirect('/login');
    return app(EnrollmentController::class)->index();
})->name('enrollments.index');