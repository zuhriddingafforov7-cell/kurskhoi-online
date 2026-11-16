<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class SubmissionController extends Controller
{
    /**
     * Нишон додани шакли супориш.
     */
    public function show(Assignment $assignment): View
    {
        $user   = auth()->user();
        $lesson = $assignment->lesson;
        $course = $lesson->module->course;

        // Санҷиши номнависӣ
        Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->firstOrFail();

        if (!$lesson->isUnlockedFor($user->id)) {
            abort(403, 'Аввал дарсҳои пешинро анҷом диҳед.');
        }

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->first();

        return view('student.assignments.show', compact('assignment', 'lesson', 'course', 'submission'));
    }

    /**
     * Нигоҳдории ҷавоби донишҷӯ бо файли то 5 МБ дар диски хусусӣ.
     */
    public function store(Request $request, Assignment $assignment): RedirectResponse
    {
        $user   = auth()->user();
        $lesson = $assignment->lesson;
        $course = $lesson->module->course;

        // Санҷиши номнависӣ
        Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->firstOrFail();

        if (!$lesson->isUnlockedFor($user->id)) {
            abort(403, 'Аввал дарсҳои пешинро анҷом диҳед.');
        }

        // Агар қаблан қабул шуда бошад (approved), дигар тағйир дода намешавад
        $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing && $existing->status === 'approved') {
            return back()->with('error', 'Супориши шумо аллакай қабул шудааст.');
        }

        $data = $request->validate([
            'answer_text' => ['nullable', 'string', 'max:10000'],
            'file'        => ['nullable', 'file', 'max:5120', 'mimes:pdf,zip,docx,doc'],
        ], [
            'file.max'   => 'Ҳаҷми файл набояд аз 5 МБ зиёд бошад.',
            'file.mimes' => 'Танҳо форматҳои PDF, ZIP, DOC ва DOCX иҷозат дода шудаанд.',
        ]);

        $answerText = trim((string) ($data['answer_text'] ?? ''));
        if ($answerText === '' && !$request->hasFile('file') && !$existing?->file_path) {
            return back()
                ->withInput()
                ->withErrors(['answer_text' => 'Лутфан матни ҷавобро ворид кунед ё файлро замима намоед.']);
        }

        $filePath = $existing?->file_path;
        if ($request->hasFile('file')) {
            $newPath = $request->file('file')->store('submissions/' . $assignment->id, 'local');
            if ($filePath) {
                Storage::disk('local')->delete($filePath);
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $newPath;
        }

        AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'user_id' => $user->id],
            [
                'submission_text' => $answerText !== '' ? $answerText : null,
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

    /**
     * Боргирии бехатари файли супориш аз диски хусусӣ.
     */
    public function download(AssignmentSubmission $submission): Response
    {
        $user = auth()->user();
        $courseAuthorId = $submission->assignment->lesson->module->course->author_id;

        if ($user->id !== $submission->user_id && $user->id !== $courseAuthorId && !$user->isAdmin()) {
            abort(403, 'Шумо ба боргирии ин файл иҷозат надоред.');
        }

        $path = $submission->file_path;
        if (!$path || str_contains($path, '..')) {
            abort(404, 'Файл ёфт нашуд.');
        }

        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->download($path, basename($path));
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path, basename($path));
        }

        abort(404, 'Файл дар нигоҳдорӣ ёфт нашуд.');
    }
}
