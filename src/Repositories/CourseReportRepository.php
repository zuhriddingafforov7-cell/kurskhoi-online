<?php

declare(strict_types=1);

namespace Src\Repositories;

use PDO;

/**
 * Репозиторияи ҳисоботҳои таҳлилии курсҳо бо GROUP BY (PDO).
 */
final class CourseReportRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Ҳисобот: шумораи курсҳо ва номнависиҳо аз рӯйи категорияҳо (LEFT JOIN + GROUP BY).
     *
     * @return list<array{
     *     category_id:int,
     *     category_title:string,
     *     category_slug:string,
     *     total_courses:int,
     *     published_courses:int,
     *     pending_courses:int,
     *     draft_courses:int,
     *     rejected_courses:int,
     *     total_enrollments:int
     * }>
     */
    public function coursesByCategory(): array
    {
        $sql = "
            SELECT
                cat.id AS category_id,
                cat.title AS category_title,
                cat.slug AS category_slug,
                COUNT(c.id) AS total_courses,
                COALESCE(SUM(CASE WHEN c.status = 'published' THEN 1 ELSE 0 END), 0) AS published_courses,
                COALESCE(SUM(CASE WHEN c.status = 'pending' THEN 1 ELSE 0 END), 0) AS pending_courses,
                COALESCE(SUM(CASE WHEN c.status = 'draft' THEN 1 ELSE 0 END), 0) AS draft_courses,
                COALESCE(SUM(CASE WHEN c.status = 'rejected' THEN 1 ELSE 0 END), 0) AS rejected_courses,
                COALESCE((
                    SELECT COUNT(*)
                    FROM enrollments e
                    INNER JOIN courses c2 ON c2.id = e.course_id
                    WHERE c2.category_id = cat.id
                ), 0) AS total_enrollments
            FROM categories cat
            LEFT JOIN courses c ON c.category_id = cat.id
            GROUP BY cat.id, cat.title, cat.slug
            ORDER BY total_courses DESC, cat.title ASC
        ";

        $rows = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static fn (array $row): array => [
            'category_id'       => (int) $row['category_id'],
            'category_title'    => (string) $row['category_title'],
            'category_slug'     => (string) $row['category_slug'],
            'total_courses'     => (int) $row['total_courses'],
            'published_courses' => (int) $row['published_courses'],
            'pending_courses'   => (int) $row['pending_courses'],
            'draft_courses'     => (int) $row['draft_courses'],
            'rejected_courses'  => (int) $row['rejected_courses'],
            'total_enrollments' => (int) $row['total_enrollments'],
        ], $rows);
    }

    /**
     * Ҷамъбасти умумии омори курсҳо, категорияҳо ва номнависиҳо.
     *
     * @return array{
     *     total_categories:int,
     *     total_courses:int,
     *     published_courses:int,
     *     pending_courses:int,
     *     draft_courses:int,
     *     rejected_courses:int,
     *     total_enrollments:int,
     *     uncategorized_courses:int
     * }
     */
    public function summaryTotals(): array
    {
        $sql = "
            SELECT
                (SELECT COUNT(*) FROM categories) AS total_categories,
                COUNT(*) AS total_courses,
                COALESCE(SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END), 0) AS published_courses,
                COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) AS pending_courses,
                COALESCE(SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END), 0) AS draft_courses,
                COALESCE(SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END), 0) AS rejected_courses,
                COALESCE(SUM(CASE WHEN category_id IS NULL THEN 1 ELSE 0 END), 0) AS uncategorized_courses,
                (SELECT COUNT(*) FROM enrollments) AS total_enrollments
            FROM courses
        ";

        $row = $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'total_categories'      => (int) ($row['total_categories'] ?? 0),
            'total_courses'         => (int) ($row['total_courses'] ?? 0),
            'published_courses'     => (int) ($row['published_courses'] ?? 0),
            'pending_courses'       => (int) ($row['pending_courses'] ?? 0),
            'draft_courses'         => (int) ($row['draft_courses'] ?? 0),
            'rejected_courses'      => (int) ($row['rejected_courses'] ?? 0),
            'total_enrollments'     => (int) ($row['total_enrollments'] ?? 0),
            'uncategorized_courses' => (int) ($row['uncategorized_courses'] ?? 0),
        ];
    }
}
