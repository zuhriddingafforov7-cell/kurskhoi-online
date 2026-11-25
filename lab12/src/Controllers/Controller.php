<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

/**
 * Контроллери базавӣ барои ҳамаи контроллерҳои Biyomuz Academy.
 */
abstract class Controller
{
    protected View $view;

    public function __construct()
    {
        $this->view = new View(dirname(__DIR__, 2) . '/views');
    }

    /**
     * Тартиб додан ва фиристодани саҳифаи HTML бо рамзи ҳолати HTTP ва макет.
     *
     * @param  array<string, mixed>  $data
     */
    protected function render(string $template, array $data = [], int $status = 200, ?string $layout = 'layout'): void
    {
        http_response_code($status);
        echo $this->view->render($template, $data, $layout);
    }

    /**
     * Равонакунии дубора (HTTP 302 Redirect) бо назардошти basePath.
     */
    protected function redirect(string $path): never
    {
        header('Location: ' . $this->view->url($path), true, 302);
        exit;
    }

    /**
     * Фиристодани ҷавоб дар формати JSON бо кодировкаи UTF-8.
     *
     * @param  array<string, mixed>  $data
     */
    protected function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo (string) json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Намоиши саҳифаи хатои умумӣ бо макети асосӣ ва анҷоми скрипт.
     */
    protected function abort(int $code, string $message = ''): never
    {
        $defaults = [
            403 => ['Дастрасӣ манъ аст', 'Шумо барои кушодани ин саҳифа иҷозат надоред.'],
            404 => ['Саҳифа ёфт нашуд', 'Маълумот ё саҳифаи дархостшуда вуҷуд надорад.'],
            405 => ['Усули дархост иҷозатдода нест', 'Усули HTTP барои ин масир дастгирӣ намешавад.'],
            500 => ['Хатогии дохилии сервер', 'Дар коркарди дархост хатогӣ ба вуҷуд омад.'],
        ];

        [$defaultTitle, $defaultMessage] = $defaults[$code] ?? ['Хатогӣ', 'Дархост иҷро нашуд.'];

        http_response_code($code);
        echo $this->view->render('errors/error', [
            'title'   => $defaultTitle,
            'active'  => '',
            'code'    => $code,
            'message' => $message !== '' ? $message : $defaultMessage,
        ]);
        exit;
    }
}
