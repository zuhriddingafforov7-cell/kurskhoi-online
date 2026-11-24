<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Src\Core\Validator;
use Src\Repositories\CategoryRepository;
use Src\Repositories\CourseRepository;
use Src\Repositories\UserRepository;

/**
 * Ёрдамчии пешниҳод, пагинация, гидратацияи моделҳо ва санҷиши шакли курс.
 * Ҳеҷ гуна дархости SQL намесозад — танҳо тавассути Repository-ҳо кор мекунад.
 */
final class CoursePresenter
{
    /**
     * Табдили амни рақами саҳифа аз GET-параметр (`abc`, `-1`, `0` -> 1).
     */
    public static function parsePage(Request $request): int
    {
        $raw = $request->query('page', 1);
        if (!is_scalar($raw) || !preg_match('/^-?\d+$/', trim((string) $raw))) {
            return 1;
        }

        return max(1, (int) $raw);
    }

    /**
     * Табдили амни идентификатор ба адади бутуни мусбат.
     */
    public static function parsePositiveInt(mixed $value): ?int
    {
        if (!is_scalar($value)) {
            return null;
        }

        $trimmed = trim((string) $value);
        if ($trimmed === '' || !preg_match('/^\d+$/', $trimmed)) {
            return null;
        }

        $int = (int) $trimmed;

        return $int > 0 ? $int : null;
    }

