<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'content_type',
        'body_text',
        'video_url',
        'file_path',
        'order_index',
    ];

    /**
     * Алиас барои content_text мутобиқ ба body_text дар база.
     */
    public function getContentTextAttribute(): ?string
    {
        return $this->attributes['body_text'] ?? null;
    }

    public function setContentTextAttribute(?string $value): void
    {
        $this->attributes['body_text'] = $value;
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function assignment(): HasOne
    {
        return $this->hasOne(Assignment::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(LessonMaterial::class)->orderBy('order_index');
    }

    /**
     * Санҷед, ки оё истифодабаранда ин дарсро анҷом додааст.
     */
    public function isCompletedBy(int $userId): bool
    {
        return $this->progress()->where('user_id', $userId)->where('is_completed', true)->exists();
    }
}
