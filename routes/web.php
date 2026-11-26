<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ==================== HALAMAN PUBLIK (GUEST, USER, ADMIN) ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/informasi', [HomeController::class, 'info'])->name('info');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}/availability', [CourseController::class, 'availability'])
    ->whereNumber('course')
    ->name('courses.availability');

// ==================== AUTENTIKASI (GUEST ONLY) ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ==================== ROUTE TERAUTENTIKASI (USER & ADMIN) ====================
Route::middleware(['auth', 'role:user,admin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard User (otomatis mengarahkan admin ke admin.dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Enrollment Pengguna
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enrollSelf'])
        ->whereNumber('course')
        ->name('courses.enroll');
    Route::get('/pendaftaran', [EnrollmentController::class, 'pendaftaran'])->name('pendaftaran');
    Route::post('/pendaftaran/preview', [EnrollmentController::class, 'preview'])->name('enrollments.preview');
});

// ==================== ROUTE KHUSUS ADMIN ====================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    // CRUD Kursus (Admin Only)
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->whereNumber('course')->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->whereNumber('course')->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->whereNumber('course')->name('courses.destroy');

    // Manajemen Enrollment oleh Admin
    Route::get('/enrollments/create', [EnrollmentController::class, 'create'])->name('enrollments.create');
    Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');

    // Manajemen Pengguna (Admin Only)
    Route::resource('admin/users', AdminUserController::class)
        ->names('admin.users')
        ->except(['show']);
});

// Detail Kursus Publik (diletakkan setelah /courses/create agar tidak bentrok)
Route::get('/courses/{course}', [CourseController::class, 'show'])
    ->whereNumber('course')
    ->name('courses.show');
