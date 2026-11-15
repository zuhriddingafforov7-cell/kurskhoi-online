<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Каталоги курсҳои нашршуда.
     */
    public function index(Request $request): View
    {
        $query = Course::published()
            ->with(['author', 'category'])
            ->withCount('enrollments');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses    = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('title')->get();

        return view('student.courses.index', compact('courses', 'categories'));
    }

    /**
     * Нишон додани курси мушаххас.
     */
    public function show(Course $course): View
    {
        if ($course->status !== Course::STATUS_PUBLISHED) {
            abort(404);
        }

        $course->load(['author', 'category', 'modules.lessons']);
        $isEnrolled = auth()->check() && auth()->user()->isEnrolledIn($course->id);
        $enrollment = null;

        if ($isEnrolled) {
            $enrollment = auth()->user()->enrollments()->where('course_id', $course->id)->first();
        }

        return view('student.courses.show', compact('course', 'isEnrolled', 'enrollment'));
    }

    /**
     * Ёзнашинии талаба ба курс.
     */
    public function enroll(Course $course): RedirectResponse
    {
        if ($course->status !== Course::STATUS_PUBLISHED) {
            abort(404);
        }

        $user = auth()->user();

        if ($user->isEnrolledIn($course->id)) {
            return back()->with('info', 'Шумо аллакай ба ин курс ёзнашинӣ кардаед.');
        }

        Enrollment::create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
            'status'    => 'active',
        ]);

        return redirect()
            ->route('student.courses.show', $course)
            ->with('success', 'Шумо ба курс муваффақона ёзнашинӣ кардед!');
    }

    /**
     * Курсҳои ёзнашинишудаи талаба.
     */
    public function myCourses(): View
    {
        $user = auth()->user();

        $enrollments = $user->enrollments()
            ->with(['course.modules.lessons', 'course.author', 'certificate'])
            ->latest()
            ->get();

        // Ҳисоби пешрафт барои ҳар курс
        $enrollments->each(function ($enrollment) use ($user) {
            $course = $enrollment->course;
            $course->load('modules.lessons');
            $enrollment->progress = $user->progressInCourse($course);
        });

        return view('student.my-courses', compact('enrollments'));
    }
}
