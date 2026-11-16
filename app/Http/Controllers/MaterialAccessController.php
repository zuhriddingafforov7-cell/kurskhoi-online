<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonMaterial;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class MaterialAccessController extends Controller
{
    /**
     * Пахши дохилӣ ё пешнамоиши бехатари маводи дарс (видео, PDF, тасвир).
     */
    public function streamMaterial(LessonMaterial $material): Response
    {
        $lesson = $material->lesson;
        $this->authorizeLessonAccess($lesson);

        $disk = $this->resolveDisk($material->file_path);
        if (!$disk) {
            abort(404, 'Файл ёфт нашуд.');
        }

        $headers = [
            'X-Content-Type-Options' => 'nosniff',
        ];
        if ($material->mime_type) {
            $headers['Content-Type'] = $material->mime_type;
        }

        return Storage::disk($disk)->response($material->file_path, basename($material->file_path), $headers);
    }

    /**
     * Боргирии бехатари маводи дарс.
     */
    public function downloadMaterial(LessonMaterial $material): Response
    {
        $lesson = $material->lesson;
        $this->authorizeLessonAccess($lesson);

        $disk = $this->resolveDisk($material->file_path);
        if (!$disk) {
            abort(404, 'Файл ёфт нашуд.');
        }

        $ext = pathinfo($material->file_path, PATHINFO_EXTENSION);
        $safeBase = preg_replace('/[^\p{L}\p{N}\s\-_.]/u', '_', $material->title ?: 'material');
        $downloadName = $ext ? "{$safeBase}.{$ext}" : $safeBase;

        return Storage::disk($disk)->download($material->file_path, $downloadName);
    }

    /**
     * Боргирии файли асосии дарс (мутобиқати таърихӣ).
     */
    public function downloadLessonAttachment(Lesson $lesson): Response
    {
        $this->authorizeLessonAccess($lesson);

        $disk = $this->resolveDisk($lesson->file_path);
        if (!$disk) {
            abort(404, 'Файл ёфт нашуд.');
        }

        return Storage::disk($disk)->download($lesson->file_path, basename($lesson->file_path));
    }

    /**
     * Намоиши сурати муқоваи курс барои феҳристи курсҳо.
     */
    public function courseCover(Course $course): Response
    {
        if ($course->status !== Course::STATUS_PUBLISHED && (!auth()->check() || (auth()->id() !== $course->author_id && !auth()->user()->isAdmin()))) {
            abort(404);
        }

        $path = $course->cover_image;
        $disk = $this->resolveDisk($path);
        if (!$disk) {
            abort(404);
        }

        return Storage::disk($disk)->response($path);
    }

    private function authorizeLessonAccess(Lesson $lesson): void
    {
        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        $course = $lesson->module->course;
        if ($user->isAdmin() || $course->author_id === $user->id) {
            return;
        }

        $enrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        if (!$enrolled) {
            abort(403, 'Шумо ба ин курс номнавис нашудаед.');
        }

        if (!$lesson->isUnlockedFor($user->id)) {
            abort(403, 'Аввал дарсҳои пешинро анҷом диҳед.');
        }
    }

    private function resolveDisk(?string $path): ?string
    {
        if (!$path || str_contains($path, '..') || str_starts_with($path, 'http')) {
            return null;
        }

        if (Storage::disk('local')->exists($path)) {
            return 'local';
        }

        if (Storage::disk('public')->exists($path)) {
            return 'public';
        }

        return null;
    }
}
