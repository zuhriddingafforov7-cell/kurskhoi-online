<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Support\ModelMeta;
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        $courses = $query->latest()->paginate(15)->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function approve(Course $course): RedirectResponse
    {
        $course->update(['status' => Course::STATUS_PUBLISHED]);
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

        $course->update(['status' => Course::STATUS_REJECTED]);
        ModelMeta::set('course', $course->id, 'rejection_reason', $reason);

        return back()->with('success', "«{$course->title}» рад карда шуд.");
    }
}
