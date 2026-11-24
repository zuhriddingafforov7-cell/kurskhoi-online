<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Support\CoursePresenter;
use App\Support\ModelMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Src\Repositories\CategoryRepository;
use Src\Repositories\CourseRepository;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseRepository $courses,
        private readonly CategoryRepository $categories,
    ) {
    }

    public function index(Request $request): View
    {
        $page = CoursePresenter::parsePage($request);
        $filters = [
            'status'      => trim((string) $request->query('status', '')),
            'category_id' => CoursePresenter::parsePositiveInt($request->query('category_id')),
            'q'           => trim((string) ($request->query('search', $request->query('q', '')))),
        ];

        $result = $this->courses->paginate($filters, $page, 10, 'latest');
        $hydrated = array_map([CoursePresenter::class, 'hydrateCourse'], $result['items']);
        $courses = CoursePresenter::paginator($hydrated, $result['total'], $result['per_page'], $result['page'], $request);
        $categories = CoursePresenter::hydrateCategoryList($this->categories->all());

        return view('admin.courses.index', compact('courses', 'categories'));
    }

    public function approve(Course $course): RedirectResponse
    {
        $this->courses->update((int) $course->id, ['status' => Course::STATUS_PUBLISHED]);
        ModelMeta::set('course', $course->id, 'rejection_reason', null);

        return back()->with('success', "«{$course->title}» нашр шуд!");
    }

    public function reject(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $reason = trim((string) ($data['rejection_reason'] ?? ''));
        if ($reason === '') {
            $reason = 'Бо қарори маъмурият рад карда шуд. Лутфан мазмун ва сохтори курсро такмил диҳед.';
        }

        $this->courses->update((int) $course->id, ['status' => Course::STATUS_REJECTED]);
        ModelMeta::set('course', $course->id, 'rejection_reason', $reason);

        return back()->with('success', "«{$course->title}» рад карда шуд.");
    }
}
