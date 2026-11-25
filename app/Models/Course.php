<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'fee', // <-- Perhatikan ini 'fee', bukan 'price'
        'quota',
        'start_date',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'fee' => 'integer',
            'quota' => 'integer',
        ];
    }

    /**
     * Relasi ke pendaftaran kursus (One-to-Many).
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Relasi ke peserta pendaftar melalui tabel pivot enrollments (Many-to-Many).
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class, 'enrollments')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Method untuk AJAX cek ketersediaan kursi (sesuai route web.php)
     */
    public function getAvailableQuotaAttribute()
    {
        return max(0, $this->quota - $this->enrollments()->count());
    }
}