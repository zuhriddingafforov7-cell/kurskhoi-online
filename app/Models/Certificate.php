<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'certificate_code',
        'issue_date',
        'issued_at',
        'pdf_path',
        'file_path',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'issued_at'  => 'datetime',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function getIssuedAtAttribute()
    {
        return $this->attributes['issue_date'] ?? $this->attributes['issued_at'] ?? null;
    }

    public function setIssuedAtAttribute($value): void
    {
        $this->attributes['issue_date'] = $value;
    }

    public function getFilePathAttribute(): ?string
    {
        return $this->attributes['pdf_path'] ?? $this->attributes['file_path'] ?? null;
    }

    public function setFilePathAttribute(?string $value): void
    {
        $this->attributes['pdf_path'] = $value;
    }
}
