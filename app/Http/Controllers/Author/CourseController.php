<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Рӯйхати курсҳои муаллиф.
     */
    public function index(): View
    {
        $courses = auth()->user()
            ->courses()
            ->with('category')
            ->withCount('enrollments')
            ->latest()
            ->paginate(10);

        return view('author.courses.index', compact('courses'));
    }

    /**
     * Шакли эҷоди курси нав.
     */
    public function create(): View
    {
        $categories = Category::orderBy('title')->get();
        return view('author.courses.create', compact('categories'));
    }

    /**
     * Нигоҳдории курси нав.
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['author_id'] = auth()->id();
        $data['status']    = Course::STATUS_PENDING;

        Course::create($data);

        return redirect()
            ->route('author.courses.index')
            ->with('success', 'Курс бо муваффақият сохта шуд ва барои тасдиқ фиристода шуд!');
    }

    /**
     * Нишон додани курси мушаххас.
     */
    public function show(Course $course): View
    {
        $this->authorizeOwner($course);

        $course->load(['modules.lessons', 'category']);
        $submissions = collect();

        foreach ($course->modules as $module) {
            foreach ($module->lessons as $lesson) {
                if ($lesson->assignment) {
                    $subs = $lesson->assignment->submissions()
                        ->with('student')
                        ->where('status', 'pending')
                        ->get();
                    $submissions = $submissions->merge($subs);
                }
            }
        }

        return view('author.courses.show', compact('course', 'submissions'));
    }

    /**
     * Шакли таҳрири курс.
     */
    public function edit(Course $course): View
    {
        $this->authorizeOwner($course);
        $categories = Category::orderBy('title')->get();
        return view('author.courses.edit', compact('course', 'categories'));
    }

    /**
     * Навсозии курс.
     */
    public function update(StoreCourseRequest $request, Course $course): RedirectResponse
    {
        $this->authorizeOwner($course);

        $data = $request->validated();
        // Агар нашр шуда бошад ва таҳрир карда шавад — дубора pending
        if ($course->status === Course::STATUS_PUBLISHED) {
            $data['status'] = Course::STATUS_PENDING;
        }

        $course->update($data);

        return redirect()
            ->route('author.courses.show', $course)
            ->with('success', 'Курс бо муваффақият навсозӣ шуд!');
    }

    /**
     * Нест кардани курс.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $this->authorizeOwner($course);
        $course->delete();

        return redirect()
            ->route('author.courses.index')
            ->with('success', 'Курс нест карда шуд.');
    }

    /**
     * Санҷиши моликияти курс.
     */
    private function authorizeOwner(Course $course): void
    {
        if ($course->author_id !== auth()->id()) {
            abort(403, 'Шумо ба ин курс дастрасӣ надоред.');
        }
    }
}
