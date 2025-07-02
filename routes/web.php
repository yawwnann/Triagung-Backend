<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CekRoleAdmin;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([CekRoleAdmin::class])->group(function () {
    Route::get('/admin', function () {
        return 'Selamat datang, Admin!';
    });
});
