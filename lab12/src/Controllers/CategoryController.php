<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Контроллери категорияҳои таълимӣ бо додаҳои муваққатии мутобиқ ба schema.sql (`categories`).
 */
final class CategoryController extends Controller
{
    /**
     * Додаҳои муваққатии ҷадвали `categories` (`id`, `title`, `slug`, `description`, `created_at`, `updated_at`).
     *
     * @var array<int, array<string, mixed>>
     */
    public const DEMO_CATEGORIES = [
        1 => [
            'id'            => 1,
            'title'         => 'Барномасозӣ',
            'slug'          => 'programming',
            'description'   => 'Курсҳои амалии забонҳои PHP 8, Laravel, алгоритмҳо ва таҳияи барномаҳои веб.',
            'created_at'    => '2026-11-10 09:00:00',
            'updated_at'    => '2026-11-10 09:00:00',
            'courses_count' => 3,
        ],
        2 => [
            'id'            => 2,
            'title'         => 'Веб-дизайн',
            'slug'          => 'web-design',
            'description'   => 'Қолаббандии мутобиқшаванда бо HTML5, CSS3, Bootstrap 5 ва тарҳрезии UI/UX.',
            'created_at'    => '2026-11-10 09:05:00',
            'updated_at'    => '2026-11-10 09:05:00',
            'courses_count' => 2,
        ],
        3 => [
            'id'            => 3,
            'title'         => 'Пойгоҳи додаҳо',
            'slug'          => 'databases',
            'description'   => 'Меъмории релятсионӣ, нормализатсия, SQL, MariaDB ва оптимизатсияи дархостҳо.',
            'created_at'    => '2026-11-10 09:10:00',
            'updated_at'    => '2026-11-10 09:10:00',
            'courses_count' => 1,
        ],
        4 => [
            'id'            => 4,
            'title'         => 'Амнияти иттилоотӣ',
            'slug'          => 'cybersecurity',
            'description'   => 'Муҳофизати барномаҳои веб аз ҳамлаҳои XSS, CSRF, SQL Injection ва назорати дастрасӣ.',
            'created_at'    => '2026-11-10 09:15:00',
            'updated_at'    => '2026-11-10 09:15:00',
            'courses_count' => 1,
        ],
    ];

    /**
     * GET /categories — Намоиши рӯйхати категорияҳои курсҳо.
     */
    public function index(): void
    {
        $this->render('categories/index', [
            'title'      => 'Категорияҳои таълимӣ',
            'active'     => 'categories',
            'categories' => array_values(self::DEMO_CATEGORIES),
        ]);
    }
}
