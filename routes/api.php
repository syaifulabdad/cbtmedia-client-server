<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return response()->json([
        'status' => false,
        'message' => 'Akses tidak diizinkan.!!'
    ], 401);
})->name('api-login');
