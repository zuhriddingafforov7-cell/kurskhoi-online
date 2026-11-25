<?php
/**
 * Front controller — нуқтаи ягонаи вуруд барои зерлоиҳаи Biyomuz Academy (Лабораторияи №12).
 * Ҳамаи дархостҳои динамикӣ тавассути ин файл ба Router фиристода мешаванд.
 */
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\CategoryController;
use App\Controllers\CourseController;
use App\Controllers\HomeController;
use App\Core\Router;

if (PHP_SAPI === 'cli-server') {
    $rawPath = (string) (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
    $publicRoot = realpath(__DIR__);
    $candidate = $publicRoot !== false ? realpath($publicRoot . DIRECTORY_SEPARATOR . ltrim($rawPath, '/')) : false;
    if (
        $publicRoot !== false
        && $candidate !== false
        && $candidate !== __FILE__
        && is_file($candidate)
        && str_starts_with($candidate, $publicRoot . DIRECTORY_SEPARATOR)
    ) {
        return false;
    }
}

define('BASE_PATH', dirname(__DIR__));

if (is_file(BASE_PATH . '/vendor/autoload.php')) {
    require BASE_PATH . '/vendor/autoload.php';
} else {
    spl_autoload_register(static function (string $class): void {
        if (!str_starts_with($class, 'App\\')) {
            return;
        }

        $file = BASE_PATH . '/src/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (is_file($file)) {
            require $file;
        }
    });
}

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/courses', [CourseController::class, 'index']);
$router->get('/courses/{id}', [CourseController::class, 'show']);
$router->get('/categories', [CategoryController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->get('/register', [AuthController::class, 'showRegister']);

$router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    $_SERVER['REQUEST_URI'] ?? '/'
);
