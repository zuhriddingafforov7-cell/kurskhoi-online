<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModuleController extends Controller
{
    /**
     * Шакли иловагии модул ба курс.
     */
    public function create(Course $course): View
    {
        $this->authorizeOwner($course);

        return view('author.modules.create', compact('course'));
    }

    /**
     * Нигоҳдории модул.
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeOwner($course);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'order_index' => ['required', 'integer', 'min:1'],
        ]);

        $data['course_id'] = $course->id;
        Module::create($data);

        return redirect()
            ->route('author.courses.show', $course)
            ->with('success', 'Модул илова шуд!');
    }

    /**
     * Шакли таҳрири модул.
     */
    public function edit(Module $module): View
    {
        $course = $module->course;
        $this->authorizeOwner($course);

        return view('author.modules.edit', compact('module', 'course'));
    }

    /**
     * Навсозии модул.
     */
    public function update(Request $request, Module $module): RedirectResponse
    {
        $course = $module->course;
        $this->authorizeOwner($course);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'order_index' => ['required', 'integer', 'min:1'],
        ]);

        $module->update($data);

        return redirect()
            ->route('author.courses.show', $course)
            ->with('success', 'Модул навсозӣ шуд!');
    }

    /**
     * Нест кардани модул.
     */
    public function destroy(Module $module): RedirectResponse
    {
        $course = $module->course;
        $this->authorizeOwner($course);

        try {
            $module->delete();
        } catch (\Throwable $e) {
            return back()->with('error', 'Модулро нест кардан ғайриимкон аст, зеро супоришҳои супоридашуда дорад.');
        }

        return redirect()
            ->route('author.courses.show', $course)
            ->with('success', 'Модул нест карда шуд.');
    }

    private function authorizeOwner(Course $course): void
    {
        if ($course->author_id !== auth()->id()) {
            abort(403, 'Шумо ба ин курс дастрасӣ надоред.');
        }
    }
}
