<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // API (JWT)
            'jwt.auth'    => \PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate::class,
            'jwt.refresh' => \PHPOpenSourceSaver\JWTAuth\Http\Middleware\RefreshToken::class,
            // Painel web
            'check.role'  => \App\Http\Middleware\CheckRole::class,
            // Spatie
            'role'        => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'  => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            // Audit
            'audit'       => \App\Http\Middleware\AuditMiddleware::class,
        ]);

        $middleware->statefulApi();
        $middleware->redirectGuestsTo('/login');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Token expirado.'], 401);
            }
        });
        $exceptions->render(function (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Token inválido.'], 401);
            }
        });
        $exceptions->render(function (\PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Token ausente.'], 401);
            }
        });
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Não autenticado.'], 401);
            }
            return redirect()->guest('/login');
        });
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Sem permissão para acessar este recurso.'], 403);
            }
            abort(403, 'Acesso negado.');
        });
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Dados inválidos.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });
    })->create();
