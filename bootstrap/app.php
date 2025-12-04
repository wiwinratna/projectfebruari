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
    ->withMiddleware(function (Middleware $middleware): void {
        // Exclude login route from CSRF protection for mobile compatibility
        $middleware->validateCsrfTokens(except: [
            'login',
        ]);
        
        // Configure session for mobile browsers
        $middleware->encryptCookies();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
