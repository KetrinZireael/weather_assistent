<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        // Отримуємо місто з параметра запиту (якщо не передане — Poltava)
        $city = $request->query('city', 'Poltava');

        // Ключ для OpenWeather API
        $apiKey = env('WEATHER_API_KEY');

        // Виконуємо запит до OpenWeather
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'ua',
        ]);

        // Якщо помилка — повертаємо повідомлення
        if ($response->failed()) {
            return response()->json([
                'error' => 'Не вдалося отримати дані про погоду. Перевір API ключ або назву міста.'
            ], 400);
        }

        return $response->json();
    }
}
