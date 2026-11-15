<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $enrollments = $user->enrollments()
            ->with(['course.modules.lessons', 'course.author', 'certificate'])
            ->latest()
            ->get();

        $enrollments->each(function ($enrollment) use ($user) {
            $enrollment->progress = $user->progressInCourse($enrollment->course);
        });

        $completedCount = $enrollments->where('status', 'completed')->count();
        $activeCount    = $enrollments->where('status', 'active')->count();
        $certCount      = $enrollments->filter(fn ($e) => $e->certificate !== null)->count();

        return view('student.dashboard', compact(
            'enrollments',
            'completedCount',
            'activeCount',
            'certCount'
        ));
    }
}
