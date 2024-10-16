<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\VueController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/demo', function () {
//     return view('index');
// });

Route::get('/api/users', [UserController::class, 'index']);

Route::post('/api/users', [UserController::class, 'store']);

Route::put('/api/users/{user}', [UserController::class, 'update']);

Route::get('{view}', VueController::class)->where('view', '(.*)');
