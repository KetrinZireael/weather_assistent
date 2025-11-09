<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Перевіряємо, чи не в режимі обслуговування
if (file_exists($maintenance = __DIR__ . '/../backend/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Підключаємо Composer автозавантаження з backend
require __DIR__ . '/../backend/vendor/autoload.php';

// Завантажуємо ядро Laravel з backend
/** @var Application $app */
$app = require_once __DIR__ . '/../backend/bootstrap/app.php';

// Обробляємо HTTP-запит
$app->handleRequest(Request::capture());
