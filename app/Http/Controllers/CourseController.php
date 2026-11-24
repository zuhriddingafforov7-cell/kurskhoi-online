<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use App\Support\CoursePresenter;
use App\Support\ModelMeta;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Src\Repositories\CategoryRepository;
use Src\Repositories\CourseRepository;
use Src\Repositories\UserRepository;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseRepository $courses,
        private readonly CategoryRepository $categories,
        private readonly UserRepository $users,
    ) {
    }

    /**
     * Саҳифаи каталоги оммавии курсҳо бо ҷустуҷӯ, филтр, пагинация ва JOIN тавассути PDO Repository.
     */
    public function index(Request $request): View
    {
        $page = CoursePresenter::parsePage($request);
        $perPage = 10;

        $categoryId = CoursePresenter::parsePositiveInt($request->query('category', $request->query('category_id')));
        $authorId = CoursePresenter::parsePositiveInt($request->query('author', $request->query('author_id')));
        $search = trim((string) ($request->query('search', $request->query('q', ''))));
        $sort = (string) $request->query('sort', 'latest');
        $level = (string) $request->query('level', '');

        $filters = [
            'status'      => Course::STATUS_PUBLISHED,
            'category_id' => $categoryId,
            'author_id'   => $authorId,
            'q'           => $search,
        ];

        if (in_array($level, ['beginner', 'intermediate', 'advanced'], true)) {
            $allMatching = array_values(array_filter(
                $this->courses->all($filters, $sort),
                static fn (array $row): bool => ModelMeta::inferCourseLevel((string) $row['title'], (int) $row['id']) === $level
            ));

            $total = count($allMatching);
            $pages = max(1, (int) ceil($total / $perPage));
            $clampedPage = max(1, min($page, $pages));
            $slice = array_slice($allMatching, ($clampedPage - 1) * $perPage, $perPage);
            $hydrated = array_map([CoursePresenter::class, 'hydrateCourse'], $slice);

            $courses = CoursePresenter::paginator($hydrated, $total, $perPage, $clampedPage, $request);
        } else {
            $result = $this->courses->paginate($filters, $page, $perPage, $sort);
            $hydrated = array_map([CoursePresenter::class, 'hydrateCourse'], $result['items']);
            $courses = CoursePresenter::paginator($hydrated, $result['total'], $result['per_page'], $result['page'], $request);
        }

        $categories = CoursePresenter::hydrateCategoryList($this->categories->allWithPublishedCount());
        $authors = CoursePresenter::hydrateUserList($this->users->publishedAuthors());

        return view('courses.index', compact('courses', 'categories', 'authors'));
    }

    /**
     * Муфассали курс барои омма / меҳмонон бо маълумоти вобаста (JOIN) аз Repository.
     */
    public function show(string $course): View
    {
        $courseId = CoursePresenter::parsePositiveInt($course);
        if ($courseId === null) {
            abort(404);
        }

        $courseRow = $this->courses->find($courseId);
        if ($courseRow === null) {
            abort(404);
        }

        if (
            $courseRow['status'] !== Course::STATUS_PUBLISHED
            && (!auth()->check() || ((int) auth()->id() !== (int) $courseRow['author_id'] && !auth()->user()->isAdmin()))
        ) {
            abort(404);
        }

        $courseModel = CoursePresenter::hydrateCourse($courseRow);
        $courseModel->load(['modules.lessons']);

        $isEnrolled = false;
        $enrollment = null;

        if (auth()->check()) {
            $enrollment = auth()->user()->enrollments()->where('course_id', $courseId)->first();
            $isEnrolled = $enrollment !== null;
        }

        return view('courses.show', [
            'course'     => $courseModel,
            'courseRow'  => $courseRow,
            'isEnrolled' => $isEnrolled,
            'enrollment' => $enrollment,
        ]);
    }
}
