<?php

declare(strict_types=1);

namespace Src\Core;

use Illuminate\Support\Facades\DB;
use PDO;

/**
 * Фабрикаи пайвастшавии танбалона (lazy connection)-и PDO барои Biyomuz Academy.
 *
 * Дар дохили Laravel аз пайвастшавии ягонаи `DB::connection()->getPdo()` истифода мебарад,
 * то як пайвастшавӣ дар як дархост нигоҳ дошта шавад ва атрибутҳои бехатари PDO таъмин гарданд.
 */
final class Database
{
    private static ?PDO $standalonePdo = null;

    /**
     * Хусусиятҳои ҳатмии PDO мувофиқи талаботи бехатарӣ.
     *
     * @return array<int, mixed>
     */
    public static function options(): array
    {
        return [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
    }

    /**
     * Баргардонидани нусхаи фаъоли PDO (lazy singleton).
     */
    public static function connection(): PDO
    {
        if (function_exists('app') && app()->bound('db')) {
            $pdo = DB::connection()->getPdo();
            self::applyOptions($pdo);

            return $pdo;
        }

        if (self::$standalonePdo instanceof PDO) {
            return self::$standalonePdo;
        }

        $configFile = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
        $config = file_exists($configFile) ? (array) require $configFile : [];
        $dbCfg = is_array($config['db'] ?? null) ? $config['db'] : [];

        self::$standalonePdo = self::connectFromConfig([
            'host'    => $dbCfg['host'] ?? self::envValue('DB_HOST', '127.0.0.1'),
            'port'    => (int) ($dbCfg['port'] ?? self::envValue('DB_PORT', '3306')),
            'dbname'  => $dbCfg['dbname'] ?? self::envValue('DB_DATABASE', 'kurskhoi_online'),
            'user'    => $dbCfg['user'] ?? self::envValue('DB_USERNAME', 'root'),
            'pass'    => $dbCfg['pass'] ?? self::envValue('DB_PASSWORD', ''),
            'charset' => $dbCfg['charset'] ?? 'utf8mb4',
        ]);

        return self::$standalonePdo;
    }

    /**
     * Эҷоди пайвастшавии нави PDO аз массиви танзимот.
     *
     * @param  array<string, mixed>  $cfg
     */
    public static function connectFromConfig(array $cfg): PDO
    {
        $host = (string) ($cfg['host'] ?? '127.0.0.1');
        $port = (int) ($cfg['port'] ?? 3306);
        $dbname = (string) ($cfg['dbname'] ?? 'kurskhoi_online');
        $charset = (string) ($cfg['charset'] ?? 'utf8mb4');
        $user = (string) ($cfg['user'] ?? 'root');
        $pass = (string) ($cfg['pass'] ?? '');

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $host, $port, $dbname, $charset);

        return new PDO($dsn, $user, $pass, self::options());
    }

    /**
     * Тоза кардани пайвастшавии мустақили кэшшуда.
     */
    public static function reset(): void
    {
        self::$standalonePdo = null;
    }

    /**
     * Танзими атрибутҳои зарурӣ ба объекти мавҷудаи PDO.
     */
    private static function applyOptions(PDO $pdo): void
    {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $driver = (string) $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver !== 'sqlite') {
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
    }

    private static function envValue(string $key, string $default): string
    {
        $val = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        return ($val !== false && $val !== null && $val !== '') ? (string) $val : $default;
    }
}
