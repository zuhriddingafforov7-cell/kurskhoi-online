<?php

declare(strict_types=1);

/**
 * Намунаи танзимоти пайвастшавии PDO ба пойгоҳи додаи Biyomuz Academy.
 *
 * Барои муҳити маҳаллӣ файлро ба `config/config.php` нусха бардоред
 * (он дар `.gitignore` истисно шудааст ва ба Git фиристода намешавад)
 * ё қиматҳои воқеиро дар файли `.env`-и Laravel муайян намоед.
 */
return [
    'db' => [
        'driver'  => 'mysql',
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'dbname'  => 'kurskhoi_online',
        'user'    => 'YOUR_DB_USER',
        'pass'    => 'YOUR_DB_PASSWORD',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'debug' => false,
    ],
];
