<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'twofactor' => \App\Http\Middleware\TwoFactorMiddleware::class,
            'role' => \App\Http\Middleware\checkRole::class,
            'permission' => \App\Http\Middleware\checkPermission::class,
            'studentRole' => \App\Http\Middleware\checkStudentRole::class,
            'adminRole' => \App\Http\Middleware\checkAdminRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
