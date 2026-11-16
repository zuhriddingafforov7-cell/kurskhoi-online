<?php

namespace App\Services;

use App\Models\AssignmentSubmission;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\DB;

class CourseCompletionService
{
    /**
     * Санҷиши хатми 100%-и дарсҳо ва тасдиқи ҳамаи супоришҳои курс,
     * ва тавлиди шаҳодатномаи беназир ҳангоми иҷрои ҳамаи шартҳо.
     */
    public function checkAndComplete(Enrollment $enrollment): bool
    {
        $course = $enrollment->course()->with('modules.lessons.assignment')->first();
        if (!$course) {
            return false;
        }

        $lessons = $course->modules->flatMap->lessons;
        $allLessonIds = $lessons->pluck('id');
        if ($allLessonIds->isEmpty()) {
            return false;
        }

        $completedLessons = LessonProgress::where('user_id', $enrollment->user_id)
            ->whereIn('lesson_id', $allLessonIds)
            ->where('is_completed', true)
            ->count();

        if ($completedLessons < $allLessonIds->count()) {
            return false;
        }

        $assignmentIds = $lessons->pluck('assignment')->filter()->pluck('id');
        if ($assignmentIds->isNotEmpty()) {
            $approvedAssignments = AssignmentSubmission::where('user_id', $enrollment->user_id)
                ->whereIn('assignment_id', $assignmentIds)
                ->where('status', 'approved')
                ->count();

            if ($approvedAssignments < $assignmentIds->count()) {
                return false;
            }
        }

        return DB::transaction(function () use ($enrollment): bool {
            if ($enrollment->status !== 'completed') {
                $enrollment->update([
                    'status'       => 'completed',
                    'completed_at' => $enrollment->completed_at ?? now(),
                ]);
            }

            $code = 'BIYO-' . date('Y') . '-' . str_pad((string) $enrollment->id, 5, '0', STR_PAD_LEFT);

            Certificate::firstOrCreate(
                ['enrollment_id' => $enrollment->id],
                [
                    'certificate_code' => $code,
                    'issue_date'       => now()->toDateString(),
                    'pdf_path'         => 'certificates/' . $code . '.pdf',
                ]
            );

            return true;
        });
    }
}
