<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();
        $allowed = $user && collect($permissions)->flatMap(fn ($p) => explode('|', $p))->contains(fn ($permission) => $user->can($permission));
        if (!$allowed) return response()->json(['status' => 'error', 'message' => 'ليس لديك صلاحية لتنفيذ هذا الإجراء.'], 403);
        return $next($request);
    }
}
