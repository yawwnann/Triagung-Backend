<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CekRoleAdmin;

Route::get('/', function () {
    return view('welcome');
});


