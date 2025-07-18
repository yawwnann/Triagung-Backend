<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CekRoleAdmin;
use App\Http\Controllers\SupabaseUploadController;

Route::get('/', function () {
    return view('welcome');
});


