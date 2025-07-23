<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;


class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            abort(403, 'Not logged in');
        }
        $user = Auth::user();
        if ($user->role !== 'admin') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            abort(403, 'Not admin. Role: ' . $user->role . ', Email: ' . $user->email);
        }
        return $next($request);
    }
}
