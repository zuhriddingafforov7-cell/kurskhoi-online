<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonController extends Controller
{
    /**
     * Нишон додани дарс ба талаба.
     */
    public function show(Lesson $lesson): View
    {
        $user   = auth()->user();
        $module = $lesson->module;
        $course = $module->course;

        // Санҷиши ёзнашинӣ
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->firstOrFail();

        // Санҷиши пешапаймоӣ: оё дарсҳои пешин анҷом ёфтаанд?
        $this->checkSequentialAccess($lesson, $user->id);

        $lesson->load(['assignment', 'materials']);
        $progress = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        $submission = null;
        if ($lesson->assignment) {
            $submission = $lesson->assignment->submissionBy($user->id);
        }

        return view('student.lessons.show', compact('lesson', 'module', 'course', 'progress', 'submission', 'enrollment'));
    }

    /**
     * Нишон додани дарсро ҳамчун анҷомшуда.
     */
    public function markComplete(Lesson $lesson): RedirectResponse
    {
        $user   = auth()->user();
        $module = $lesson->module;
        $course = $module->course;

        // Санҷиши ёзнашинӣ
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->firstOrFail();

        // Нигоҳдорӣ ё навсозии пешрафт
        LessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['is_completed' => true, 'completed_at' => now()]
        );

        // Санҷиши анҷоми тамоми курс
        $this->checkCourseCompletion($enrollment);

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
        $module  = $lesson->module;
        $course  = $module->course;

        // Дарсҳои пеш аз ин дарс дар ҳамин модул
        $prevLessonsInModule = $module->lessons()
            ->where('order_index', '<', $lesson->order_index)
            ->pluck('id');

        if ($prevLessonsInModule->isNotEmpty()) {
            $completedCount = LessonProgress::where('user_id', $userId)
                ->whereIn('lesson_id', $prevLessonsInModule)
                ->where('is_completed', true)
                ->count();

            if ($completedCount < $prevLessonsInModule->count()) {
                abort(403, 'Аввал дарсҳои пешинро анҷом диҳед.');
            }
        }

        // Санҷиши модулҳои пеш аз ин модул
        $prevModules = $module->course->modules()
            ->where('order_index', '<', $module->order_index)
            ->with('lessons')
            ->get();

        foreach ($prevModules as $prevModule) {
            $lessonIds = $prevModule->lessons->pluck('id');
            if ($lessonIds->isEmpty()) {
                continue;
            }
            $completedCount = LessonProgress::where('user_id', $userId)
                ->whereIn('lesson_id', $lessonIds)
                ->where('is_completed', true)
                ->count();

            if ($completedCount < $lessonIds->count()) {
                abort(403, 'Аввал модулҳои пешинро анҷом диҳед.');
            }
        }
    }

    /**
     * Санҷиши анҷоми тамоми курс ва эҷоди сертификат.
     */
    private function checkCourseCompletion(Enrollment $enrollment): void
    {
        $course = $enrollment->course->load('modules.lessons');

        $allLessonIds = $course->modules->flatMap->lessons->pluck('id');
        if ($allLessonIds->isEmpty()) {
            return;
        }

        $completedCount = LessonProgress::where('user_id', $enrollment->user_id)
            ->whereIn('lesson_id', $allLessonIds)
            ->where('is_completed', true)
            ->count();

        if ($completedCount >= $allLessonIds->count() && $enrollment->status !== 'completed') {
            $enrollment->update([
                'status'       => 'completed',
                'completed_at' => now(),
            ]);

            // Эҷоди сертификат
            if (!$enrollment->certificate) {
                $code = 'BIYO-' . date('Y') . '-' . str_pad($enrollment->id, 5, '0', STR_PAD_LEFT);

                \App\Models\Certificate::create([
                    'enrollment_id'    => $enrollment->id,
                    'certificate_code' => $code,
                    'issue_date'       => now()->toDateString(),
                    'pdf_path'         => 'certificates/' . $code . '.pdf',
                ]);
            }
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
