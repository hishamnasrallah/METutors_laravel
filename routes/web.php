<?php

use App\Http\Controllers\VedioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/index', function () {
    return "hello";
});

Route::get('join-meeting',[VedioController::class,'index']);
Route::get('join-meeting/{string}',[VedioController::class,'meeting_join']);

Route::get('search-student', [VedioController::class, 'student']);
Route::post('search-student', [VedioController::class, 'search_student'])->name('search_student');

Auth::routes(['verify' => true]);
