<?php

namespace App\Models;

use App\Support\ModelMeta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'user_id',
        'submission_text',
        'answer_text',
        'file_path',
        'score',
        'feedback',
        'status',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAnswerTextAttribute(): ?string
    {
        return $this->attributes['submission_text'] ?? $this->attributes['answer_text'] ?? null;
    }

    public function setAnswerTextAttribute(?string $value): void
    {
        $this->attributes['submission_text'] = $value;
    }

    public function isPending(): bool
    {
        return $this->status === 'submitted' || $this->status === 'pending';
    }

    public function isGraded(): bool
    {
        return $this->status === 'approved' || $this->status === 'rejected' || $this->status === 'graded';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Таърихи баҳогузорӣ ва тақризҳо.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getGradingHistoryAttribute(): array
    {
        return $this->id ? (array) ModelMeta::get('submission', $this->id, 'history', []) : [];
    }
}
