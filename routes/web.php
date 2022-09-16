<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\VedioController;
use Illuminate\Support\Facades\Auth;
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

Route::get('join-meeting', [VedioController::class, 'index']);
Route::get('join-meeting/{string}', [VedioController::class, 'meeting_join']);

Route::get('search-student', [VedioController::class, 'student']);
Route::post('search-student', [VedioController::class, 'search_student'])->name('search_student');

Auth::routes(['verify' => true]);

Route::get('payment/prepare-checkout', 'PaymentController@prepareCheckout');
Route::get('payment/status', 'PaymentController@paymentStatus')->name('payment_status');

// Testing Routes for HyperPay
Route::get('payment/checkout', 'PaymentController@checkout');
Route::get('payment-status', 'PaymentController@payment_status');


Route::get('courses', [TestController::class, 'courses'])->name('courses');
Route::get('course/accept/{id}', [TestController::class, 'acceptCourse'])->name('accept_course');
Route::get('notifications', [TestController::class, 'notifications'])->name('notifications');
Route::get('notifications/{id}/read', [TestController::class, 'mark_as_read'])->name('mark_as_read');
