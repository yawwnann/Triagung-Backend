<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRoleAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            abort(403, 'Akses hanya untuk admin!');
        }
        return $next($request);
    }
}