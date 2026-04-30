<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/logs', [\App\Http\Controllers\LogController::class, 'index']);
