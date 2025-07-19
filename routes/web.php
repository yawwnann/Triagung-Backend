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

// Custom Filament Authentication Routes (if needed)
// Route::prefix('admin')->group(function () {
//     Route::get('/login', [App\Http\Controllers\FilamentAuthController::class, 'showLoginForm'])
//         ->name('filament.admin.auth.login')
//         ->middleware('guest');
    
//     Route::post('/login', [App\Http\Controllers\FilamentAuthController::class, 'login'])
//         ->name('filament.admin.auth.login.store')
//         ->middleware('guest');
    
//     Route::post('/logout', [App\Http\Controllers\FilamentAuthController::class, 'logout'])
//         ->name('filament.admin.auth.logout')
//         ->middleware('auth');
// });


