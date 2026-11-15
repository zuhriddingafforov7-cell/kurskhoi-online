<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Саҳифаи каталоги оммавии курсҳо.
     */
    public function index(Request $request): View
    {
        $query = Course::published()
            ->with(['author', 'category'])
            ->withCount(['enrollments', 'modules']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $courses = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::withCount(['courses' => fn ($q) => $q->where('status', 'published')])->get();

        return view('courses.index', compact('courses', 'categories'));
    }

    /**
     * Муфассали курс барои омма / меҳмонон.
     */
    public function show(Course $course): View
    {
        if ($course->status !== Course::STATUS_PUBLISHED && (!auth()->check() || (auth()->id() !== $course->author_id && !auth()->user()->isAdmin()))) {
            abort(404);
        }

        $course->load(['author', 'category', 'modules.lessons']);
        $isEnrolled = false;
        $enrollment = null;

        if (auth()->check()) {
            $enrollment = auth()->user()->enrollments()->where('course_id', $course->id)->first();
            $isEnrolled = $enrollment !== null;
        }

        return view('courses.show', compact('course', 'isEnrolled', 'enrollment'));
    }
}
