<?php

declare(strict_types=1);

namespace Src\Repositories;

use PDO;

/**
 * Репозиторияи корбарон ва муаллифони курсҳо (PDO).
 */
final class UserRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Ҳамаи муаллифон (role = author).
     *
     * @return list<array{id:int, name:string, email:string, role:string}>
     */
    public function authors(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, name, email, role FROM users WHERE role = :role ORDER BY name ASC, id ASC'
        );
        $stmt->execute([':role' => 'author']);

        return $this->mapUsers($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Корбароне, ки метавонанд муаллифи/соҳиби курс бошанд (author ва admin).
     *
     * @return list<array{id:int, name:string, email:string, role:string}>
     */
    public function courseManagers(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id, name, email, role FROM users WHERE role IN ('author', 'admin') ORDER BY name ASC, id ASC"
        );

        return $this->mapUsers($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Муаллифоне, ки ҳадди ақал як курси нашршуда доранд (барои филтри каталоги оммавӣ).
     *
     * @return list<array{id:int, name:string, email:string, role:string}>
     */
    public function publishedAuthors(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT u.id, u.name, u.email, u.role
             FROM users u
             INNER JOIN courses c ON c.author_id = u.id
             WHERE u.role = :role AND c.status = :status
             ORDER BY u.name ASC, u.id ASC'
        );
        $stmt->execute([
            ':role'   => 'author',
            ':status' => 'published',
        ]);

        return $this->mapUsers($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Ёфтани корбар бо ID.
     *
     * @return array{id:int, name:string, email:string, role:string}|null
     */
    public function find(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT id, name, email, role FROM users WHERE id = :id LIMIT 1'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return [
            'id'    => (int) $row['id'],
            'name'  => (string) $row['name'],
            'email' => (string) $row['email'],
            'role'  => (string) $row['role'],
        ];
    }

    /**
     * Санҷидани мавҷудияти корбар дар база.
     */
    public function exists(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        $stmt = $this->pdo->prepare('SELECT 1 FROM users WHERE id = :id LIMIT 1');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    /**
     * Санҷидани он ки корбар метавонад соҳиби курс бошад (author ё admin).
     */
    public function canOwnCourse(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM users WHERE id = :id AND role IN ('author', 'admin') LIMIT 1"
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     * @return list<array{id:int, name:string, email:string, role:string}>
     */
    private function mapUsers(array $rows): array
    {
        return array_map(static fn (array $row): array => [
            'id'    => (int) $row['id'],
            'name'  => (string) $row['name'],
            'email' => (string) $row['email'],
            'role'  => (string) $row['role'],
        ], $rows);
    }
}
