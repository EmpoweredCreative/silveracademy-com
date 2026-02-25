<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentContactEmail extends Model
{
    public const MAX_PER_STUDENT = 4;

    protected $fillable = [
        'student_id',
        'email',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
