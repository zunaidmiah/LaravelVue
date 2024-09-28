<?php

use App\Http\Controllers\VueController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/demo', function () {
//     return view('index');
// });

Route::get('{view}', VueController::class)->where('view', '(.*)');
