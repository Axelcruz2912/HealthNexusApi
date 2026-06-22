<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckFinancePin;
use App\Http\Middleware\CheckDoctorPin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
            $middleware->alias(["specialty" => AppHttpMiddlewareCheckSpecialty::class]);
        $middleware->alias([
            'role' => CheckRole::class,
            'finance.pin' => CheckFinancePin::class,
            'doctor.pin' => CheckDoctorPin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
