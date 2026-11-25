<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Контроллери курсҳо бо додаҳои муваққатии мутобиқ ба schema.sql (`courses`, `modules`, `lessons`).
 */
final class CourseController extends Controller
{
    /**
     * Додаҳои муваққатии ҷадвали `courses` бо тахаллусҳои JOIN (мувофиқи schema.sql).
     *
     * @var array<int, array<string, mixed>>
     */
    public const DEMO_COURSES = [
        1 => [
            'id'                => 1,
            'title'             => 'Кор бо Laravel',
            'description'       => 'Таҳияи барномаҳои муосири веб бо истифодаи Laravel 13, Eloquent ORM, маршрутизатсия ва меъмории MVC барои лоиҳаҳои воқеӣ.',
            'author_id'         => 2,
            'category_id'       => 1,
            'status'            => 'published',
            'created_at'        => '2026-11-12 10:00:00',
            'updated_at'        => '2026-11-15 14:20:00',
            'author_name'       => 'Устод Аҳмад Раҳимов',
            'author_email'      => 'ahmad@biyomuz.tj',
            'category_title'    => 'Барномасозӣ',
            'modules_count'     => 2,
            'lessons_count'     => 18,
            'enrollments_count' => 24,
        ],
        2 => [
            'id'                => 2,
            'title'             => 'Омӯзиши забони PHP',
            'description'       => 'Курси мукаммали забони PHP 8.5 аз сифр: намудҳои сахт (strict_types), ООП, PSR-4, Composer ва сохтани роутери мустақил.',
            'author_id'         => 2,
            'category_id'       => 1,
            'status'            => 'published',
            'created_at'        => '2026-11-11 09:30:00',
            'updated_at'        => '2026-11-14 16:45:00',
            'author_name'       => 'Устод Аҳмад Раҳимов',
            'author_email'      => 'ahmad@biyomuz.tj',
            'category_title'    => 'Барномасозӣ',
            'modules_count'     => 2,
            'lessons_count'     => 16,
            'enrollments_count' => 31,
        ],
        3 => [
            'id'                => 3,
            'title'             => 'Асосҳои барномасозӣ',
            'description'       => 'Курси муқаддимавӣ барои навомӯзон оид ба мантиқи алгоритмҳо, сохтори додаҳо ва тафаккури муҳандисӣ бо машқҳои амалӣ.',
            'author_id'         => 2,
            'category_id'       => 1,
            'status'            => 'published',
            'created_at'        => '2026-11-10 11:15:00',
            'updated_at'        => '2026-11-12 11:15:00',
            'author_name'       => 'Устод Аҳмад Раҳимов',
            'author_email'      => 'ahmad@biyomuz.tj',
            'category_title'    => 'Барномасозӣ',
            'modules_count'     => 2,
            'lessons_count'     => 12,
            'enrollments_count' => 19,
        ],
        4 => [
            'id'                => 4,
            'title'             => 'Тарҳрезии вебсайт',
            'description'       => 'Асосҳои тарҳрезии графикӣ, дизайн-системаҳо, дастрасии WCAG 2.1 ва сохтани интерфейсҳои мутобиқшаванда дар Figma ва веб.',
            'author_id'         => 3,
            'category_id'       => 2,
            'status'            => 'published',
            'created_at'        => '2026-11-13 08:40:00',
            'updated_at'        => '2026-11-16 10:00:00',
            'author_name'       => 'Шаҳром Саидов',
            'author_email'      => 'shahrom@biyomuz.tj',
            'category_title'    => 'Веб-дизайн',
            'modules_count'     => 2,
            'lessons_count'     => 14,
            'enrollments_count' => 15,
        ],
        5 => [
            'id'                => 5,
            'title'             => 'Асосҳои HTML ва CSS',
            'description'       => 'Қолаббандии семантикии саҳифаҳои веб бо HTML5, Flexbox, CSS Grid ва Bootstrap 5 барои экранҳои мобилӣ ва компютер.',
            'author_id'         => 3,
            'category_id'       => 2,
            'status'            => 'published',
            'created_at'        => '2026-11-14 13:20:00',
            'updated_at'        => '2026-11-17 09:10:00',
            'author_name'       => 'Шаҳром Саидов',
            'author_email'      => 'shahrom@biyomuz.tj',
            'category_title'    => 'Веб-дизайн',
            'modules_count'     => 2,
            'lessons_count'     => 10,
            'enrollments_count' => 22,
        ],
        6 => [
            'id'                => 6,
            'title'             => 'Пойгоҳи додаҳои MySQL',
            'description'       => 'Тарҳрезии пойгоҳи додаҳо, нормализатсия то шакли сеюм (3NF), калидҳои хориҷӣ ва дархостҳои SQL дар MariaDB бо PDO.',
            'author_id'         => 4,
            'category_id'       => 3,
            'status'            => 'pending',
            'created_at'        => '2026-11-15 15:00:00',
            'updated_at'        => '2026-11-18 12:30:00',
            'author_name'       => 'Синои Ҳасан',
            'author_email'      => 'sino@biyomuz.tj',
            'category_title'    => 'Пойгоҳи додаҳо',
            'modules_count'     => 2,
            'lessons_count'     => 10,
            'enrollments_count' => 11,
        ],
    ];

