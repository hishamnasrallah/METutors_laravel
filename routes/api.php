<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API RoutesF
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['cors', 'share']], function () {

    //*************** */ Faq Api's***************

    //************** / Manage Classes Api's***************

    Route::Post('create-class', 'ClassController@create_class');

    Route::Post('del-session', 'ClassController@del_session');
    Route::Post('update-session', 'ClassController@update_session');

    Route::get('teacherProfile', 'ClassController@teacher_profile');
    Route::get('teachers/search', 'ClassController@search_tutor');
    Route::Post('schedule-class', 'ClassController@schedule_class');
    Route::get('classes/show', 'ClassController@show_classes');
    Route::post('classes/class-detail', 'ClassController@class_detail');
    Route::post('classes/class-url', 'ClassController@class_url');
    Route::get('classes/search', 'ClassController@search_class');
    Route::get('registered-students', 'ClassController@registered_students');
    Route::get('registered-teachers', 'ClassController@registered_teachers');

    Route::get('search-student', 'ClassController@search_student');
    Route::get('courses', 'ClassController@courses');
    Route::get('courses/search-course', 'ClassController@search_course');
    Route::get('courses/course-detail/{id}', 'ClassController@course_detail');
});

Route::group(['namespace' => 'Auth', 'middleware' => ['cors', 'share']], function () {

    Route::post('/login', 'LoginController@login');
    Route::get('/login', 'LoginController@showLoginForm');

    Route::post('/register', 'RegisterController@register');
    Route::get('/verification', 'VerificationController@index');
    Route::post('/verification', 'VerificationController@confirmCode');

    Route::post('/verification/resend', 'VerificationController@resendCode');

    Route::post('/send-email', 'ForgotPasswordController@forgot');

    Route::get('reset-password/{token}', 'ResetPasswordController@getPassword');

    Route::post('/reset-password', 'ResetPasswordController@updatePassword');
    Route::get('/google', 'SocialiteController@redirectToGoogle');
    Route::post('/google/callback', 'SocialiteController@handleGoogleCallback');
    Route::get('/facebook/redirect', 'SocialiteController@redirectToFacebook');
    Route::post('/facebook/callback', 'SocialiteController@handleFacebookCallback');

    Route::post('/check-googleid', 'SocialiteController@check_googleId');
    Route::post('/check-facebookid', 'SocialiteController@check_facebookId');

    Route::post('/google-signup', 'SocialiteController@google_signup');
    Route::post('/facebook-signup', 'SocialiteController@facebook_signup');

    Route::post('/registeration', 'RegisterController@registeration');

    Route::post('/verifyOtp', 'LoginController@verifyOtp');
    Route::get('/resendOtp', 'LoginController@resendOtp');

    Route::post('/interview-request', 'InterviewRequestController@interview_request');

    Route::get('/interview-requests', 'InterviewRequestController@interview_requests');

    Route::get('/interview-requests/detail', 'InterviewRequestController@interview_requests_details');

     Route::post('/logout', 'LoginController@logout');



        Route::get('/testing', 'LoginController@testing');



        Route::post('validate-password', 'ResetPasswordController@validate_password');

        Route::post('change-password', 'ResetPasswordController@change_password');
        Route::post('change-email', 'ResetPasswordController@change_email');
        Route::post('submit-email-withotp', 'ResetPasswordController@submit_email_withOtp');

});

Route::get('estimated-price', 'PricingController@estimated_price');

Route::post('final-invoice', 'PricingController@final_invoice');

Route::post('contact-us', 'FaqController@contact_us');
Route::post('add-faq', 'FaqController@add_faq');
Route::get('faq-topics', 'FaqController@faq_topics');
Route::get('faqs', 'FaqController@faqs');
Route::post('faqs/search', 'FaqController@search_faq');

Route::get('level-of-education', 'GeneralController@level_of_education');
Route::get('course-levels', 'GeneralController@course_levels');
Route::get('multi-field-subjects', 'GeneralController@multi_field_subjects');
Route::get('field-of-studies', 'GeneralController@field_of_studies');

Route::get('timezones', 'GeneralController@timezones');
Route::get('programs', 'GeneralController@programs');
Route::get('countries', 'GeneralController@countries');
Route::get('cities', 'GeneralController@cities');
Route::get('subjects', 'GeneralController@subjects');
Route::get('field-of-study', 'GeneralController@field_of_study');
Route::get('field-subjects', 'GeneralController@field_subjects');
Route::get('languages', 'GeneralController@languages');

