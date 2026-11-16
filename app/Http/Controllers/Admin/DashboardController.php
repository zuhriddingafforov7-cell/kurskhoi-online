<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            'rejected_courses'   => Course::where('status', 'rejected')->count(),
            'total_enrollments'  => Enrollment::count(),
            'total_categories'   => Category::count(),
            'total_certificates' => Certificate::count(),
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

    /**
     * Содироти рӯйхати хатмкунандагон бо сертификатҳо ба формати CSV (FR-12).
     */
    public function exportGraduatesCsv(): StreamedResponse
    {
        $certificates = Certificate::with(['enrollment.user', 'enrollment.course.author'])
            ->latest('issue_date')
            ->get();

        $filename = 'biyomuz-graduates-' . date('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($certificates) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM барои намоиши дурусти кириллӣ дар Excel
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Рамзи сертификат', 'Донишҷӯ', 'Email', 'Курс', 'Муаллиф', 'Санаи додан']);

            foreach ($certificates as $cert) {
                fputcsv($out, [
                    $cert->certificate_code,
                    $cert->enrollment?->user?->name ?? '—',
                    $cert->enrollment?->user?->email ?? '—',
                    $cert->enrollment?->course?->title ?? '—',
                    $cert->enrollment?->course?->author?->name ?? '—',
                    $cert->issued_at ? $cert->issued_at->format('Y-m-d') : '',
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
