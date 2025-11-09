<?php

use Illuminate\Support\Facades\Route;

// Усі API запити обробляються через routes/api.php,
// тому тут ми ловимо все інше і повертаємо Vue SPA
Route::get('/{any}', function () {
    return file_get_contents(public_path('build/index.html'));
})->where('any', '.*');
