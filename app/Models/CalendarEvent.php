<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scope',
        'grade_id',
        'classroom_id',
        'title',
        'description',
        'event_date',
        'event_end_date',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'event_end_date' => 'datetime',
    ];

    /**
     * Get the author of the event.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the grade this event is for (if grade-scoped).
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the classroom this event is for (if classroom-scoped).
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Scope to school-wide events.
     */
    public function scopeSchoolWide(Builder $query): Builder
    {
        return $query->where('scope', 'school_wide');
    }

    /**
     * Scope to upcoming events.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('event_date', '>=', now())
                ->orWhere('event_end_date', '>=', now());
        });
    }

    /**
     * Scope events visible to a specific student.
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

