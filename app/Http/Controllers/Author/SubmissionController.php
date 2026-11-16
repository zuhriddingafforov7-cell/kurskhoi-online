<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use App\Models\Enrollment;
use App\Services\CourseCompletionService;
use App\Support\ModelMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function __construct(private readonly CourseCompletionService $completionService)
    {
    }

    /**
     * Рӯйхати супоришҳои донишҷӯён (интизор, тасдиқшуда, радшуда ё ҳама).
     */
    public function index(Request $request): View
    {
        $authorId = auth()->id();
        $filter = $request->input('status', 'submitted');

        $baseQuery = AssignmentSubmission::query()
            ->whereHas('assignment.lesson.module.course', fn ($q) => $q->where('author_id', $authorId));

        $counts = [
            'submitted' => (clone $baseQuery)->whereIn('status', ['submitted', 'pending'])->count(),
            'approved'  => (clone $baseQuery)->where('status', 'approved')->count(),
            'rejected'  => (clone $baseQuery)->where('status', 'rejected')->count(),
            'all'       => (clone $baseQuery)->count(),
        ];

        $query = (clone $baseQuery)
            ->with(['student', 'assignment.lesson.module.course']);

        if ($filter === 'approved') {
            $query->where('status', 'approved');
        } elseif ($filter === 'rejected') {
            $query->where('status', 'rejected');
        } elseif ($filter !== 'all') {
            $filter = 'submitted';
            $query->whereIn('status', ['submitted', 'pending']);
        }

        $submissions = $query->latest('updated_at')->paginate(15)->withQueryString();

        return view('author.submissions.index', compact('submissions', 'filter', 'counts'));
    }

    /**
     * Баҳодиҳӣ, тасдиқ ё радди супориш.
     */
    public function grade(Request $request, AssignmentSubmission $submission): RedirectResponse
    {
        $course = $submission->assignment->lesson->module->course;
        if ($course->author_id !== auth()->id()) {
            abort(403);
        }

        $maxScore = $submission->assignment->max_score;
        $data = $request->validate([
            'score'    => ['required', 'integer', 'min:0', 'max:' . $maxScore],
            'feedback' => ['nullable', 'string', 'max:2000'],
            'status'   => ['nullable', 'in:approved,rejected'],
        ], [
            'score.min' => 'Баҳо наметавонад аз 0 кам бошад.',
            'score.max' => "Баҳо наметавонад аз {$maxScore} зиёд бошад.",
        ]);

        $status = $request->filled('status')
            ? $data['status']
            : 'approved';

        $submission->update([
            'score'    => $data['score'],
            'feedback' => $data['feedback'] ?? null,
            'status'   => $status,
        ]);

        // Сабти таърихи баҳогузорӣ
        $history = (array) ModelMeta::get('submission', $submission->id, 'history', []);
        $history[] = [
            'graded_at' => now()->format('d.m.Y H:i'),
            'grader'    => auth()->user()->name,
            'score'     => (int) $data['score'],
            'max_score' => (int) $maxScore,
            'status'    => $status,
            'feedback'  => $data['feedback'] ?? null,
        ];
        ModelMeta::set('submission', $submission->id, 'history', $history);

        if ($status === 'approved') {
            $enrollment = Enrollment::where('user_id', $submission->user_id)
                ->where('course_id', $course->id)
                ->first();
            if ($enrollment) {
                $this->completionService->checkAndComplete($enrollment);
            }
        }

        $statusLabel = $status === 'approved' ? 'Қабул шуд' : 'Рад карда шуд (барои ислоҳ)';

        return back()->with('success', "Баҳо гузошта шуд: {$data['score']} / {$maxScore} ({$statusLabel})");
    }
}
