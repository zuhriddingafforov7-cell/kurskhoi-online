<?php

declare(strict_types=1);

namespace Src\Repositories;

use PDO;

/**
 * Репозиторияи курсҳо (PDO): CRUD, ҷустуҷӯ, филтрҳо, пагинация, JOIN ва санҷиши вобастагиҳо.
 */
final class CourseRepository
{
    /** @var list<string> */
    public const ALLOWED_STATUSES = ['draft', 'pending', 'published', 'rejected'];

    /** @var list<string> */
    private const FIELDS = ['title', 'description', 'author_id', 'category_id', 'status'];

    /** @var array<string, string> */
    private const SORT_WHITELIST = [
        'latest'  => 'c.created_at DESC, c.id DESC',
        'oldest'  => 'c.created_at ASC, c.id ASC',
        'title'   => 'c.title ASC, c.id ASC',
        'popular' => 'enrollments_count DESC, c.created_at DESC, c.id DESC',
    ];

    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Пагинацияи курсҳо бо ҷустуҷӯ, филтрҳо ва JOIN ба `users` ва `categories`.
     *
     * @param  array<string, mixed>  $filters
     * @return array{items:list<array<string,mixed>>, total:int, page:int, pages:int, per_page:int}
     */
    public function paginate(array $filters = [], int $page = 1, int $perPage = 10, string $sort = 'latest'): array
    {
        $perPage = max(1, min($perPage, 100));
        $orderBy = self::SORT_WHITELIST[$sort] ?? self::SORT_WHITELIST['latest'];

        [$whereSql, $params] = $this->buildWhere($filters);

        $countStmt = $this->pdo->prepare("SELECT COUNT(*) FROM courses c {$whereSql}");
        $this->bindParams($countStmt, $params);
        $countStmt->execute();
        $total = (int) $countStmt->fetchColumn();

        $pages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $pages));
        $offset = ($page - 1) * $perPage;

        $sql = $this->baseSelectSql() . " {$whereSql} ORDER BY {$orderBy} LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $this->bindParams($stmt, $params);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = array_map([$this, 'normalizeRow'], $stmt->fetchAll(PDO::FETCH_ASSOC));

        return [
            'items'    => $rows,
            'total'    => $total,
            'page'     => $page,
            'pages'    => $pages,
            'per_page' => $perPage,
        ];
    }

    /**
     * Гирифтани ҳамаи курсҳои мувофиқ (бе LIMIT/OFFSET — барои филтрҳои иловагӣ).
     *
     * @param  array<string, mixed>  $filters
     * @return list<array<string, mixed>>
     */
    public function all(array $filters = [], string $sort = 'latest'): array
    {
        $orderBy = self::SORT_WHITELIST[$sort] ?? self::SORT_WHITELIST['latest'];
        [$whereSql, $params] = $this->buildWhere($filters);

        $stmt = $this->pdo->prepare($this->baseSelectSql() . " {$whereSql} ORDER BY {$orderBy}");
        $this->bindParams($stmt, $params);
        $stmt->execute();

        return array_map([$this, 'normalizeRow'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Ёфтани як курс бо ID ва маълумоти вобаста (муаллиф ва категория).
     *
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $stmt = $this->pdo->prepare($this->baseSelectSql() . ' WHERE c.id = :id LIMIT 1');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->normalizeRow($row) : null;
    }

    /**
     * Илова кардани курси нав.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $now = date('Y-m-d H:i:s');
        $payload = $this->whitelistPayload($data);
        $payload['created_at'] = $now;
        $payload['updated_at'] = $now;

        $columns = array_keys($payload);
        $placeholders = array_map(static fn (string $col): string => ':' . $col, $columns);

        $sql = sprintf(
            'INSERT INTO courses (%s) VALUES (%s)',
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $stmt = $this->pdo->prepare($sql);
        foreach ($payload as $col => $val) {
            $stmt->bindValue(':' . $col, $val, $this->pdoType($val));
        }
        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Навсозии курси мавҷуда.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): bool
    {
        $payload = $this->whitelistPayload($data);
        if ($payload === []) {
            return false;
        }

        $payload['updated_at'] = date('Y-m-d H:i:s');

        $sets = [];
        foreach (array_keys($payload) as $col) {
            $sets[] = "{$col} = :{$col}";
        }

        $sql = 'UPDATE courses SET ' . implode(', ', $sets) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);

        foreach ($payload as $col => $val) {
            $stmt->bindValue(':' . $col, $val, $this->pdoType($val));
        }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Нест кардани курс аз пойгоҳи дода.
     */
    public function delete(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        $stmt = $this->pdo->prepare('DELETE FROM courses WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Санҷидани такрор нашудани номи курс (бо истиснои худи курс ҳангоми таҳрир).
     */
    public function titleExists(string $title, ?int $ignoreId = null): bool
    {
        $normalized = mb_strtolower(trim($title), 'UTF-8');
        if ($normalized === '') {
            return false;
        }

        $driver = (string) $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver === 'sqlite') {
            // Дар SQLite функсияи LOWER() танҳо барои ASCII кор мекунад, аз ин рӯ кириллро бо mb_strtolower муқоиса мекунем.
            $sql = 'SELECT id, title FROM courses';
            $params = [];
            if ($ignoreId !== null) {
                $sql .= ' WHERE id <> :ignore_id';
                $params[':ignore_id'] = $ignoreId;
            }
            $stmt = $this->pdo->prepare($sql);
            $this->bindParams($stmt, $params);
            $stmt->execute();

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                if (mb_strtolower(trim((string) $row['title']), 'UTF-8') === $normalized) {
                    return true;
                }
            }

            return false;
        }

        $sql = 'SELECT 1 FROM courses WHERE LOWER(TRIM(title)) = :title_lower';
        $params = [':title_lower' => $normalized];
        if ($ignoreId !== null) {
            $sql .= ' AND id <> :ignore_id';
            $params[':ignore_id'] = $ignoreId;
        }
        $sql .= ' LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $this->bindParams($stmt, $params);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    /**
     * Ҳисоби сабтҳои вобаста барои ҳифзи Foreign Key ҳангоми несткунӣ.
     *
     * @return array{enrollments:int, modules:int}
     */
    public function countDependencies(int $id): array
    {
        $enrStmt = $this->pdo->prepare('SELECT COUNT(*) FROM enrollments WHERE course_id = :id');
        $enrStmt->bindValue(':id', $id, PDO::PARAM_INT);
        $enrStmt->execute();

        $modStmt = $this->pdo->prepare('SELECT COUNT(*) FROM modules WHERE course_id = :id');
        $modStmt->bindValue(':id', $id, PDO::PARAM_INT);
        $modStmt->execute();

        return [
            'enrollments' => (int) $enrStmt->fetchColumn(),
            'modules'     => (int) $modStmt->fetchColumn(),
        ];
    }

    /**
     * Шумораи курсҳо аз рӯйи ҳолат (status).
     *
     * @return array{total:int, published:int, pending:int, draft:int, rejected:int}
     */
    public function countByStatus(?int $authorId = null): array
    {
        $where = '';
        $params = [];
        if ($authorId !== null && $authorId > 0) {
            $where = 'WHERE author_id = :author_id';
            $params[':author_id'] = $authorId;
        }

        $stmt = $this->pdo->prepare("SELECT status, COUNT(*) AS cnt FROM courses {$where} GROUP BY status");
        $this->bindParams($stmt, $params);
        $stmt->execute();

        $counts = [
            'total'     => 0,
            'published' => 0,
            'pending'   => 0,
            'draft'     => 0,
            'rejected'  => 0,
        ];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $status = (string) $row['status'];
            $cnt = (int) $row['cnt'];
            $counts['total'] += $cnt;
            if (array_key_exists($status, $counts)) {
                $counts[$status] = $cnt;
            }
        }

        return $counts;
    }

    /**
     * Сохтани қисми WHERE ва параметрҳои prepared statement барои ҷустуҷӯ ва филтрҳо.
     *
     * @param  array<string, mixed>  $filters
     * @return array{0:string, 1:array<string, mixed>}
     */
    private function buildWhere(array $filters): array
    {
        $clauses = [];
        $params = [];

        $q = trim((string) ($filters['q'] ?? ''));
        if ($q !== '') {
            $escaped = addcslashes($q, '%_\\');
            $pattern = '%' . $escaped . '%';
            $escapeSql = $this->likeEscapeClause();
            // Ду параметри алоҳида (:q_title ва :q_desc) барои мутобиқати пурра бо ATTR_EMULATE_PREPARES = false.
            $clauses[] = "(c.title LIKE :q_title {$escapeSql} OR c.description LIKE :q_desc {$escapeSql})";
            $params[':q_title'] = $pattern;
            $params[':q_desc'] = $pattern;
        }

        if (isset($filters['category_id']) && is_numeric($filters['category_id'])) {
            $catId = (int) $filters['category_id'];
            if ($catId > 0) {
                $clauses[] = 'c.category_id = :category_id';
                $params[':category_id'] = $catId;
            }
        }

        if (isset($filters['status']) && is_string($filters['status']) && $filters['status'] !== '') {
            if (in_array($filters['status'], self::ALLOWED_STATUSES, true)) {
                $clauses[] = 'c.status = :status';
                $params[':status'] = $filters['status'];
            }
        }

        if (isset($filters['author_id']) && is_numeric($filters['author_id'])) {
            $authorId = (int) $filters['author_id'];
            if ($authorId > 0) {
                $clauses[] = 'c.author_id = :author_id';
                $params[':author_id'] = $authorId;
            }
        }

        $whereSql = $clauses === [] ? '' : 'WHERE ' . implode(' AND ', $clauses);

        return [$whereSql, $params];
    }

    private function baseSelectSql(): string
    {
        return '
            SELECT
                c.id,
                c.title,
                c.description,
                c.author_id,
                c.category_id,
                c.status,
                c.created_at,
                c.updated_at,
                u.name AS author_name,
                u.email AS author_email,
                cat.title AS category_title,
                cat.slug AS category_slug,
                (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id) AS enrollments_count,
                (SELECT COUNT(*) FROM modules m WHERE m.course_id = c.id) AS modules_count
            FROM courses c
            INNER JOIN users u ON u.id = c.author_id
            LEFT JOIN categories cat ON cat.id = c.category_id
        ';
    }

    private function likeEscapeClause(): string
    {
        $driver = (string) $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        return $driver === 'sqlite' ? "ESCAPE '\\'" : "ESCAPE '\\\\'";
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function whitelistPayload(array $data): array
    {
        $payload = [];
        foreach (self::FIELDS as $field) {
            if (!array_key_exists($field, $data)) {
                continue;
            }

            $value = $data[$field];
            if ($field === 'author_id' || $field === 'category_id') {
                $payload[$field] = $value === null ? null : (int) $value;
            } elseif (is_string($value)) {
                $payload[$field] = trim($value);
            } else {
                $payload[$field] = $value;
            }
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $params
     */
    private function bindParams(\PDOStatement $stmt, array $params): void
    {
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, $this->pdoType($val));
        }
    }

    private function pdoType(mixed $value): int
    {
        return match (true) {
            is_int($value)  => PDO::PARAM_INT,
            $value === null => PDO::PARAM_NULL,
            is_bool($value) => PDO::PARAM_BOOL,
            default         => PDO::PARAM_STR,
        };
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    private function normalizeRow(array $row): array
    {
        return [
            'id'                => (int) $row['id'],
            'title'             => (string) $row['title'],
            'description'       => $row['description'] !== null ? (string) $row['description'] : '',
            'author_id'         => (int) $row['author_id'],
            'category_id'       => $row['category_id'] !== null ? (int) $row['category_id'] : null,
            'status'            => (string) $row['status'],
            'created_at'        => $row['created_at'] !== null ? (string) $row['created_at'] : null,
            'updated_at'        => $row['updated_at'] !== null ? (string) $row['updated_at'] : null,
            'author_name'       => (string) ($row['author_name'] ?? 'Номаълум'),
            'author_email'      => (string) ($row['author_email'] ?? ''),
            'category_title'    => $row['category_title'] !== null ? (string) $row['category_title'] : 'Бе категория',
            'category_slug'     => $row['category_slug'] !== null ? (string) $row['category_slug'] : '',
            'enrollments_count' => (int) ($row['enrollments_count'] ?? 0),
            'modules_count'     => (int) ($row['modules_count'] ?? 0),
        ];
    }
}
