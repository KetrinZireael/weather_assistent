<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\FeedbackController;

Route::get('/feedback', [FeedbackController::class, 'index']);
Route::post('/feedback', [FeedbackController::class, 'store']);

Route::get('/weather', function (Request $request) {
    $city = $request->query('city', 'Poltava'); // за замовчуванням — Полтава
    $apiKey = env('WEATHER_API_KEY');

    $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
        'q' => $city,
        'appid' => $apiKey,
        'units' => 'metric',
        'lang' => 'ua'
    ]);

    if ($response->failed()) {
        return response()->json(['error' => 'Не вдалося отримати погоду'], 400);
    }

    $data = $response->json();
    $temp = $data['main']['temp'] ?? null;

    // порада по одягу
    if ($temp >= 25) {
        $advice = 'Спекотно ☀️ — вдягай щось легке, шорти або сукню';
    } elseif ($temp >= 15) {
        $advice = 'Тепло 🌤️ — футболка або сорочка, легка куртка не завадить';
    } elseif ($temp >= 5) {
        $advice = 'Прохолодно 🌥️ — светр, джинси або куртка';
    } else {
        $advice = 'Холодно ❄️ — тепла куртка, шапка, шарф';
    }

    return response()->json([
        'city' => $data['name'] ?? $city,
        'temperature' => $temp,
        'condition' => $data['weather'][0]['description'] ?? 'невідомо',
        'advice' => $advice
    ]);
});
