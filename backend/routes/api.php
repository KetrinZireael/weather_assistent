<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\FeedbackController;

/*
|--------------------------------------------------------------------------
| Feedback API
|--------------------------------------------------------------------------
*/

Route::get('/feedback', [FeedbackController::class, 'index']);
Route::post('/feedback', [FeedbackController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Weather API
|--------------------------------------------------------------------------
*/

Route::get('/weather', function (Request $request) {
    $city = $request->query('city', 'Poltava'); // за замовчуванням — Полтава
    $apiKey = env('WEATHER_API_KEY');

    $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
        'q' => $city,
        'appid' => $apiKey,
        'units' => 'metric',
        'lang' => 'ua',
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
        'advice' => $advice,
    ]);
});

/*
|--------------------------------------------------------------------------
| Auth API (Laravel Sanctum)
|--------------------------------------------------------------------------
*/

// 🟩 Реєстрація
Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Реєстрація успішна!',
        'token' => $token,
        'user' => $user,
    ]);
});

// 🟦 Вхід
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Невірний email або пароль'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Вхід виконано успішно!',
        'token' => $token,
        'user' => $user,
    ]);
});

// 🟥 Вихід
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Вихід виконано']);
});

// 🟨 Поточний користувач
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
