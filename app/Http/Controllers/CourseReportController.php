<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;
use Src\Repositories\CourseReportRepository;

/**
 * Контроллери ҳисоботҳои таҳлилии курсҳо бо GROUP BY (PDO).
 */
class CourseReportController extends Controller
{
    public function __construct(private readonly CourseReportRepository $reports)
    {
    }

    /**
     * Ҳисобот: Шумораи курсҳо аз рӯйи категорияҳо.
     */
    public function index(): View
    {
        $rows = $this->reports->coursesByCategory();
        $totals = $this->reports->summaryTotals();

        return view('reports.courses', compact('rows', 'totals'));
    }
}
