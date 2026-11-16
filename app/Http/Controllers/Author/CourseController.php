<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Support\ModelMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
        $validated = $request->validated();
        $level = $validated['level'] ?? null;
        $requestedStatus = $validated['status'] ?? Course::STATUS_PENDING;

        $course = Course::create([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'author_id'   => auth()->id(),
            'status'      => $requestedStatus === Course::STATUS_DRAFT ? Course::STATUS_DRAFT : Course::STATUS_PENDING,
        ]);

        if ($level) {
            ModelMeta::set('course', $course->id, 'level', $level);
        }

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers/' . $course->id, 'local');
            ModelMeta::set('course', $course->id, 'cover_image', $coverPath);
        }

        $msg = $course->status === Course::STATUS_DRAFT
            ? 'Курс ҳамчун лоиҳа (Draft) захира карда шуд.'
            : 'Курс бо муваффақият сохта шуд ва барои тасдиқ фиристода шуд!';

        return redirect()
            ->route('author.courses.index')
            ->with('success', $msg);
    }

    /**
     * Нишон додани курси мушаххас, сохтор ва омори пешрафти донишҷӯён (FR-11).
     */
    public function show(Course $course): View
    {
        $this->authorizeOwner($course);

        $course->load([
            'modules.lessons.assignment.submissions.student',
            'modules.lessons.materials',
            'category',
            'enrollments.user',
            'enrollments.certificate',
        ]);

        $submissions = collect();
        foreach ($course->modules as $module) {
            foreach ($module->lessons as $lesson) {
                if ($lesson->assignment) {
                    $subs = $lesson->assignment->submissions
                        ->whereIn('status', ['submitted', 'pending']);
                    $submissions = $submissions->merge($subs);
                }
            }
        }

        $enrollments = $course->enrollments->map(function ($enrollment) use ($course) {
            if ($enrollment->user) {
                $enrollment->progress = $enrollment->user->progressInCourse($course);
            } else {
                $enrollment->progress = 0;
            }

            return $enrollment;
        });

        return view('author.courses.show', compact('course', 'submissions', 'enrollments'));
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

        $validated = $request->validated();
        $updateData = [
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
        ];

        // Агар нашр шуда бошад ва таҳрир карда шавад — дубора pending
        if ($course->status === Course::STATUS_PUBLISHED) {
            $updateData['status'] = Course::STATUS_PENDING;
        } elseif (!empty($validated['status'])) {
            $updateData['status'] = $validated['status'];
        }

        $course->update($updateData);

        if (!empty($validated['level'])) {
            ModelMeta::set('course', $course->id, 'level', $validated['level']);
        }

        if ($request->hasFile('cover_image')) {
            $oldCover = $course->cover_image;
            $coverPath = $request->file('cover_image')->store('covers/' . $course->id, 'local');
            if ($oldCover) {
                Storage::disk('local')->delete($oldCover);
                Storage::disk('public')->delete($oldCover);
            }
            ModelMeta::set('course', $course->id, 'cover_image', $coverPath);
        }

        return redirect()
            ->route('author.courses.show', $course)
            ->with('success', 'Курс бо муваффақият навсозӣ шуд!');
    }

    /**
     * Ирсоли курси draft ё rejected ба модератсия (pending).
     */
    public function submitForReview(Course $course): RedirectResponse
    {
        $this->authorizeOwner($course);

        if ($course->status === Course::STATUS_PUBLISHED) {
            return back()->with('info', 'Ин курс аллакай нашр шудааст.');
        }

        $course->update(['status' => Course::STATUS_PENDING]);
        ModelMeta::set('course', $course->id, 'rejection_reason', null);

        return back()->with('success', 'Курс барои санҷиш ва модератсия фиристода шуд!');
    }

    /**
     * Нест кардани курс.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $this->authorizeOwner($course);

        if ($course->enrollments()->exists()) {
            return back()->with('error', 'Курсе, ки донишҷӯёни номнависшуда дорад, нест карда намешавад.');
        }

        try {
            $course->delete();
        } catch (\Throwable $e) {
            return back()->with('error', 'Нест кардани курс имконнопазир аст, зеро сабтҳои вобаста мавҷуданд.');
        }

        return redirect()
            ->route('author.courses.index')
            ->with('success', 'Курс нест карда шуд.');
    }

    private function authorizeOwner(Course $course): void
    {
        if ($course->author_id !== auth()->id()) {
            abort(403, 'Шумо ба ин курс дастрасӣ надоред.');
        }
    }
}
