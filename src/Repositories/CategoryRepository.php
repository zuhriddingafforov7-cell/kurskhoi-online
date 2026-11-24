<?php

declare(strict_types=1);

namespace Src\Repositories;

use PDO;

/**
 * Репозиторияи категорияҳои курсҳо (PDO).
 */
final class CategoryRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Ҳамаи категорияҳо бо тартиби алифбо.
     *
     * @return list<array{id:int, title:string, slug:string, description:?string}>
     */
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, title, slug, description FROM categories ORDER BY title ASC, id ASC'
        );

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static fn (array $row): array => [
            'id'          => (int) $row['id'],
            'title'       => (string) $row['title'],
            'slug'        => (string) $row['slug'],
            'description' => $row['description'] !== null ? (string) $row['description'] : null,
        ], $rows);
    }

    /**
     * Ҳамаи категорияҳо бо шумораи курсҳои нашршуда (LEFT JOIN + GROUP BY).
     *
     * @return list<array{id:int, title:string, slug:string, description:?string, courses_count:int}>
     */
    public function allWithPublishedCount(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT cat.id, cat.title, cat.slug, cat.description, COUNT(c.id) AS courses_count
             FROM categories cat
             LEFT JOIN courses c ON c.category_id = cat.id AND c.status = :status
             GROUP BY cat.id, cat.title, cat.slug, cat.description
             ORDER BY cat.title ASC, cat.id ASC'
        );
        $stmt->execute([':status' => 'published']);

        return array_map(static fn (array $row): array => [
            'id'            => (int) $row['id'],
            'title'         => (string) $row['title'],
            'slug'          => (string) $row['slug'],
            'description'   => $row['description'] !== null ? (string) $row['description'] : null,
            'courses_count' => (int) $row['courses_count'],
        ], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Ёфтани як категория бо ID.
     *
     * @return array{id:int, title:string, slug:string, description:?string}|null
     */
    public function find(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT id, title, slug, description FROM categories WHERE id = :id LIMIT 1'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return [
            'id'          => (int) $row['id'],
            'title'       => (string) $row['title'],
            'slug'        => (string) $row['slug'],
            'description' => $row['description'] !== null ? (string) $row['description'] : null,
        ];
    }

    /**
     * Санҷидани мавҷудияти категория дар пойгоҳи дода.
     */
    public function exists(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        $stmt = $this->pdo->prepare('SELECT 1 FROM categories WHERE id = :id LIMIT 1');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    /**
     * Рӯйхати ID-ҳои иҷозатдодашудаи категорияҳо.
     *
     * @return list<int>
     */
    public function ids(): array
    {
        $stmt = $this->pdo->query('SELECT id FROM categories ORDER BY id ASC');

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }
}
