<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle($request, Closure $next, ...$permissions)
    {
        $user = Auth::user();

        if ($user && $user->hasAnyPermission($permissions)) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized. Insufficient permissions.'], 403);
    }
}