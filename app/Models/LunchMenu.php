<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class LunchMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'menu_date',
        'content',
    ];

    protected $casts = [
        'menu_date' => 'date',
    ];

    /**
     * Get the author of the menu.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to get menu for a specific date.
     */
    public function scopeForDate(Builder $query, $date): Builder
    {
        return $query->where('menu_date', Carbon::parse($date)->format('Y-m-d'));
    }

    /**
     * Scope to get menus for this week (Monday-Friday).
     */
    public function scopeThisWeek(Builder $query): Builder
    {
        $monday = now()->startOfWeek();
        $friday = now()->startOfWeek()->addDays(4);
        
        return $query->whereBetween('menu_date', [$monday, $friday])
            ->orderBy('menu_date');
    }

    /**
     * Scope to get menus for a specific month.
     */
    public function scopeForMonth(Builder $query, int $year, int $month): Builder
    {
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        return $query->whereBetween('menu_date', [$startOfMonth, $endOfMonth])
            ->orderBy('menu_date');
    }

    /**
     * Scope to get menus within a date range.
     */
    public function scopeInRange(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('menu_date', [
            Carbon::parse($startDate)->format('Y-m-d'),
            Carbon::parse($endDate)->format('Y-m-d')
        ])->orderBy('menu_date');
    }

    /**
     * Scope to get upcoming menus (from today forward).
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('menu_date', '>=', now()->format('Y-m-d'))
            ->orderBy('menu_date');
    }

    /**
     * Legacy scope for backward compatibility.
     * @deprecated Use thisWeek() instead
     */
    public function scopeCurrentWeek(Builder $query): Builder
    {
        return $this->scopeThisWeek($query);
    }

    /**
     * Get the day of week name for the menu.
     */
    public function getDayNameAttribute(): string
    {
        return $this->menu_date->format('l'); // Monday, Tuesday, etc.
    }

    /**
     * Get the short day name.
     */
    public function getShortDayNameAttribute(): string
    {
        return $this->menu_date->format('D'); // Mon, Tue, etc.
    }

    /**
     * Get formatted date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->menu_date->format('M j'); // Jan 15
    }
}
