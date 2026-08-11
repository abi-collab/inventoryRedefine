<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user || (int) $user->user_role !== 1) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
