@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div>
        <p class="text-xs font-semibold text-pink-600 uppercase tracking-wider">Dashboard</p>
        <h1 class="text-2xl font-bold text-gray-900 mt-1">
            Selamat Datang, Admin KursusKu 👋
        </h1>
        <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas pembelajaran Anda hari ini.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Course -->
        <div class="bg-white rounded-xl border border-pink-100 p-5 shadow-sm">
            <div class="bg-pink-100 rounded-lg w-10 h-10 flex items-center justify-center mb-3">
                <span class="text-lg">📚</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalCourses ?? 0 }}</p>
            <p class="text-sm text-gray-500">Total Course</p>
        </div>

        <!-- Total Participant -->
        <div class="bg-white rounded-xl border border-pink-100 p-5 shadow-sm">
            <div class="bg-pink-100 rounded-lg w-10 h-10 flex items-center justify-center mb-3">
                <span class="text-lg">👥</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalParticipants ?? 0 }}</p>
            <p class="text-sm text-gray-500">Total Participant</p>
        </div>

        <!-- Enrollment Aktif -->
        <div class="bg-white rounded-xl border border-pink-100 p-5 shadow-sm">
            <div class="bg-pink-100 rounded-lg w-10 h-10 flex items-center justify-center mb-3">
                <span class="text-lg">📋</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $activeEnrollments ?? 0 }}</p>
            <p class="text-sm text-gray-500">Enrollment Aktif</p>
        </div>

        <!-- Tingkat Kehadiran -->
        <div class="bg-white rounded-xl border border-pink-100 p-5 shadow-sm">
            <div class="bg-pink-100 rounded-lg w-10 h-10 flex items-center justify-center mb-3">
                <span class="text-lg">🎯</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $attendanceRate ?? 0 }}%</p>
            <p class="text-sm text-gray-500">Tingkat Kehadiran</p>
        </div>

    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-white rounded-xl border border-pink-100 shadow-sm">
        <div class="p-5 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Aktivitas Terbaru</h2>
        </div>
        <div class="p-5 space-y-3">
            @forelse($activities ?? [] as $activity)
                <div class="flex items-start space-x-3 p-3 bg-pink-50 rounded-lg">
                    <div class="bg-pink-600 text-white rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0 text-xs">
                        📌
                    </div>
                    <div>
                        <p class="text-sm text-gray-700">{{ $activity->description }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-sm text-gray-400">
                    Belum ada aktivitas enrollment terbaru.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection