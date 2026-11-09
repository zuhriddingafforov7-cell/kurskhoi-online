<?php

use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return response('Login page', 200);
})->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/author/courses', [
        CourseController::class,
        'index'
    ])->name('author.courses.index');

    Route::post('/author/courses', [
        CourseController::class,
        'store'
    ])->name('author.courses.store');
});
