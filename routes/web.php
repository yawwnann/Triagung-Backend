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


