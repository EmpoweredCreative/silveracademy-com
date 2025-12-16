<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'user_id',
        'type',
        'title',
        'content',
        'image_path',
        'event_start_date',
        'event_end_date',
        'button_text',
        'button_url',
        'published_at',
    ];

    protected $casts = [
        'event_start_date' => 'datetime',
        'event_end_date' => 'datetime',
        'published_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
                
                // Ensure unique slug
                $originalSlug = $post->slug;
                $count = 1;
                while (static::where('slug', $post->slug)->exists()) {
                    $post->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

    /**
     * Get the author of the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to only published posts.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to only news posts.
     */
    public function scopeNews(Builder $query): Builder
    {
        return $query->where('type', 'news');
    }

    /**
     * Scope to only event posts.
     */
    public function scopeEvents(Builder $query): Builder
    {
        return $query->where('type', 'event');
    }

    /**
     * Scope to only upcoming events (not past).
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('type', 'event')
            ->where(function ($q) {
                $q->where('event_start_date', '>=', now())
                    ->orWhere('event_end_date', '>=', now());
            });
    }

    /**
     * Check if this is a news post.
     */
    public function isNews(): bool
    {
        return $this->type === 'news';
    }

    /**
     * Check if this is an event post.
     */
    public function isEvent(): bool
    {
        return $this->type === 'event';
    }

    /**
     * Check if this event has passed.
     */
    public function isPastEvent(): bool
    {
        if (!$this->isEvent()) {
            return false;
        }

        $endDate = $this->event_end_date ?? $this->event_start_date;
        return $endDate && $endDate->isPast();
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

