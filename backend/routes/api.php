<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\WeatherController;

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
|
| Повністю логіка погоди тепер винесена у WeatherController@index.
| Там обчислюється поточна температура, прогноз, рекомендації тощо.
|
*/

Route::get('/weather', [WeatherController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Auth API (Laravel Sanctum)
|--------------------------------------------------------------------------
|
| Реєстрація, вхід, вихід і отримання даних користувача.
| Використовуються стандартні методи Sanctum для токенів.
|
*/

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

Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Вихід виконано']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
