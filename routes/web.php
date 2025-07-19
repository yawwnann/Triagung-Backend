<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CekRoleAdmin;
use App\Http\Controllers\SupabaseUploadController;

Route::get('/', function () {
    return view('welcome');
});

// Health check route for Railway
Route::get('/up', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'environment' => app()->environment()
    ]);
});

// Test route for debugging
Route::get('/test-auth', function () {
    return response()->json([
        'message' => 'Auth test route working',
        'session_id' => session()->getId(),
        'user' => \Illuminate\Support\Facades\Auth::user(),
        'timestamp' => now()
    ]);
});


