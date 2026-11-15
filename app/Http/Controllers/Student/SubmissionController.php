<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use App\Models\Assignment;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    /**
     * Нишон додани шакли супориш.
     */
    public function show(Assignment $assignment): View
    {
        $user    = auth()->user();
        $lesson  = $assignment->lesson;
        $course  = $lesson->module->course;

        // Санҷиши ёзнашинӣ
        Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->firstOrFail();

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->first();

        return view('student.assignments.show', compact('assignment', 'lesson', 'course', 'submission'));
    }

    /**
     * Нигоҳдории ҷавоби талаба.
     */
    public function store(Request $request, Assignment $assignment): RedirectResponse
    {
        $user    = auth()->user();
        $lesson  = $assignment->lesson;
        $course  = $lesson->module->course;

        // Санҷиши ёзнашинӣ
        Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->firstOrFail();

        // Агар қаблан қабул шуда бошад (approved), дигар тағйир дода намешавад
        $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->first();

        if ($existing) {
            return back()->with('error', 'Супориши шумо аллакай қабул шудааст.');
        }

        $data = $request->validate([
            'answer_text' => ['nullable', 'string'],
            'file'        => ['nullable', 'file', 'max:20480'],
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'user_id' => $user->id],
            [
                'submission_text' => $data['answer_text'] ?? null,
                'file_path'       => $filePath,
                'status'          => 'submitted',
                'score'           => null,
                'feedback'        => null,
            ]
        );

        return redirect()
            ->route('student.lessons.show', $lesson)
            ->with('success', 'Супориши шумо бо муваффақият фиристода шуд!');
    }
}
