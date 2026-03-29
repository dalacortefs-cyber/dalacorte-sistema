<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditMiddleware
{
    public function __construct(private AuditService $auditService) {}

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $this->auditService->registrar(
                acao: strtolower($request->method()),
                modulo: $this->extrairModulo($request),
                observacao: $request->path()
            );
        }

        return $response;
    }

    private function extrairModulo(Request $request): string
    {
        $segmentos = explode('/', $request->path());
        return $segmentos[1] ?? 'sistema';
    }
}
