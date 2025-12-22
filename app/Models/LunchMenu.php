<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class LunchMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'week_start',
        'content',
    ];

    protected $casts = [
        'week_start' => 'date',
    ];

    /**
     * Get the author of the menu.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to current week's menu.
     */
    public function scopeCurrentWeek(Builder $query): Builder
    {
        $startOfWeek = now()->startOfWeek();
        return $query->where('week_start', $startOfWeek);
    }

    /**
     * Scope to upcoming menus.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('week_start', '>=', now()->startOfWeek());
    }
}