    /**
     * Барномаи намунавии модулҳо ва дарсҳо мутобиқ ба ҷадвалҳои `modules` ва `lessons`.
     *
     * @var array<int, list<array<string, mixed>>>
     */
    private const DEMO_SYLLABUS = [
        1 => [
            [
                'id'          => 101,
                'course_id'   => 1,
                'title'       => 'Насб ва сохтори меъмории MVC',
                'description' => 'Шиносоӣ бо Composer, Front Controller ва масиргузории дархостҳо.',
                'order_index' => 1,
                'lessons'     => [
                    ['id' => 1001, 'module_id' => 101, 'title' => 'Муқаддима ба PHP 8 ва PSR-4', 'content_type' => 'video', 'order_index' => 1],
                    ['id' => 1002, 'module_id' => 101, 'title' => 'Масиргузорӣ (Routing) ва контроллерҳо', 'content_type' => 'text', 'order_index' => 2],
                    ['id' => 1003, 'module_id' => 101, 'title' => 'Шаблонҳои PHP ва ҳимоя аз XSS', 'content_type' => 'file', 'order_index' => 3],
                ],
            ],
            [
                'id'          => 102,
                'course_id'   => 1,
                'title'       => 'Пойгоҳи додаҳо ва амният',
                'description' => 'Кор бо Eloquent ORM, транзаксияҳо ва санҷиши воридот.',
                'order_index' => 2,
                'lessons'     => [
                    ['id' => 1004, 'module_id' => 102, 'title' => 'Мигратсияҳо ва калидҳои хориҷӣ', 'content_type' => 'text', 'order_index' => 1],
                    ['id' => 1005, 'module_id' => 102, 'title' => 'Лоиҳаи амалӣ: Платформаи омӯзишӣ', 'content_type' => 'video', 'order_index' => 2],
                ],
            ],
        ],
    ];

    /**
     * GET /courses — Каталоги курсҳо бо имкони ҷустуҷӯ ва филтр аз рӯйи Query String.
     */
    public function index(): void
    {
        $q = trim((string) ($_GET['q'] ?? ''));
        $categoryId = trim((string) ($_GET['category_id'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));

        $courses = array_values(array_filter(
            self::DEMO_COURSES,
            static function (array $course) use ($q, $categoryId, $status): bool {
                if ($categoryId !== '' && (int) $categoryId !== (int) $course['category_id']) {
                    return false;
                }

                if ($status !== '' && $status !== $course['status']) {
                    return false;
                }

                if ($q !== '') {
                    $haystack = $course['title'] . ' ' . $course['description'] . ' ' . $course['author_name'];
                    if (mb_stripos($haystack, $q, 0, 'UTF-8') === false) {
                        return false;
                    }
                }

                return true;
            }
        ));

        $this->render('courses/index', [
            'title'      => 'Каталоги курсҳо',
            'active'     => 'courses',
            'courses'    => $courses,
            'categories' => array_values(CategoryController::DEMO_CATEGORIES),
            'filters'    => [
                'q'           => $q,
                'category_id' => $categoryId,
                'status'      => $status,
            ],
        ]);
    }

    /**
     * GET /courses/{id} — Саҳифаи муфассали курс; ҳангоми набудани курс HTTP 404 медиҳад.
     */
    public function show(int $id): void
    {
        $course = self::DEMO_COURSES[$id] ?? null;
        if ($course === null) {
            $this->abort(404, "Курси рақами #{$id} дар система ёфт нашуд.");
        }

        $modules = self::DEMO_SYLLABUS[$id] ?? [
            [
                'id'          => $id * 100 + 1,
                'course_id'   => $id,
                'title'       => 'Модули 1: Асосҳои назариявӣ ва сохторӣ',
                'description' => 'Омӯзиши мафҳумҳои калидӣ ва омодасозии муҳити корӣ.',
                'order_index' => 1,
                'lessons'     => [
                    ['id' => $id * 1000 + 1, 'module_id' => $id * 100 + 1, 'title' => 'Дарси 1: Муқаддима ва ҳадафҳои курс', 'content_type' => 'video', 'order_index' => 1],
                    ['id' => $id * 1000 + 2, 'module_id' => $id * 100 + 1, 'title' => 'Дарси 2: Машқи аввалини амалӣ', 'content_type' => 'text', 'order_index' => 2],
                ],
            ],
        ];

        $this->render('courses/show', [
            'title'   => $course['title'],
            'active'  => 'courses',
            'course'  => $course,
            'modules' => $modules,
        ]);
    }
}
