<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RfidApiKeyMiddleware;
return Application::configure(basePath: dirname(__DIR__))
   ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
     ->withMiddleware(function (Middleware $middleware) {
        // Production traffic reaches Laravel through the Caddy container.
        // Trust the direct proxy so signed URLs are validated using the
        // original HTTPS scheme and host from the forwarded headers.
        $middleware->trustProxies(at: '*');

        $middleware->redirectGuestsTo('/admin/login');
        $middleware->alias([
            'rfid.api.key' => RfidApiKeyMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
