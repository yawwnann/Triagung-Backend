<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CekRoleAdmin;
use App\Http\Controllers\SupabaseUploadController;

Route::get('/', function () {
    return view('welcome');
});
Route::view('/upload-supabase', 'upload-supabase');
Route::post('/upload-supabase', [SupabaseUploadController::class, 'upload']);


