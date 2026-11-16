<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class ModelMeta
{
    /**
     * Анбори муваққатӣ дар хотира барои муҳити тестӣ.
     *
     * @var array<string, array<string, mixed>>
     */
    private static array $memoryStore = [];

    private static function metaFilePath(): string
    {
        return storage_path('app/private/model_meta.json');
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function loadAll(): array
    {
        if (app()->environment('testing')) {
            return self::$memoryStore;
        }

        $path = self::metaFilePath();
        if (!file_exists($path)) {
            return [];
        }

        $raw = @file_get_contents($path);
        if (!$raw) {
            return [];
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @param  array<string, array<string, mixed>>  $data
     */
    private static function saveAll(array $data): void
    {
        if (app()->environment('testing')) {
            self::$memoryStore = $data;

            return;
        }

        $path = self::metaFilePath();
        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        @file_put_contents($path, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
    }

    public static function flush(): void
    {
        self::$memoryStore = [];
    }

    private static function makeKey(string $type, int|string $id): string
    {
        return "{$type}:{$id}";
    }

    public static function get(string $type, int|string $id, string $key, mixed $default = null): mixed
    {
        if ($id === '' || $id === null) {
            return $default;
        }

        $all = self::loadAll();
        $bucket = $all[self::makeKey($type, $id)] ?? [];

        return array_key_exists($key, $bucket) ? $bucket[$key] : $default;
    }

    public static function set(string $type, int|string $id, string $key, mixed $value): void
    {
        if ($id === '' || $id === null) {
            return;
        }

        $all = self::loadAll();
        $bucketKey = self::makeKey($type, $id);

        if (!isset($all[$bucketKey]) || !is_array($all[$bucketKey])) {
            $all[$bucketKey] = [];
        }

        if ($value === null) {
            unset($all[$bucketKey][$key]);
        } else {
            $all[$bucketKey][$key] = $value;
        }

        self::saveAll($all);
    }

    /**
     * @return array<string, mixed>
     */
    public static function getAll(string $type, int|string $id): array
    {
        if ($id === '' || $id === null) {
            return [];
        }

        $all = self::loadAll();

        return $all[self::makeKey($type, $id)] ?? [];
    }

    /**
     * Муайян кардани сатҳи курс (beginner / intermediate / advanced).
     */
    public static function inferCourseLevel(string $title, ?int $id = null): string
    {
        if ($id !== null) {
            $explicit = self::get('course', $id, 'level');
            if (in_array($explicit, ['beginner', 'intermediate', 'advanced'], true)) {
                return $explicit;
            }
        }

        $lower = mb_strtolower($title);

        if (preg_match('/(объектгаро|амният|касбӣ|пешрафта|меъморӣ|advanced|security|oop)/u', $lower)) {
            return 'advanced';
        }

        if (preg_match('/(laravel|javascript|mysql|пойгоҳи дода|кор бо|intermediate|backend|api)/u', $lower)) {
            return 'intermediate';
        }

        if (preg_match('/(асосҳо|муқаддима|html|css|навомӯз|ибтидоӣ|beginner|python|php)/u', $lower)) {
            return 'beginner';
        }

        return 'beginner';
    }

    public static function levelLabel(string $level): string
    {
        return match ($level) {
            'advanced'     => __('Пешрафта'),
            'intermediate' => __('Миёна'),
            default        => __('Ибтидоӣ'),
        };
    }
}
