<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\ApiExceptionFormatter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $formatter = new ApiExceptionFormatter();

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) use ($formatter) {
            if ($request->expectsJson()) {
                return $formatter->error('Validation failed',422, $e->errors());
            }
        });

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) use ($formatter) {
            if ($request->expectsJson()) {
                return $formatter->error('Unauthenticated', 401, []);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) use ($formatter) {
            if ($request->expectsJson()) {
                return $formatter->error('Route not found', 404, []);
            }
        });

        $exceptions->render(function (Throwable $e, $request) use ($formatter) {
            if ($request->expectsJson()) {
                return $formatter->error($e->getMessage(), 500, []);
            }
        });
    })->create();
