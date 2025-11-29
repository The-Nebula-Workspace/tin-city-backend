<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // $middleware->append(\App\Http\Middleware\CorsMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                $statusCode = 500;
                $message = $e->getMessage();
                $errors = null;

                if ($e instanceof ValidationException) {
                    $statusCode = 422;
                    $message = 'Validation error.';
                    $errors = $e->errors();
                } elseif ($e instanceof NotFoundHttpException) {
                    $statusCode = 404;
                    $message = 'Resource not found.';
                } elseif ($e instanceof AuthenticationException) {
                    $statusCode = 401;
                    $message = 'Unauthenticated.';
                } elseif ($e instanceof HttpException) {
                    $statusCode = $e->getStatusCode();
                }

                if ($statusCode === 500 && ! config('app.debug')) {
                    $message = 'Server Error';
                }

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => $errors,
                ], $statusCode);
            }
        });
    })->create();