Route::post('ticket-categories', 'TicketsController@ticket_categories');
Route::post('ticket-priorities', 'TicketsController@ticket_priorities');


Route::post('create-ticket', 'TicketsController@store_new');
Route::get('create-ticket', 'TicketsController@store_new');
Route::get('new-ticket', 'TicketsController@create');
Route::get('my_tickets', 'TicketsController@userTickets');
Route::get('tickets/{ticket_id}', 'TicketsController@show');
Route::post('comment', 'CommentsController@postComment');

Route::group(['prefix' => 'admin'], function () {

    Route::get('tickets', 'TicketsController@index');
    Route::post('close_ticket/{ticket_id}', 'TicketsController@close');
});

Route::get('/roles', 'Admin\RoleController@roles');
Route::get('/approve-document', 'Admin\UserController@approve_document');
Route::get('/reject-document', 'Admin\UserController@reject_document');

Route::get('filtered-teacher', 'Web\UserController@filteredTeacher');

Route::group(['namespace' => 'Web', 'middleware' => ['impersonate', 'share']], function () {

    Route::group(['prefix' => 'admin'], function () {

        Route::get('/{id}/profile', 'UserController@admin_profile');
    });

    Route::group(['prefix' => 'student'], function () {

        Route::get('/{id}/profile', 'UserController@student_profile');
    });

    Route::group(['prefix' => 'teacher'], function () {

        Route::get('/{id}/profile', 'UserController@teacher_profile');
    });
});

Route::group(['namespace' => 'Panel', 'prefix' => 'student', 'middleware' => ['auth:sanctum', 'share']], function () {

    Route::group(['prefix' => 'setting'], function () {

        Route::post('/', 'UserController@student_update');
    });
});

Route::group(['namespace' => 'Panel', 'prefix' => 'teacher', 'middleware' => ['auth:sanctum', 'share']], function () {

    Route::get('/documents', 'UserController@teacher_documents');

    Route::group(['prefix' => 'setting'], function () {

        Route::post('/', 'UserController@teacher_update');
    });
});

Route::post('invite/friends', 'Panel\UserController@invite_friends');

Route::post('teacher/approve', 'Panel\UserController@teacher_approve');

Route::post('teacher/reject', 'Panel\UserController@teacher_reject');

Route::post('teacher/upload-documents', 'Panel\UserController@upload_documents');

Route::post('teacher/upload-documents2', 'Panel\UserController@upload_documents2');

Route::post('teacher/complete-account', 'Panel\UserController@teacher_complete_account');

Route::post('add-language', 'Panel\UserController@add_language');
Route::post('remove-language', 'Panel\UserController@remove_language');

Route::post('add-subjects', 'Panel\UserController@add_subjects');
Route::post('remove-subjects', 'Panel\UserController@remove_subjects');

Route::post('add-availability', 'Panel\UserController@add_availability');
Route::post('remove-availability', 'Panel\UserController@remove_availability');

Route::group(['namespace' => 'Panel', 'prefix' => 'teacher', 'middleware' => ['impersonate', 'panel', 'share']], function () {

    Route::group(['prefix' => 'setting'], function () {

        Route::post('/', 'UserController@teacher_update');
        //  Route::post('/upload-documents', 'UserController@upload_documents');

    });
});

//*************** */ Feedback Api's***************
Route::post('feedbacks/teacher', 'FeedbackController@feedback');

Route::post('users/block-user', 'AdminController@block_user');
Route::post('users/unblock-user', 'AdminController@unblock_user');

Route::post('teacher-performance', 'AdminController@teacher_performance');

Route::get('teachers/teacher-detail/{$teacher_id}', 'FeedbackController@teacher_details');

Route::get('teacher-profile', 'Web\UserController@teacher_public_profile');

//*************** */ Dashboard Api's***************

Route::get('teacher-dashboard', 'DashboardController@teacher_dashboard');
Route::post('invoice-mail', 'DashboardController@invoice_mail');
Route::get('classes-dashboard', 'DashboardController@classes_dashboard');

Route::group(["middleware" => "auth.jwt"], function () {
    Route::post('feedbacks/student', 'FeedbackController@feedback');
});

Route::get('students/profile', 'ClassController@student_profile');
Route::Post('teacher/update-profile', 'DashboardController@update_teacherProfile');
Route::Post('newsletter', 'FaqController@newsletter');
Route::Post('teachers/change-teacher', 'AdminController@change_teacher');
Route::Post('teachers/warn-teacher', 'AdminController@warn_teacher');
Route::Post('test-api', 'AdminController@testApi');

