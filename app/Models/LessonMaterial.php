<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'order_index',
    ];

    protected $casts = [
        'file_size'   => 'integer',
        'order_index' => 'integer',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function getHumanFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if (!$bytes) {
            return '';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 1) . ' ' . $units[$i];
    }

    public function getIsVideoAttribute(): bool
    {
        return $this->file_type === 'video';
    }

    public function getIsImageAttribute(): bool
    {
        return $this->file_type === 'image';
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->file_type === 'pdf';
    }

    public function getIsDocumentAttribute(): bool
    {
        return $this->file_type === 'document';
    }

    public function getIsPresentationAttribute(): bool
    {
        return $this->file_type === 'presentation';
    }

    public function getIsExternalVideoAttribute(): bool
    {
        return $this->is_video && str_starts_with($this->file_path, 'http');
    }
}
