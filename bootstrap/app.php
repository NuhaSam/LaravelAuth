<?php

use App\Http\Middleware\RoleMiddleWare;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleWare::class,
       ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->render(function (ValidationException $e, Request $request) {
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'message' => 'There are some errors in sending data.',
        //             'errors' => $e->errors(),
        //         ], 400);
        //     }
        // });
    })->create();
