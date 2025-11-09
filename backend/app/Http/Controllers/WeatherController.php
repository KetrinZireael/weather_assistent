<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->query('city', 'Poltava');
        $apiKey = env('WEATHER_API_KEY');

        $current = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'ua'
        ]);

        $forecast = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'ua'
        ]);

        if ($current->failed() || $forecast->failed()) {
            return response()->json(['error' => 'Не вдалося отримати погоду'], 400);
        }

        $currentData = $current->json();
        $forecastData = $forecast->json();

        $temp = $currentData['main']['temp'] ?? 0;
        $humidity = $currentData['main']['humidity'] ?? 0;
        $wind = $currentData['wind']['speed'] ?? 0;
        $condition = $currentData['weather'][0]['description'] ?? 'невідомо';
        $icon = $this->getWeatherIcon($condition);

        $feels_like = $this->calculateFeelsLike($temp, $wind, $humidity);
        $advice = $this->getClothingAdvice($feels_like, $wind, $humidity);

        $grouped = collect($forecastData['list'])
            ->groupBy(fn($item) => substr($item['dt_txt'], 0, 10))
            ->take(5)
            ->map(function (Collection $day) {
                $temps = $day->pluck('main.temp')->avg();
                $humidity = $day->pluck('main.humidity')->avg();
                $wind = $day->pluck('wind.speed')->avg();
                $condition = $day->first()['weather'][0]['description'] ?? 'невідомо';
                $icon = $this->getWeatherIcon($condition);

                $feels_like = $this->calculateFeelsLike($temps, $wind, $humidity);

                return [
                    'date' => substr($day->first()['dt_txt'], 0, 10),
                    'temperature' => round($temps, 1),
                    'feels_like' => $feels_like,
                    'humidity' => round($humidity),
                    'wind_speed' => round($wind, 1),
                    'condition' => $condition,
                    'icon' => $icon,
                    'advice' => $this->getClothingAdvice($feels_like, $wind, $humidity),
                ];
            })
            ->values();

        return response()->json([
            'city' => $currentData['name'] ?? $city,
            'temperature' => round($temp, 1),
            'humidity' => $humidity,
            'wind_speed' => $wind,
            'feels_like' => $feels_like,
            'condition' => $condition,
            'icon' => $icon,
            'advice' => $advice,
            'forecast' => $grouped
        ]);
    }

    private function calculateFeelsLike($temp, $wind, $humidity)
    {
        $feels_like = $temp;

        if ($temp <= 10 && $wind > 1.3) {
            $wind_kmh = $wind * 3.6;
            $feels_like = round(13.12 + 0.6215 * $temp - 11.37 * pow($wind_kmh, 0.16) + 0.3965 * $temp * pow($wind_kmh, 0.16), 1);
        } elseif ($temp >= 27 && $humidity > 40) {
            $e = ($humidity / 100) * 6.105 * exp((17.27 * $temp) / (237.7 + $temp));
            $feels_like = round($temp + 0.33 * $e - 0.70 * $wind - 4.00, 1);
        }

        return $feels_like;
    }

    private function getClothingAdvice($temp, $wind = 0, $humidity = 0)
    {
        $clothes = '';
        $shoes = '';
        $fabric = '';
        $accessories = '';

        if ($temp >= 30) {
            $clothes = 'легкий одяг — шорти, майка або сукня';
            $shoes = 'сандалі або відкрите взуття';
            $fabric = 'льон, бавовна або віскоза';
            $accessories = 'капелюшок, сонцезахисні окуляри, пляшка води 💧';
            if ($humidity > 60) $fabric = 'льон, бамбук, шовк (дихаючі тканини)';
        } elseif ($temp >= 22) {
            $clothes = 'футболка, легкі штани або сукня';
            $shoes = 'кеди або легке взуття';
            $fabric = 'бавовна, льон, денім';
            $accessories = 'легка сумка, окуляри 😎';
            if ($humidity > 70) $fabric = 'бавовна або льон (щоб уникнути прилипання)';
        } elseif ($temp >= 15) {
            $clothes = 'светр або худі, легка куртка';
            $shoes = 'кросівки або закрите взуття';
            $fabric = 'бавовна, денім або фліс';
            $accessories = 'куртка з капюшоном 🌬️';
            if ($wind > 5) $accessories .= ' + шарф або снуд';
        } elseif ($temp >= 8) {
            $clothes = 'куртка, светр або теплий кардиган';
            $shoes = 'черевики або закрите взуття';
            $fabric = 'фліс, вовна, щільна бавовна';
            $accessories = 'капюшон або парасоля ☔️';
            if ($humidity > 80) $accessories .= ' Уникай замші.';
        } elseif ($temp >= 0) {
            $clothes = 'тепла куртка або пуховик, светр, шарф';
            $shoes = 'зимове взуття або черевики з утепленням';
            $fabric = 'вовна, фліс, термобілизна';
            $accessories = 'шапка, шарф, рукавиці ❄️';
        } else {
            $clothes = 'пуховик, термобілизна, светр із високим горлом';
            $shoes = 'черевики або чоботи з хутром';
            $fabric = 'вовна, фліс, поліестер';
            $accessories = 'шапка, шарф, рукавиці, теплі шкарпетки 🧣🧤';
        }

        return [
            'main' => sprintf(
                "👕 %s.\n👟 Взуття: %s.\n🧩 Додай: %s.",
                $clothes,
                $shoes,
                $accessories
            ),
            'fabric' => $fabric
        ];
    }

    private function getWeatherIcon($condition)
    {
        $condition = mb_strtolower($condition);

        return match (true) {
            str_contains($condition, 'дощ') => '🌧️',
            str_contains($condition, 'гроза') => '⛈️',
            str_contains($condition, 'сніг') => '❄️',
            str_contains($condition, 'туман') => '🌫️',
            str_contains($condition, 'хмар') => '☁️',
            str_contains($condition, 'ясн') => '☀️',
            default => '🌦️',
        };
    }
}
