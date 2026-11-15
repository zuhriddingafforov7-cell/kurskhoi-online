<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function create(Lesson $lesson): View
    {
        $this->authorizeLesson($lesson);
        return view('author.assignments.create', compact('lesson'));
    }

    public function store(Request $request, Lesson $lesson): RedirectResponse
    {
        $this->authorizeLesson($lesson);

        if ($lesson->assignment) {
            return back()->with('error', 'Ин дарс аллакай супориш дорад.');
        }

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'max_score'   => ['required', 'integer', 'min:1', 'max:100'],
        ]);
        $data['lesson_id'] = $lesson->id;

        Assignment::create($data);

        return redirect()
            ->route('author.courses.show', $lesson->module->course)
            ->with('success', 'Супориш иловa шуд!');
    }

    public function edit(Assignment $assignment): View
    {
        $this->authorizeLesson($assignment->lesson);
        return view('author.assignments.edit', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $this->authorizeLesson($assignment->lesson);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'max_score'   => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $assignment->update($data);

        return redirect()
            ->route('author.courses.show', $assignment->lesson->module->course)
            ->with('success', 'Супориш навсозӣ шуд!');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $course = $assignment->lesson->module->course;
        $this->authorizeLesson($assignment->lesson);
        $assignment->delete();

        return redirect()
            ->route('author.courses.show', $course)
            ->with('success', 'Супориш нест карда шуд.');
    }

    private function authorizeLesson(Lesson $lesson): void
    {
        if ($lesson->module->course->author_id !== auth()->id()) {
            abort(403);
        }
    }
}
