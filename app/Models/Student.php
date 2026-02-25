<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'grade_id',
        'classroom_id',
        'status',
    ];

    /**
     * Get the grade this student is in.
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the classroom this student is in.
     * @deprecated Classrooms are being removed - students are now assigned directly to grades
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get the teachers for this student's grade.
     */
    public function teachers(): BelongsToMany
    {
        return $this->grade?->teachers() ?? collect();
    }

    /**
     * Get the parents of this student.
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id')
            ->withTimestamps();
    }

    /**
     * Get contact emails for sending codes (up to 4 per student).
     */
    public function contactEmails(): HasMany
    {
        return $this->hasMany(StudentContactEmail::class);
    }

    /**
     * Get all access codes for this student.
     */
    public function accessCodes(): HasMany
    {
        return $this->hasMany(StudentAccessCode::class);
    }

    /**
     * Get the single active access code for this student (if any).
     */
    public function activeAccessCode(): ?StudentAccessCode
    {
        return $this->accessCodes()->active()->first();
    }

    /**
     * Count of distinct parent accounts linked to this student.
     */
    public function currentLinkCount(): int
    {
        return $this->parents()->count();
    }
}



