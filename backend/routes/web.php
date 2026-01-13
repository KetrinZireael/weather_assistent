<?php

use Illuminate\Support\Facades\Route;

// Всі API йдуть через routes/api.php
// А решта запитів — повертаємо Vue SPA
Route::get('/{any}', function () {
    $index = public_path('build/index.html');

    if (file_exists($index)) {
        return file_get_contents($index);
    }

    // fallback якщо немає Vue-збірки
    return response('Frontend build not found', 404);
})->where('any', '.*');
