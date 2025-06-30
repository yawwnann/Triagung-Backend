<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect('/admin/login');
        }
        $user = \Illuminate\Support\Facades\Auth::user();
        // DEBUG: tampilkan info user di browser
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
        ]);
        // if ($user->role !== 'admin') {
        //     abort(403);
        // }
        // return $next($request);
    }
}
