<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::with(['author', 'category'])->withCount('enrollments');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $courses = $query->latest()->paginate(15);

        return view('admin.courses.index', compact('courses'));
    }

    public function approve(Course $course): RedirectResponse
    {
        $course->update(['status' => Course::STATUS_PUBLISHED]);

        return back()->with('success', "«{$course->title}» нашр шуд!");
    }

    public function reject(Course $course): RedirectResponse
    {
        $course->update(['status' => Course::STATUS_REJECTED]);

        return back()->with('success', "«{$course->title}» рад карда шуд.");
    }
}
