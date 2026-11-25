<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Контроллери саҳифаи асосии платформаи Biyomuz Academy.
 */
final class HomeController extends Controller
{
    /**
     * GET / — Намоиши саҳифаи асосӣ бо омори курсҳо, категорияҳо ва курсҳои машҳур.
     */
    public function index(): void
    {
        $courses = array_values(CourseController::DEMO_COURSES);
        $categories = array_values(CategoryController::DEMO_CATEGORIES);

        $publishedCourses = array_values(array_filter(
            $courses,
            static fn (array $course): bool => $course['status'] === 'published'
        ));

        $authors = array_unique(array_column($courses, 'author_id'));
        $modulesTotal = array_sum(array_map(
            static fn (array $course): int => (int) $course['modules_count'],
            $courses
        ));

        $this->render('dashboard/index', [
            'title'      => 'Саҳифаи асосӣ',
            'active'     => 'dashboard',
            'stats'      => [
                'courses_count'    => count($publishedCourses),
                'categories_count' => count($categories),
                'modules_count'    => $modulesTotal,
                'authors_count'    => count($authors),
            ],
            'categories' => $categories,
            'courses'    => array_slice($publishedCourses, 0, 4),
        ]);
    }
}
