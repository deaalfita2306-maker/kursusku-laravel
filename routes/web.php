<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - KursusKu Laravel
|--------------------------------------------------------------------------
*/

// Beranda (Pertemuan 7)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Pendaftaran Publik & Preview Form (Pertemuan 9)
Route::get('/pendaftaran', [EnrollmentController::class, 'pendaftaran'])->name('pendaftaran');
Route::post('/pendaftaran/preview', [EnrollmentController::class, 'preview'])->name('enrollments.preview');

// Modul Relasional Enrollment (Pertemuan 12)
Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
Route::get('/enrollments/create', [EnrollmentController::class, 'create'])->name('enrollments.create');
Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');

// JSON Ketersediaan Kursi Kursus untuk jQuery Ajax (Pertemuan 9, 10 & 11)
Route::get('/courses/{course}/availability', [CourseController::class, 'availability'])->name('courses.availability');

// Resource Routes Kursus - CRUD Lengkap (Pertemuan 10 & 11)
Route::resource('courses', CourseController::class);
