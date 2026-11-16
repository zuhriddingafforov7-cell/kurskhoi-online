<?php

namespace App\Models;

use App\Support\ModelMeta;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[Fillable(['title', 'description', 'author_id', 'category_id', 'status'])]
class Course extends Model
{
    use HasFactory;

    // Вазъиятҳои курс
    const STATUS_DRAFT     = 'draft';
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
     * Санҷед, ки оё донишҷӯ ба ин курс номнавис шудааст.
     */
    public function isEnrolledBy(int $userId): bool
    {
        return $this->enrollments()->where('user_id', $userId)->exists();
    }

    public function getLevelAttribute(): string
    {
        return ModelMeta::inferCourseLevel($this->title ?? '', $this->id);
    }

    public function getLevelLabelAttribute(): string
    {
        return ModelMeta::levelLabel($this->level);
    }

    public function getRejectionReasonAttribute(): ?string
    {
        return $this->id ? ModelMeta::get('course', $this->id, 'rejection_reason') : null;
    }

    public function getCoverImageAttribute(): ?string
    {
        return $this->attributes['cover_image']
            ?? ($this->id ? ModelMeta::get('course', $this->id, 'cover_image') : null);
    }
}
