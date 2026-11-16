<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ===== Муносибатҳо =====

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'author_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function certificates(): HasManyThrough
    {
        return $this->hasManyThrough(Certificate::class, Enrollment::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class, 'user_id');
    }

    // ===== Ёрдамчиҳои нақш =====

    public function isAuthor(): bool
    {
        return $this->role === 'author';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : func_get_args();
        return in_array($this->role, $roles, true);
    }

    /**
     * Санҷед, ки оё истифодабаранда ба курси мушаххас номнавис шудааст.
     */
    public function isEnrolledIn(int $courseId): bool
    {
        return $this->enrollments()->where('course_id', $courseId)->exists();
    }

    /**
     * Фоизи пешрафт дар курс.
     */
    public function progressInCourse(Course $course): int
    {
        $course->loadMissing('modules.lessons');
        $totalLessons = $course->modules->flatMap->lessons->count();
        if ($totalLessons === 0) {
            return 0;
        }
        $lessonIds = $course->modules->flatMap->lessons->pluck('id');
        $completed = $this->lessonProgress()
            ->whereIn('lesson_id', $lessonIds)
            ->where('is_completed', true)
            ->count();

        return (int) round(($completed / $totalLessons) * 100);
    }
}
