<?php

namespace App\Models;

use App\Support\ModelMeta;
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

    /**
     * Санҷиши кушода будани дарс барои донишҷӯ тибқи пайдарпайии модул ва дарсҳо.
     */
    public function isUnlockedFor(int $userId): bool
    {
        $module = $this->module;
        if (!$module) {
            return false;
        }

        $prevLessonIds = $module->lessons()
            ->where('order_index', '<', $this->order_index)
            ->pluck('id');

        if ($prevLessonIds->isNotEmpty()) {
            $completedInModule = LessonProgress::where('user_id', $userId)
                ->whereIn('lesson_id', $prevLessonIds)
                ->where('is_completed', true)
                ->count();

            if ($completedInModule < $prevLessonIds->count()) {
                return false;
            }
        }

        $prevModules = $module->course->modules()
            ->where('order_index', '<', $module->order_index)
            ->with('lessons:id,module_id')
            ->get();

        $prevModuleLessonIds = $prevModules->flatMap->lessons->pluck('id');
        if ($prevModuleLessonIds->isNotEmpty()) {
            $completedPrevModules = LessonProgress::where('user_id', $userId)
                ->whereIn('lesson_id', $prevModuleLessonIds)
                ->where('is_completed', true)
                ->count();

            if ($completedPrevModules < $prevModuleLessonIds->count()) {
                return false;
            }
        }

        return true;
    }

    public function getDurationMinutesAttribute(): ?int
    {
        if (array_key_exists('duration_minutes', $this->attributes)) {
            return $this->attributes['duration_minutes'] !== null ? (int) $this->attributes['duration_minutes'] : null;
        }

        $val = $this->id ? ModelMeta::get('lesson', $this->id, 'duration_minutes') : null;

        return $val !== null ? (int) $val : null;
    }

    public function getStatusAttribute(): string
    {
        return $this->attributes['status']
            ?? ($this->id ? (string) ModelMeta::get('lesson', $this->id, 'status', 'published') : 'published');
    }

    /**
     * Табдил додани истиноди YouTube/Vimeo ба суроғаи embed.
     */
    public static function toEmbedUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/shorts\/)([A-Za-z0-9_-]{6,})/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return $url;
    }

    public function getEmbedVideoUrlAttribute(): ?string
    {
        return self::toEmbedUrl($this->video_url);
    }
}
