<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAccessCode extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_REVOKED = 'revoked';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'student_id',
        'code_hash',
        'code_last4',
        'plain_code_encrypted',
        'max_links',
        'status',
        'expires_at',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Check if this code is valid: active, not expired, and under link cap.
     */
    public function isValid(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }
        $linkCount = $this->student->currentLinkCount();

        return $linkCount < $this->max_links;
    }

    /**
     * Get decrypted plain code (for email sending only). Returns null if not stored.
     */
    public function getDecryptedPlainCode(): ?string
    {
        if (empty($this->plain_code_encrypted)) {
            return null;
        }
        try {
            return Crypt::decryptString($this->plain_code_encrypted);
        } catch (\Throwable) {
            return null;
        }
    }
}
