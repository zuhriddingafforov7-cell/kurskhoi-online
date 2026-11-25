<?php

declare(strict_types=1);

namespace App\Core;

use DateTimeImmutable;
use RuntimeException;

/**
 * Муҳаррики шаблонҳои PHP бо буферкунии баромад, экранкунии XSS (e),
 * сохтани пайвандҳои мутобиқ (url), муҳофизати path traversal ва ёрирасонҳо.
 */
final class View
{
    /**
     * Тамғаҳои намоишии забони тоҷикӣ барои статусҳо ва нақшҳо.
     *
     * @var array<string, array<string, string>>
     */
    public const LABELS = [
        'course_status' => [
            'published' => 'Нашршуда',
            'pending'   => 'Мунтазир',
            'draft'     => 'Сиёҳнавис',
            'rejected'  => 'Радшуда',
        ],
        'user_role' => [
            'admin'   => 'Маъмур',
            'author'  => 'Муаллим',
            'student' => 'Донишҷӯ',
        ],
    ];

    private readonly string $viewsPath;

    public function __construct(string $viewsPath)
    {
        $real = realpath($viewsPath);
        $this->viewsPath = $real !== false ? $real : rtrim($viewsPath, '/\\');
    }

    /**
     * Тартиб додани шаблони саҳифа ва гузоштани натиҷа ба макети умумӣ.
     *
     * @param  array<string, mixed>  $data
     */
    public function render(string $template, array $data = [], ?string $layout = 'layout'): string
    {
        $content = $this->renderFile($template, $data);

        if ($layout === null) {
            return $content;
        }

        return $this->renderFile($layout, $data + ['content' => $content]);
    }

    /**
     * Хондан ва иҷро кардани як файли шаблон аз дохили ҷузвдони views/.
     *
     * @param  array<string, mixed>  $data
     */
    public function renderFile(string $template, array $data = []): string
    {
        $cleanTemplate = str_replace('\\', '/', trim($template));
        if (
            $cleanTemplate === ''
            || str_contains($cleanTemplate, "\0")
            || str_contains($cleanTemplate, '..')
            || str_starts_with($cleanTemplate, '/')
            || preg_match('/^[a-zA-Z]:/', $cleanTemplate) === 1
        ) {
            throw new RuntimeException("Роҳи нодурусти шаблон: {$template}");
        }

        $baseReal = realpath($this->viewsPath);
        $candidate = $this->viewsPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleanTemplate) . '.php';
        $targetReal = realpath($candidate);

        if (
            $baseReal === false
            || $targetReal === false
            || !is_file($targetReal)
            || !str_starts_with($targetReal, $baseReal . DIRECTORY_SEPARATOR)
        ) {
            throw new RuntimeException("Шаблон ёфт нашуд: {$template}");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $targetReal;

        return (string) ob_get_clean();
    }

    /**
     * Экранкунии ҳатмии маълумоти динамикӣ барои ҳимоя аз XSS.
     */
    public function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Сохтани суроғаи дохилӣ бо назардошти роҳи базавии барнома.
     */
    public function url(string $path = '/'): string
    {
        $normalized = str_starts_with($path, '/') ? $path : '/' . $path;

        return Router::basePath() . $normalized;
    }

    /**
     * Боргузории фрагменти шаблон аз views/partials/.
     *
     * @param  array<string, mixed>  $data
     */
    public function partial(string $name, array $data = []): string
    {
        $cleanName = ltrim(str_replace('\\', '/', $name), '/');

        return $this->renderFile('partials/' . $cleanName, $data);
    }

    /**
     * Форматкунии сана ба намуди хонои DD.MM.YYYY.
     */
    public function date(?string $value): string
    {
        if ($value === null || trim($value) === '') {
            return '—';
        }

        try {
            $dt = new DateTimeImmutable($value);

            return $dt->format('d.m.Y');
        } catch (\Throwable) {
            return $this->e($value);
        }
    }

    /**
     * Ҷудокунии ҳарфҳои аввали ному насаб бо дастгирии UTF-8 / тоҷикӣ.
     */
    public function initials(string ...$parts): string
    {
        $tokens = [];
        foreach ($parts as $part) {
            $trimmed = trim($part);
            if ($trimmed === '') {
                continue;
            }
            foreach (preg_split('/\s+/u', $trimmed) ?: [] as $word) {
                if ($word !== '') {
                    $tokens[] = $word;
                }
            }
        }

        if ($tokens === []) {
            return 'БА';
        }

        $chars = '';
        foreach (array_slice($tokens, 0, 2) as $token) {
            $chars .= mb_substr($token, 0, 1, 'UTF-8');
        }

        return mb_strtoupper($chars, 'UTF-8');
    }

    /**
     * Баргардонидани номи тоҷикии статус ё тамға аз LABELS.
     */
    public function label(string $group, string $key, string $default = 'Номаълум'): string
    {
        return self::LABELS[$group][$key] ?? $default;
    }

    /**
     * @return array<string, string>
     */
    public function labels(string $group): array
    {
        return self::LABELS[$group] ?? [];
    }
}
