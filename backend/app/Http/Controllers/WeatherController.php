<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->query('city', 'Kyiv');
        $apiKey = env('WEATHER_API_KEY');
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'ua',
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Не вдалося отримати погоду'
            ], 400);
        }

        $data = $response->json();

        // Побудуємо простішу відповідь
        return response()->json([
            'city' => $data['name'],
            'temperature' => $data['main']['temp'],
            'condition' => $data['weather'][0]['description'],
            'advice' => $this->getAdvice($data['main']['temp']),
        ]);
    }

    private function getAdvice(float $temp): string
    {
        if ($temp >= 25) return 'Спекотно — вдягай щось легке та відкриті ноги ☀️';
        if ($temp >= 15) return 'Приємна погода — футболка з джинсами підійдуть 👕';
        if ($temp >= 5) return 'Прохолодно — вдягни светр чи легку куртку 🧥';
        return 'Холодно — тепла куртка, шарф і рукавиці потрібні ❄️';
    }
}
