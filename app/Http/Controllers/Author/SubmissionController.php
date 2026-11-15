<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    /**
     * Рӯйхати ҳамаи супоришҳои интизори баҳодиҳӣ.
     */
    public function index(): View
    {
        // Ҳамаи супоришҳои интизори баҳодиҳӣ, ки ба курсҳои ин муаллиф тааллуқ доранд
        $submissions = AssignmentSubmission::query()
            ->with(['student', 'assignment.lesson.module.course'])
            ->whereHas('assignment.lesson.module.course', function ($q) {
                $q->where('author_id', auth()->id());
            })
            ->whereIn('status', ['submitted', 'pending'])
            ->latest()
            ->paginate(15);

        return view('author.submissions.index', compact('submissions'));
    }

    /**
     * Баҳодиҳии супориш.
     */
    public function grade(Request $request, AssignmentSubmission $submission): RedirectResponse
    {
        // Санҷиши моликияти курс
        if ($submission->assignment->lesson->module->course->author_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'score'    => ['required', 'integer', 'min:0', 'max:' . $submission->assignment->max_score],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $status = ($data['score'] >= ($submission->assignment->max_score / 2)) ? 'approved' : 'rejected';

        $submission->update([
            'score'    => $data['score'],
            'feedback' => $data['feedback'] ?? null,
            'status'   => $status,
        ]);

        return back()->with('success', 'Баҳо дода шуд: ' . $data['score'] . ' / ' . $submission->assignment->max_score);
    }
}
