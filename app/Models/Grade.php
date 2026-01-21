<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sort_order',
    ];

    /**
     * Get the teachers assigned to this grade.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'grade_teacher', 'grade_id', 'teacher_id')
            ->withTimestamps();
    }

    /**
     * Get the classrooms for this grade.
     * @deprecated Classrooms are being removed - use teachers() instead
     */
    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    /**
     * Get the students in this grade.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get announcements for this grade.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * Get calendar events for this grade.
     */
    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }
}



