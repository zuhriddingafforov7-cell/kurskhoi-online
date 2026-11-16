<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Services\CourseCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function __construct(private readonly CourseCompletionService $completionService)
    {
    }

    /**
     * Нишон додани дарс ба донишҷӯ.
     */
    public function show(Lesson $lesson): View
    {
        $user   = auth()->user();
        $module = $lesson->module;
        $course = $module->course;

        // Санҷиши номнависӣ
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->firstOrFail();

        // Санҷиши пайдарпайии дарсҳо
        $this->checkSequentialAccess($lesson, $user->id);

        $lesson->load(['assignment', 'materials']);
        $course->load(['modules.lessons.assignment']);

        $allLessonIds = $course->modules->flatMap->lessons->pluck('id');
        $completedLessonIds = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $allLessonIds)
            ->where('is_completed', true)
            ->pluck('lesson_id')
            ->all();

        $progress = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        $submission = $lesson->assignment ? $lesson->assignment->submissionBy($user->id) : null;
        $isPreview = false;

        return view('student.lessons.show', compact(
            'lesson',
            'module',
            'course',
            'progress',
            'submission',
            'enrollment',
            'completedLessonIds',
            'isPreview'
        ));
    }

    /**
     * Нишон додани дарсро ҳамчун анҷомшуда.
     */
    public function markComplete(Lesson $lesson): RedirectResponse
    {
        $user   = auth()->user();
        $module = $lesson->module;
        $course = $module->course;

        // Санҷиши номнависӣ
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->firstOrFail();

        // Пешгирии гузаштан аз дарси қулфшуда ба таври мустақим
        $this->checkSequentialAccess($lesson, $user->id);

        // Нигоҳдорӣ ё навсозии пешрафт
        LessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['is_completed' => true, 'completed_at' => now()]
        );

        // Санҷиши анҷоми тамоми курс (дарсҳо ва супоришҳои тасдиқшуда)
        $this->completionService->checkAndComplete($enrollment);

        // Дарси навбатӣ
        $nextLesson = $this->getNextLesson($lesson);

        if ($nextLesson) {
            return redirect()
                ->route('student.lessons.show', $nextLesson)
                ->with('success', 'Дарс анҷом ёфт! Дарси навбатӣ кушода шуд.');
        }

        return redirect()
            ->route('student.courses.show', $course)
            ->with('success', 'Табрик! Шумо ҳамаи дарсҳои ин курсро анҷом додед!');
    }

    /**
     * Санҷиши дастрасии пайдарпай.
     */
    private function checkSequentialAccess(Lesson $lesson, int $userId): void
    {
        if (!$lesson->isUnlockedFor($userId)) {
            abort(403, 'Аввал дарсҳо ва модулҳои пешинро анҷом диҳед.');
        }
    }

    /**
     * Дарси навбатиро пайдо кун.
     */
    private function getNextLesson(Lesson $lesson): ?Lesson
    {
        $module = $lesson->module;
        $course = $module->course;

        // Дарси навбатӣ дар ҳамин модул
        $next = $module->lessons()
            ->where('order_index', '>', $lesson->order_index)
            ->orderBy('order_index')
            ->first();

        if ($next) {
            return $next;
        }

        // Дарси аввали модули навбатӣ
        $nextModule = $course->modules()
            ->where('order_index', '>', $module->order_index)
            ->orderBy('order_index')
            ->first();

        return $nextModule?->lessons()->orderBy('order_index')->first();
    }
}
