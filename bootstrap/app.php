<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Domain\Exceptions\BusinessRuleViolation;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) return response()->json(['status' => 'error', 'message' => 'المورد غير موجود.'], 404);
        });
        $exceptions->render(function (InvalidArgumentException $e, Request $request) {
            if ($request->is('api/*')) return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        });
        $exceptions->render(function (BusinessRuleViolation $e, Request $request) {
            if ($request->is('api/*')) return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        });
    })->create();