    /**
     * Сохтани объекти LengthAwarePaginator аз натиҷаи CourseRepository::paginate().
     *
     * @param  array<int, mixed>  $items
     */
    public static function paginator(
        array $items,
        int $total,
        int $perPage,
        int $currentPage,
        Request $request
    ): LengthAwarePaginator {
        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]
        );
    }

    /**
     * Гидратацияи сатри массивии Repository ба модели `Course` бе дархости иловагии SQL.
     *
     * @param  array<string, mixed>  $row
     */
    public static function hydrateCourse(array $row): Course
    {
        $createdAt = !empty($row['created_at']) ? (string) $row['created_at'] : now()->toDateTimeString();
        $updatedAt = !empty($row['updated_at']) ? (string) $row['updated_at'] : $createdAt;

        /** @var Course $course */
        $course = (new Course())->newFromBuilder([
            'id'          => (int) $row['id'],
            'title'       => (string) $row['title'],
            'description' => (string) ($row['description'] ?? ''),
            'author_id'   => (int) $row['author_id'],
            'category_id' => $row['category_id'] !== null ? (int) $row['category_id'] : null,
            'status'      => (string) $row['status'],
            'created_at'  => $createdAt,
            'updated_at'  => $updatedAt,
        ]);

        $course->setAttribute('enrollments_count', (int) ($row['enrollments_count'] ?? 0));
        $course->setAttribute('modules_count', (int) ($row['modules_count'] ?? 0));

        if (!empty($row['category_id'])) {
            /** @var Category $category */
            $category = (new Category())->newFromBuilder([
                'id'    => (int) $row['category_id'],
                'title' => (string) ($row['category_title'] ?? ''),
                'slug'  => (string) ($row['category_slug'] ?? ''),
            ]);
            $course->setRelation('category', $category);
        } else {
            $course->setRelation('category', null);
        }

        /** @var User $author */
        $author = (new User())->newFromBuilder([
            'id'    => (int) $row['author_id'],
            'name'  => (string) ($row['author_name'] ?? 'Номаълум'),
            'email' => (string) ($row['author_email'] ?? ''),
            'role'  => 'author',
        ]);
        $course->setRelation('author', $author);

        return $course;
    }

    /**
     * Табдили сатрҳои категория ба объектҳои мувофиқ бо шаблонҳои Blade.
     *
     * @param  list<array<string, mixed>>  $rows
     * @return list<object>
     */
    public static function hydrateCategoryList(array $rows): array
    {
        return array_map(static fn (array $row): object => (object) [
            'id'            => (int) $row['id'],
            'title'         => (string) $row['title'],
            'name'          => (string) $row['title'],
            'slug'          => (string) ($row['slug'] ?? ''),
            'description'   => $row['description'] ?? null,
            'courses_count' => (int) ($row['courses_count'] ?? 0),
        ], $rows);
    }

    /**
     * Табдили сатрҳои корбарон ба объектҳо барои рӯйхатҳои интихоб.
     *
     * @param  list<array<string, mixed>>  $rows
     * @return list<object>
     */
    public static function hydrateUserList(array $rows): array
    {
        return array_map(static fn (array $row): object => (object) [
            'id'    => (int) $row['id'],
            'name'  => (string) $row['name'],
            'email' => (string) ($row['email'] ?? ''),
            'role'  => (string) ($row['role'] ?? 'author'),
        ], $rows);
    }

    /**
     * Санҷиши пурраи серверии маълумоти курс бо ёрии Validator ва Repository.
     *
     * @return array{
     *     errors:array<string, list<string>>,
     *     data:array{title:string, description:string, category_id:int, status:string, author_id:int},
     *     old:array{title:string, description:string, category_id:string, status:string, author_id:string}
     * }
     */
    public static function validateCourseForm(
        Request $request,
        CourseRepository $courses,
        CategoryRepository $categories,
        UserRepository $users,
        bool $isAdmin,
        int $currentUserId,
        ?int $ignoreCourseId = null,
        string $defaultStatus = Course::STATUS_PENDING,
        int $minDescriptionLength = 10
    ): array {
        $title = trim((string) $request->input('title', ''));
        $description = trim((string) $request->input('description', ''));
        $categoryRaw = trim((string) $request->input('category_id', ''));
        $statusRaw = trim((string) $request->input('status', ''));
        $status = $statusRaw !== '' ? $statusRaw : $defaultStatus;
        $authorRaw = trim((string) $request->input('author_id', ''));

        $allowedStatuses = $isAdmin
            ? CourseRepository::ALLOWED_STATUSES
            : [Course::STATUS_DRAFT, Course::STATUS_PENDING];

        $validator = (new Validator())
            ->required('title', $title, 'Номи курс ҳатмист.')
            ->minLength('title', $title, 3, 'Номи курс бояд ҳадди ақал 3 аломат дошта бошад.')
            ->maxLength('title', $title, 255, 'Номи курс набояд аз 255 аломат зиёд бошад.')
            ->required('description', $description, 'Тавсифи курс ҳатмист.')
            ->minLength('description', $description, $minDescriptionLength, "Тавсифи курс бояд ҳадди ақал {$minDescriptionLength} аломат дошта бошад.")
            ->maxLength('description', $description, 5000, 'Тавсифи курс набояд аз 5000 аломат зиёд бошад.')
            ->required('category_id', $categoryRaw, 'Интихоби категория ҳатмист.')
            ->positiveInt('category_id', $categoryRaw, 'Идентификатори категория нодуруст аст.')
            ->required('status', $status, 'Интихоби ҳолати курс ҳатмист.')
            ->in('status', $status, $allowedStatuses, 'Ҳолати интихобшудаи курс иҷозатдода нест.');

        // Қоидаи DB №1: санҷиши ягонагии номи курс дар пойгоҳи дода тавассути Repository
        if ($title !== '' && mb_strlen($title, 'UTF-8') >= 3 && mb_strlen($title, 'UTF-8') <= 255) {
            if ($courses->titleExists($title, $ignoreCourseId)) {
                $validator->addError('title', 'Курс бо чунин ном аллакай дар пойгоҳи дода мавҷуд аст.');
            }
        }

        // Қоидаи DB №2: санҷиши мавҷудияти категория дар пойгоҳи дода тавассути Repository
        $categoryId = self::parsePositiveInt($categoryRaw);
        if ($categoryId !== null && !$categories->exists($categoryId)) {
            $validator->addError('category_id', 'Категорияи интихобшуда дар пойгоҳи дода вуҷуд надорад.');
        }

        // Назорати author_id дар сервер (пешгирии ивазкунии соҳиби курс аз POST)
        $authorId = $currentUserId;
        if ($isAdmin && $authorRaw !== '') {
            $validator->positiveInt('author_id', $authorRaw, 'Идентификатори муаллиф нодуруст аст.');
            $parsedAuthorId = self::parsePositiveInt($authorRaw);
            if ($parsedAuthorId !== null) {
                if (!$users->canOwnCourse($parsedAuthorId)) {
                    $validator->addError('author_id', 'Муаллифи интихобшуда дар система ёфт нашуд ё ҳуқуқи эҷоди курс надорад.');
                } else {
                    $authorId = $parsedAuthorId;
                }
            }
        }

        return [
            'errors' => $validator->errors(),
            'data'   => [
                'title'       => $title,
                'description' => $description,
                'category_id' => $categoryId ?? 0,
                'status'      => $status,
                'author_id'   => $authorId,
            ],
            'old' => [
                'title'       => (string) $request->input('title', ''),
                'description' => (string) $request->input('description', ''),
                'category_id' => (string) $request->input('category_id', ''),
                'status'      => $status,
                'author_id'   => $isAdmin ? (string) ($request->input('author_id', (string) $currentUserId)) : (string) $currentUserId,
            ],
        ];
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            Course::STATUS_PUBLISHED => 'Нашршуда',
            Course::STATUS_PENDING   => 'Дар баррасӣ',
            Course::STATUS_DRAFT     => 'Лоиҳа',
            Course::STATUS_REJECTED  => 'Радшуда',
            default                  => 'Номаълум',
        };
    }
}
