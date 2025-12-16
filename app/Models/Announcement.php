<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scope',
        'grade_id',
        'classroom_id',
        'title',
        'content',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the author of the announcement.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the grade this announcement is for (if grade-scoped).
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the classroom this announcement is for (if classroom-scoped).
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Scope to only published announcements.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to school-wide announcements.
     */
    public function scopeSchoolWide(Builder $query): Builder
    {
        return $query->where('scope', 'school_wide');
    }

    /**
     * Scope announcements visible to a specific student.
     */
    public function scopeVisibleToStudent(Builder $query, Student $student): Builder
    {
        return $query->where(function ($q) use ($student) {
            $q->where('scope', 'school_wide')
                ->orWhere(function ($q) use ($student) {
                    $q->where('scope', 'grade')
                        ->where('grade_id', $student->grade_id);
                })
                ->orWhere(function ($q) use ($student) {
                    $q->where('scope', 'classroom')
                        ->where('classroom_id', $student->classroom_id);
                });
        });
    }
}

