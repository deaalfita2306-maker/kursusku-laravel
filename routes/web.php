// Tambahkan ini di dalam CourseController.php jika belum ada
public function availability(Course $course)
{
    $availableQuota = max(0, $course->quota - $course->enrollments()->count());
    
    return response()->json([
        'available' => $availableQuota > 0,
        'quota' => $course->quota,
        'enrolled' => $course->enrollments()->count(),
        'remaining' => $availableQuota
    ]);
}