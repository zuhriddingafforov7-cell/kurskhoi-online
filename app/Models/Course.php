<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[Fillable(['title', 'description', 'author_id', 'category_id', 'status', 'cover_image'])]
class Course extends Model
{
    use HasFactory;

    // Вазъиятҳои курс
    const STATUS_PENDING   = 'pending';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REJECTED  = 'rejected';

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('order_index');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Enrollment::class, 'course_id', 'id', 'id', 'user_id');
    }

    // Скоупҳо
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Шумораи дарсҳо дар курс.
     */
    public function getLessonsCountAttribute(): int
    {
        return $this->modules()->withCount('lessons')->get()->sum('lessons_count');
    }

    /**
     * Санҷед, ки оё талаба ба ин курс ёзнашинӣ кардааст.
     */
    public function isEnrolledBy(int $userId): bool
    {
        return $this->enrollments()->where('user_id', $userId)->exists();
    }
}
