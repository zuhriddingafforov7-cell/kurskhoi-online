<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Масиргузори (Router) дархостҳои HTTP бо дастгирии параметрҳои рақамӣ,
 * ҷудокунии Query String, муайянсозии basePath ва коркарди 404 / 405.
 */
final class Router
{
    /**
     * @var list<array{method: string, path: string, regex: string, handler: array{0: class-string, 1: string}}>
     */
    private array $routes = [];

    /**
     * Бақайдгирии масири HTTP GET.
     *
     * @param  array{0: class-string, 1: string}  $handler
     */
    public function get(string $path, array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Бақайдгирии масири HTTP POST.
     *
     * @param  array{0: class-string, 1: string}  $handler
     */
    public function post(string $path, array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Таҳлили дархост ва фиристодан ба контроллери мувофиқ (ё 404 / 405).
     */
    public function dispatch(string $method, string $uri): void
    {
        $normalizedMethod = strtoupper(trim($method));
        $matchMethod = $normalizedMethod === 'HEAD' ? 'GET' : $normalizedMethod;
        $path = $this->normalizePath($uri);
        $allowedMethods = [];

        foreach ($this->routes as $route) {
            if (!preg_match($route['regex'], $path, $matches)) {
                continue;
            }

            if ($route['method'] !== $matchMethod) {
                $allowedMethods[] = $route['method'];
                continue;
            }

            // Танҳо параметрҳои номдорро ҳамчун адади бутун ба метод месупорем
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            $params = array_map('intval', array_values($params));

            [$class, $action] = $route['handler'];
            $controller = new $class();
            $controller->$action(...$params);

            return;
        }

        $view = new View(dirname(__DIR__, 2) . '/views');

        if ($allowedMethods !== []) {
            $allowHeader = implode(', ', array_values(array_unique($allowedMethods)));
            http_response_code(405);
            header('Allow: ' . $allowHeader);
            echo $view->render('errors/error', [
                'title'   => 'Усули дархост иҷозатдода нест',
                'active'  => '',
                'code'    => 405,
                'message' => "Усули HTTP {$normalizedMethod} барои ин суроға иҷозатдода нест. Усулҳои иҷозатдода: {$allowHeader}.",
            ]);

            return;
        }

        http_response_code(404);
        echo $view->render('errors/error', [
            'title'   => 'Саҳифа ёфт нашуд',
            'active'  => '',
            'code'    => 404,
            'message' => 'Суроғаи дархостшуда дар платформаи Biyomuz Academy ёфт нашуд. Лутфан ба саҳифаи асосӣ ё каталоги курсҳо гузаред.',
        ]);
    }

    /**
     * Хориҷ кардани Query String, роҳи базавӣ ва слеши охирин аз URI.
     */
    public function normalizePath(string $uri): string
    {
        $rawPath = parse_url($uri, PHP_URL_PATH);
        $path = is_string($rawPath) ? $rawPath : (string) strtok($uri, '?');
        $path = str_replace('\\', '/', $path);

        $basePath = self::basePath();
        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        $trimmed = trim($path, '/');

        return $trimmed === '' ? '/' : '/' . $trimmed;
    }

    /**
     * Муайянсозии роҳи базавӣ (барои решаи php -S ва зерпапкаҳои Apache XAMPP).
     */
    public static function basePath(): string
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        if (PHP_SAPI === 'cli-server' && !str_ends_with($scriptName, '/index.php')) {
            return '';
        }

        $dir = dirname($scriptName !== '' ? $scriptName : '/');
        if ($dir === '/' || $dir === '.' || $dir === '\\') {
            return '';
        }

        return rtrim($dir, '/');
    }

    /**
     * @param  array{0: class-string, 1: string}  $handler
     */
    private function addRoute(string $method, string $path, array $handler): void
    {
        $normalized = '/' . trim($path, '/');
        $this->routes[] = [
            'method'  => $method,
            'path'    => $normalized === '' ? '/' : $normalized,
            'regex'   => $this->toRegex($normalized === '' ? '/' : $normalized),
            'handler' => $handler,
        ];
    }

    /**
     * Табдили шаблони роҳ ба regex бо escape-и дурусти қисмҳои статикӣ.
     * Масалан: '/courses/{id}' -> '#^/courses/(?P<id>\d+)$#'
     */
    private function toRegex(string $path): string
    {
        if ($path === '/') {
            return '#^/$#';
        }

        $parts = preg_split('/(\{\w+\})/', $path, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($parts === false) {
            return '#^' . preg_quote($path, '#') . '$#';
        }

        $regex = '';
        foreach ($parts as $part) {
            if ($part === '') {
                continue;
            }
            if (preg_match('/^\{(\w+)\}$/', $part, $matches) === 1) {
                $regex .= '(?P<' . $matches[1] . '>\d+)';
            } else {
                $regex .= preg_quote($part, '#');
            }
        }

        return '#^' . $regex . '$#';
    }
}
