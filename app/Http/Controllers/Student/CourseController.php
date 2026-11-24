<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Support\ModelMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Src\Repositories\EnrollmentRepository;

class CourseController extends Controller
{
    public function __construct(private readonly EnrollmentRepository $enrollments)
    {
    }
    /**
     * Каталоги курсҳои нашршуда.
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

        return view('student.courses.index', compact('courses', 'categories', 'authors'));
    }

    /**
     * Нишон додани курси мушаххас.
     */
    public function show(Course $course): View
    {
        if ($course->status !== Course::STATUS_PUBLISHED) {
            abort(404);
        }

        $course->load(['author', 'category', 'modules.lessons'])->loadCount('enrollments');
        $isEnrolled = auth()->check() && auth()->user()->isEnrolledIn($course->id);
        $enrollment = null;

        if ($isEnrolled) {
            $enrollment = auth()->user()->enrollments()->where('course_id', $course->id)->first();
        }

        return view('student.courses.show', compact('course', 'isEnrolled', 'enrollment'));
    }

    /**
     * Номнависии донишҷӯ ба курс ва сохтани пешрафти аввалия дар як транзаксияи PDO.
     */
    public function enroll(Course $course): RedirectResponse
    {
        if ($course->status !== Course::STATUS_PUBLISHED) {
            abort(404);
        }

        $user = auth()->user();

        try {
            $result = $this->enrollments->enrollStudentWithProgress((int) $user->id, (int) $course->id);
        } catch (\Throwable $e) {
            Log::error('Хатогӣ дар транзаксияи номнависии донишҷӯ ба курс.', [
                'user_id'   => $user->id,
                'course_id' => $course->id,
                'error'     => $e->getMessage(),
            ]);

            return back()->with('error', 'Ҳангоми номнависӣ ба курс хатогӣ рух дод. Тағйирот бекор карда шуданд.');
        }

        if ($result['already_enrolled']) {
            return back()->with('info', 'Шумо аллакай ба ин курс номнавис шудаед.');
        }

        return redirect()
            ->route('student.courses.show', $course)
            ->with('success', 'Шумо ба курс бомуваффақият номнавис шудед!');
    }

    /**
     * Курсҳои номнависшудаи донишҷӯ.
     */
    public function myCourses(): View
    {
        $user = auth()->user();

        $enrollments = $user->enrollments()
            ->with(['course.modules.lessons', 'course.author', 'certificate'])
            ->latest()
            ->get();

        $enrollments->each(function ($enrollment) use ($user) {
            $enrollment->progress = $user->progressInCourse($enrollment->course);
        });

        return view('student.my-courses', compact('enrollments'));
    }
}
