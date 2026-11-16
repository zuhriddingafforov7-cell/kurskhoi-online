<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use App\Support\ModelMeta;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Саҳифаи каталоги оммавии курсҳо бо ҷустуҷӯ, филтр ва мураттабсозӣ.
     */
    public function index(Request $request): View
    {
        $query = Course::published()
            ->with(['author', 'category'])
            ->withCount(['enrollments', 'modules']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('author')) {
            $query->where('author_id', $request->author);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'latest');
        if ($sort === 'popular') {
            $query->orderByDesc('enrollments_count')->latest();
        } elseif ($sort === 'title') {
            $query->orderBy('title');
        } else {
            $query->latest();
        }

        $level = $request->input('level');
        if (in_array($level, ['beginner', 'intermediate', 'advanced'], true)) {
            $filtered = $query->get()->filter(
                fn (Course $course) => ModelMeta::inferCourseLevel($course->title ?? '', $course->id) === $level
            )->values();

            $perPage = 12;
            $page = LengthAwarePaginator::resolveCurrentPage();
            $courses = new LengthAwarePaginator(
                $filtered->forPage($page, $perPage)->values(),
                $filtered->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $courses = $query->paginate(12)->withQueryString();
        }

        $categories = Category::withCount(['courses' => fn ($q) => $q->where('status', 'published')])
            ->orderBy('title')
            ->get();

        $authors = User::where('role', 'author')
            ->whereHas('courses', fn ($q) => $q->where('status', Course::STATUS_PUBLISHED))
            ->orderBy('name')
            ->get();

        return view('courses.index', compact('courses', 'categories', 'authors'));
    }

    /**
     * Муфассали курс барои омма / меҳмонон.
     */
    public function show(Course $course): View
    {
        if ($course->status !== Course::STATUS_PUBLISHED && (!auth()->check() || (auth()->id() !== $course->author_id && !auth()->user()->isAdmin()))) {
            abort(404);
        }

        $course->load(['author', 'category', 'modules.lessons'])->loadCount('enrollments');
        $isEnrolled = false;
        $enrollment = null;

        if (auth()->check()) {
            $enrollment = auth()->user()->enrollments()->where('course_id', $course->id)->first();
            $isEnrolled = $enrollment !== null;
        }

        return view('courses.show', compact('course', 'isEnrolled', 'enrollment'));
    }
}
