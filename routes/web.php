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

Route::get('/users', [UserController::class, 'index']);

Route::post('/users', [UserController::class, 'store']);

Route::patch('/users/{user}/change-role', [UserController::class, 'changeRole']);

Route::put('/users/{user}', [UserController::class, 'update']);

Route::delete('/users/delete/{user}', [UserController::class, 'destroy']);

Route::get('{view}', VueController::class)->where('view', '(.*)');
