<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users'        => User::count(),
            'students'           => User::where('role', 'student')->count(),
            'authors'            => User::where('role', 'author')->count(),
            'total_courses'      => Course::count(),
            'published_courses'  => Course::where('status', 'published')->count(),
            'pending_courses'    => Course::where('status', 'pending')->count(),
            'total_enrollments'  => Enrollment::count(),
            'total_categories'   => Category::count(),
        ];

        $recentCourses = Course::with(['author', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(5)->get();

        $pendingCourses = Course::with('author')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentCourses', 'recentUsers', 'pendingCourses'));
    }
}
