<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
                    ->withRouting(
                        web: __DIR__ . '/../routes/web.php',
                        api: __DIR__ . '/../routes/api.php', // 🔹 додаємо api
                        commands: __DIR__ . '/../routes/console.php',
                        health: '/up',
    )
                  ->withMiddleware(function (Middleware $middleware): void {
                      // Реєстрація псевдоніму (не обов’язково, але зручно для явного використання)
                      $middleware->alias([
                          'stateful' => EnsureFrontendRequestsAreStateful::class,
                      ]);

                      // Додаємо Sanctum у групу middleware 'api'
                      $middleware->appendToGroup('api', [
                          EnsureFrontendRequestsAreStateful::class,
                      ]);
                  })
                  ->withExceptions(function (Exceptions $exceptions): void {
                      //
                  })
                  ->create();
