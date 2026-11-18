<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var list<string>
     */
    protected $fillable = [
        'participant_id',
        'course_id',
        'status',
    ];

    /**
     * Relasi ke peserta pendaftar (Many-to-One).
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Relasi ke program kursus yang didaftar (Many-to-One).
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
