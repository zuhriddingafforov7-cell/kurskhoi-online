<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $courses = $user->courses()
            ->withCount('enrollments')
            ->with('category')
            ->latest()
            ->get();

        $totalStudents = $courses->sum('enrollments_count');

        $pendingSubmissions = AssignmentSubmission::query()
            ->whereHas('assignment.lesson.module.course', fn ($q) => $q->where('author_id', $user->id))
            ->whereIn('status', ['submitted', 'pending'])
            ->count();

        return view('author.dashboard', compact('courses', 'totalStudents', 'pendingSubmissions'));
    }
}
