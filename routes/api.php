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

Route::group(['middleware' => ['cors', 'share', 'jwt.verify']], function () {

    Route::get('testing_verify', 'GeneralController@testing_verify');
});

Route::group(['middleware' => ['cors', 'share']], function () {





    Route::post('admin/schedule-meeting', 'AdminController@schedule_meeting');
    Route::get('join-meeting/{interview_request_id}', 'AdminController@join_meeting');


    //**************** Student routes starts****************

    Route::get('student/dashboard', 'Student\DashboardController@dashboard');
    Route::get('student/classes', 'Student\DashboardController@classes_dashboard');

    Route::get('student/classroom', 'Student\ClassController@courses');
    Route::get('student/courses/search-course', 'Student\ClassController@search_course');
    Route::get('student/classes/{id}', 'Student\ClassController@course_detail');

    //**************** Syllabus routes ****************
    Route::get('student/syllabus/{course_id}', 'Student\SyllabusController@syllabusDashboard');


    //**************** Assignment routes ****************
    Route::get('student/assignments/{id}', 'Student\AssignmentController@assignmentDashboard');
    Route::get('student/assignment/{id}', 'Student\AssignmentController@assignmentDetail');
    Route::Post('student/assignment/{id}', 'Student\AssignmentController@submitAssignment');
    // Route::get('student/assignment/{assignment_id}/user', 'Student\AssignmentController@userAssignment');
    Route::get('student/assignment/user/{user_assignment_id}', 'Student\AssignmentController@userAssignment');

    //**************** Resources routes ****************
    Route::get('student/resources/{course_id}', 'Student\ResourcesController@classResources');
    Route::get('student/resource/{resource_id}', 'Student\ResourcesController@editResource');
    Route::post('student/resource/update/{resource_id}', 'Student\ResourcesController@updateResource');

    //**************** Student routes ends****************



    Route::post('change-avatar', 'Panel\UserController@change_avatar');
    Route::post('change-cover', 'Panel\UserController@change_cover');

    Route::post('teacher/profile', 'Panel\UserController@teacher_update_profile');

    //**************** Teacher routes starts****************

    Route::get('teacher/profile', 'Teacher\DashboardController@profile');
    Route::get('teacher/dashboard', 'Teacher\DashboardController@dashboard');
    Route::get('teacher/classes', 'Teacher\DashboardController@classes_dashboard');

    Route::get('teacher/classroom', 'Teacher\ClassController@courses');
    Route::get('teacher/courses/search', 'Teacher\ClassController@search_course');
    Route::get('teacher/classes/{id}', 'Teacher\ClassController@course_detail');

    //**************** Syllabus routes ****************
    Route::get('teacher/syllabus/{course_id}', 'Teacher\SyllabusController@syllabusDashboard');
    Route::post('teacher/syllabus/topic', 'Teacher\SyllabusController@addTopic');
    Route::get('teacher/syllabus/topic/{topic_id}', 'Teacher\SyllabusController@editTopic');
    Route::post('teacher/syllabus/topic/update', 'Teacher\SyllabusController@updateTopic');
    Route::delete('teacher/syllabus/topic/{id}', 'Teacher\SyllabusController@deleteTopic');

    //**************** Edit class name ****************
    Route::patch('teacher/class/{acadamic_class_id}', 'Teacher\SyllabusController@editTopicClass');


    //**************** Assignment routes ****************
    Route::get('teacher/assignment/{id}', 'Teacher\AssignmentController@assignmentDetail');
    Route::post('teacher/assignment/update/{id}/', 'Teacher\AssignmentController@updateAssignment');
    Route::delete('teacher/assignment/{id}', 'Teacher\AssignmentController@deleteAssignment');
    Route::post('teacher/assignment', 'Teacher\AssignmentController@addAssignment');
    Route::get('teacher/assignments/{id}', 'Teacher\AssignmentController@assignmentDashboard');
    Route::get('teacher/course/{course_id}/assignees', 'Teacher\AssignmentController@assignees');
    Route::post('teacher/assignment/{assignment_id}/accept', 'Teacher\AssignmentController@acceptAssignment');
    Route::post('teacher/assignment/{assignment_id}/reject', 'Teacher\AssignmentController@rejectAssignment');
    Route::get('teacher/assignment/{assignment_id}/user/{user_id}', 'Teacher\AssignmentController@userAssignment');
    Route::post('teacher/assignment/{assignment_id}/extend', 'Teacher\AssignmentController@extendDate');

    //**************** Resources routes ****************
    Route::post('upload', 'Teacher\ResourcesController@uploadFiles');
    Route::delete('file/{id}', 'Teacher\ResourcesController@deleteFile');
    Route::get('teacher/resources/{course_id}', 'Teacher\ResourcesController@classResources');
    Route::post('teacher/resource/update/{resource_id}', 'Teacher\ResourcesController@updateResource');
    Route::get('teacher/resource/{resource_id}', 'Teacher\ResourcesController@editResource');
    Route::delete('teacher/resource/{resource_id}', 'Teacher\ResourcesController@delResource');
    Route::post('teacher/class/{class_id}/resource', 'Teacher\ResourcesController@addResource');


    //**************** Teacher routes ends****************

    Route::get('access-token', 'TwilioController@generate_token');


    //*************** */ Faq Api's***************

    //************** / Manage Classes Api's***************

    Route::Post('course/request', 'ClassController@request_course');

    Route::get('view-class/{id}', 'ClassController@view_class');
    Route::Post('create-course', 'ClassController@create_course');
    Route::Post('create-class', 'ClassController@create_course');
    Route::Post('add-classes', 'ClassController@add_classes');

    Route::Post('course/payment-retry', 'ClassController@course_payment_retry');

    Route::Post('del-session', 'ClassController@del_session');
    Route::Post('update-session', 'ClassController@update_session');

    Route::get('teacherProfile', 'ClassController@teacher_profile');
    Route::get('teachers/search', 'ClassController@search_tutor');
    Route::Post('schedule-class', 'ClassController@schedule_class');
    Route::get('classes/show', 'ClassController@show_classes');
    Route::post('classes/class-detail', 'ClassController@class_detail');
    Route::get('class/launch/{id}', 'ClassController@class_url');
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

    Route::get('admin/interview-request', 'InterviewRequestController@interview_requests');

    Route::get('admin/interview-request/{id}', 'InterviewRequestController@interview_requests_details');

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


Route::resource('program', 'ProgramController');
Route::post('program/{id}', 'ProgramController@update');
Route::resource('program-country', 'ProgramCountryController');
Route::get('all-countries', 'ProgramCountryController@countries');

Route::resource('fieldofstudy', 'FieldOfStudyController');
Route::resource('subject', 'SubjectController');

Route::get('admin/program-countries', 'ProgramCountryController@program_countries');

Route::get('timezones', 'GeneralController@timezones');
Route::get('programs', 'GeneralController@programs');
Route::get('countries', 'GeneralController@countries');
Route::get('cities', 'GeneralController@cities');
Route::get('subjects', 'GeneralController@subjects');
Route::get('field-of-study', 'GeneralController@field_of_study');
Route::get('field-subjects', 'GeneralController@field_subjects');
Route::get('languages', 'GeneralController@languages');

Route::get('ticket-categories', 'TicketsController@ticket_categories');
Route::get('ticket-priorities', 'TicketsController@ticket_priorities');


Route::post('create-ticket', 'TicketsController@store_new');
Route::get('create-ticket', 'TicketsController@store_new');
Route::get('new-ticket', 'TicketsController@create');
Route::get('my-tickets', 'TicketsController@userTickets');
Route::get('tickets/{ticket_id}', 'TicketsController@show');
Route::post('comment', 'CommentsController@postComment');

Route::group(['prefix' => 'admin'], function () {

    Route::get('tickets', 'TicketsController@index');
    Route::post('ticket/change-status', 'TicketsController@change_status');
});

Route::get('/roles', 'Admin\RoleController@roles');
Route::post('admin/approve-document/{id}', 'Admin\UserController@approve_document');
Route::post('admin/reject-document/{id}', 'Admin\UserController@reject_document');

Route::get('filtered-teacher', 'Web\UserController@filteredTeacher');
Route::get('available-teachers', 'Web\UserController@avail_teachers');

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

Route::group(['namespace' => 'Panel', 'prefix' => 'teacher', 'middleware' => ['share']], function () {

    Route::get('/documents', 'UserController@teacher_documents');

    Route::group(['prefix' => 'setting'], function () {

        Route::post('/', 'UserController@teacher_update');
    });
});

Route::post('invite/friends', 'Panel\UserController@invite_friends');

Route::post('teacher/approve/{id}', 'Panel\UserController@teacher_approve');

Route::post('teacher/reject/{id}', 'Panel\UserController@teacher_reject');

Route::post('teacher/upload-documents', 'Panel\UserController@upload_documents');

Route::post('teacher/upload-documents2', 'Panel\UserController@upload_documents2');

Route::post('teacher/complete-account', 'Panel\UserController@teacher_complete_account');

Route::post('add-language', 'Panel\UserController@add_language');
Route::post('remove-language', 'Panel\UserController@remove_language');

Route::post('add-subjects', 'Panel\UserController@add_subjects');
Route::post('remove-subjects', 'Panel\UserController@remove_subjects');

Route::post('add-availability', 'Panel\UserController@add_availability');
Route::delete('remove-availability/{id}', 'Panel\UserController@remove_availability');

Route::group(['namespace' => 'Panel', 'prefix' => 'teacher', 'middleware' => ['impersonate', 'panel', 'share']], function () {

    Route::group(['prefix' => 'setting'], function () {

        Route::post('/', 'UserController@teacher_update');
        //  Route::post('/upload-documents', 'UserController@upload_documents');

    });
});

//**************** Syllabus routes ****************

Route::get('teacher-dashboard', 'DashboardController@teacher_dashboard');
Route::get('teacher/kudos-points', 'DashboardController@kudos_detail');
Route::post('invoice-mail', 'DashboardController@invoice_mail');
Route::get('classes-dashboard', 'DashboardController@classes_dashboard');



Route::get('students/profile', 'ClassController@student_profile');
Route::Post('teacher/update-profile', 'DashboardController@update_teacherProfile');
Route::Post('newsletter', 'FaqController@newsletter');
Route::Post('teachers/change-teacher', 'AdminController@change_teacher');
Route::Post('teachers/warn-teacher', 'AdminController@warn_teacher');
Route::Post('test-api', 'AdminController@testApi');


Route::post('users/block-user', 'AdminController@block_user');
Route::post('users/unblock-user', 'AdminController@unblock_user');
Route::get('get-teachers/{name}', 'Web\UserController@search_teacher');


Route::get('programs/{id}/subjects', 'ClassController@program_subjects');
// Route::get('teacher/kudos-points', 'ClassController@kudos_points');


Route::get('courses/{id}', 'AdminController@program_subjects');

Route::get('search/{searchQuery}', 'TeacherController@overallSearch');





// Route::group(["middleware" => "auth.jwt"], function () {

// });


//**************** Account setting routes ****************
Route::get('student/profile', 'Web\UserController@student_get_profile');
Route::patch('student/account/setting', 'Web\UserController@account_setting');
Route::post('student/security/setting', 'Web\UserController@security_setting');
Route::post('student/preference', 'Web\UserController@user_preference');
Route::get('student/preference', 'Web\UserController@get_user_preference');

//**************** Feedback routes ****************

Route::post('teacher-performance', 'AdminController@teacher_performance');
Route::get('teachers/teacher-detail/{$teacher_id}', 'FeedbackController@teacher_details');
Route::get('teacher-profile', 'Web\UserController@teacher_public_profile');



//**************** classes routes ****************
Route::get('class-attendees/{class_id}', 'TeacherController@classAttendees');
Route::get('todays-classes', 'TeacherController@todaysClasses');
Route::post('teacher/class/reschedule', 'Teacher\ClassController@reschedule_class');
Route::post('student/class/reschedule', 'Student\ClassController@reschedule_class');
Route::post('teacher/class/view', 'Teacher\ClassController@view_class_recording');
Route::post('student/class/view', 'Student\ClassController@view_class_recording');
Route::post('student/course/{course_id}/class', 'Student\ClassController@addClass');


//**************** Courses routes ****************
Route::post('course/{id}/cancel', 'TeacherController@cancelCourse');
Route::post('teacher/course/{id}/cancel', 'TeacherController@cancelCourse');
Route::post('teacher/preferences', 'TeacherController@prefrences');
Route::get('courses/progress', 'TeacherController@coursesProgress');
Route::get('course/{id}/progress', 'TeacherController@courseProgress');
Route::post('course/accept/{id}', 'TeacherController@acceptCourse');
Route::post('course/reject/{id}', 'TeacherController@rejectCourse');
Route::get('reschedule-course/{id}', 'TeacherController@rescheduleCourse');
Route::get('course/{id}/reviews', 'TeacherController@courseReviews');
Route::get('newly-asssigned-courses', 'TeacherController@newlyCourses');

//**************** Resources routes ****************
Route::get('course/{course_id}/resources', 'TeacherController@classResources');
Route::post('resource/{resource_id}/update', 'TeacherController@updateResource');
Route::get('resource/{resource_id}', 'TeacherController@editResource');
Route::delete('resource/{resource_id}', 'TeacherController@delResource');
Route::post('class/{class_id}/resource', 'TeacherController@addResource');

//**************** Assignment routes ****************
Route::get('assignment/{id}', 'TeacherController@assignmentDetail');
Route::post('assignment/{id}/update', 'TeacherController@updateAssignment');
Route::delete('assignment/{id}', 'TeacherController@deleteAssignment');
Route::post('course/assignment/add', 'TeacherController@addAssignment');
Route::get('course/{id}/assignment', 'TeacherController@assignmentDashboard');

//**************** Syllabus routes ****************
Route::get('course/{course_id}/syllabus', 'TeacherController@syllabusDashboard');
Route::post('course/add-topic', 'TeacherController@addTopic');
Route::get('course/edit-topic/{topic_id}', 'TeacherController@editTopic');
Route::post('topic/update', 'TeacherController@updateTopic');
Route::patch('class/edit/{acadamic_class_id}', 'TeacherController@editTopicClass');
Route::delete('topic/{id}', 'TeacherController@deleteTopic');

//**************** Roles management routes ****************
Route::post('roles/add', 'AdminController@add_role');
Route::patch('role/{id}', 'AdminController@update_role');
Route::delete('role/{id}', 'AdminController@delete_role');

Route::post('teachers/filter', 'Web\UserController@filterTeacher');


//**************** Student routes ****************


Route::get('teacher/{teacher_id}/availability', 'TeacherAvailabilityController@getAvailability'); //get teacher availability
Route::get('admin/teacher-availability/{teacher_id}/', 'TeacherAvailabilityController@getAvailability'); //Admin get teacher availability




// Route::group(['middleware' => ['auth.jwt', 'isAdmin']], function () {
Route::resource('course-param', 'CourseParamController');
Route::resource('platform-param', 'PlatformParamController');
Route::get('admin/newsletter', 'AdminController@newsletter');
Route::delete('admin/newsletter/{id}', 'AdminController@del_newsletter');
Route::get('admin/courses', 'AdminController@booked_courses');
Route::get('admin/course/{id}', 'AdminController@course_detail');
Route::get('admin/teachers/courses', 'AdminController@course_teachers');
Route::get('admin/courses/classes', 'AdminController@courses_classes');
Route::get('admin/course/teacher/cancel', 'AdminController@teacher_canceledcourses');
Route::get('admin/course/student/cancel', 'AdminController@student_canceledcourses');
Route::get('admin/teachers/schedule', 'AdminController@teachers_schedule');
Route::get('admin/students/schedule', 'AdminController@students_schedule');
Route::get('admin/feedbacks/course', 'AdminController@course_feedbacks');
Route::delete('admin/course/feedback', 'AdminController@del_coursefeedback');
Route::get('admin/testimonial/{user_id}', 'AdminController@edit_testimonial');
Route::post('admin/testimonial/{user_id}', 'AdminController@update_testimonial');
Route::get('admin/testimonials', 'AdminController@platform_feedbacks');
Route::post('admin/user-testimonial/{user_id}', 'AdminController@testimonial_status');
Route::delete('admin/testimonial/{sender_id}', 'AdminController@del_usertestimonial');
Route::get('admin/teachers/ratings', 'AdminController@teacher_ratings');
Route::post('admin/canceled-course/reassign/teacher', 'AdminController@reassign_teacher');
Route::post('admin/rejected-course/teacher/reassign', 'AdminController@reassignTeacher');
Route::get('admin/teachers', 'AdminController@teachers');

Route::get('admin/suspended-teachers/', 'AdminController@suspended_teachers');
Route::get('admin/rejected-teachers/', 'AdminController@rejected_teachers');
Route::get('admin/pending-teachers/', 'AdminController@pending_teachers');
Route::get('admin/current-teachers/', 'AdminController@current_teachers');
Route::get('admin/subject/{subject_id}/teachers', 'AdminController@subject_teachers');
Route::get('admin/subject/{subject_id}/active-classes', 'AdminController@subject_activeclasses');
Route::get('admin/subject/{subject_id}/upcoming-classes', 'AdminController@subject_upcomingclasses');
Route::get('admin/subject/{subject_id}/canceled-classes', 'AdminController@subject_canceledclasses');
Route::get('admin/subject/{subject_id}/rescheduled-classes', 'AdminController@subject_rescheduledclasses');

Route::get('admin/workforce-capacity', 'AdminController@workforce_capacity');
Route::get('admin/subject/{subject_id}/bookings', 'AdminController@subject_bookings');
Route::get('admin/subject/{subject_id}/hired-teachers', 'AdminController@hired_teachers');
Route::get('admin/subject/{subject_id}/available-teachers', 'AdminController@available_teachers');
Route::get('admin/course/{course_id}/detail', 'AdminController@course_detail');
Route::get('admin/bookings', 'AdminController@classroom');
Route::get('admin/course/{course_id}/feedbacks', 'AdminController@student_feedbacks');
Route::get('admin/course/{course_id}/previous-teachers', 'AdminController@previous_teachers');
Route::get('admin/cancelled-courses', 'AdminController@cancelledCourses');
Route::post('admin/teacher-status', 'AdminController@teacher_status');
Route::get('admin/teacher/{teacher_id}', 'AdminController@teacher_profile');

Route::get('admin/requested-courses', 'AdminController@requested_courses');
Route::post('admin/requested-course/{id}/status', 'AdminController@requested_courses_status');
Route::get('admin/students', 'AdminController@students');
Route::get('admin/student/{student_id}/bookings', 'AdminController@student_bookings');
Route::get('admin/teacher/{teacher_id}/bookings', 'AdminController@teacher_bookings');
Route::get('admin/student/{student_id}/course/{course_id}/booking-details', 'AdminController@booking_detail');
Route::get('admin/teacher/{teacher_id}/course/{course_id}/booking-details', 'AdminController@teacher_course_detail');
Route::get('admin/student/{student_id}/course/{course_id}/assignment-summary', 'AdminController@assignment_summary');

Route::get('admin/student/{student_id}/course/{course_id}/feedback', 'AdminController@studentFeedback');
Route::get('admin/subject-courses', 'AdminController@subject_courses');
Route::get('admin/student/{student_id}', 'AdminController@student_profile');
Route::get('admin/assign-teacher', 'AdminController@teacher_assignment');
Route::post('admin/send-email', 'AdminController@send_mail');
Route::get('admin/orders', 'AdminController@orders');
Route::get('admin/course/{course_id}/order-detail', 'AdminController@order_detail');
Route::get('admin/subject/{subject_id}/orders', 'AdminController@subject_orders');
Route::get('admin/courses/refund', 'AdminController@refund_orders');
Route::get('admin/course/{course_id}/refund/detail', 'AdminController@refund_details');
Route::post('admin/course/{course_id}/teacher-status', 'AdminController@teacherStatus');
Route::get('admin/approval-request', 'AdminController@approval_request');
Route::get('admin/subject/{subject_id}/featured-teacher', 'AdminController@featured_teacher');
Route::get('admin/featured-teachers', 'AdminController@featured_teachers_list');
Route::get('admin/course/{course_id}/refund/account-detail', 'AdminController@refund_account_detail');
Route::post('admin/course/{course_id}/refund', 'AdminController@process_refund');



// });


// Route::group(['middleware' => ['auth.jwt', 'isTeacher']], function () {

// Feedback Routes
Route::post('teacher/feedback', 'FeedbackController@feedback');
Route::get('teacher/feedback/params', 'FeedbackController@feedback_params');
Route::post('teacher/feedback/platform', 'FeedbackController@userPlatform');
Route::get('teacher/feedback/platform/params', 'FeedbackController@PlatformFeedbackParams');
Route::get('teacher/feedback/classroom/params', 'FeedbackController@classroom_params');
Route::post('teacher/feedback/classroom', 'FeedbackController@classroom_feedback');


Route::get('teacher/course/{course_id}/attendance', 'Student\CourseController@teacherAttendence');
// });

// Route::group(['middleware' => ['auth.jwt', 'isStudent']], function () {
Route::get('student/refund/course/{course_id}', 'Student\CourseController@refundCourse');
Route::get('student/refund/course/{course_id}/classes', 'Student\CourseController@refundClasses');
Route::post('student/course/{course_id}/cancel', 'Student\CourseController@cancelCourseReason');

Route::get('student/course/{course_id}/attendance', 'Student\CourseController@getCourseAttendence');
// Feedback Routes
Route::post('student/feedback', 'FeedbackController@feedback');
Route::get('student/feedback/params', 'FeedbackController@feedback_params');
Route::post('student/feedback/platform', 'FeedbackController@userPlatform');
Route::get('student/feedback/platform/params', 'FeedbackController@PlatformFeedbackParams');

Route::get('student/teachers/available', 'Web\UserController@availableTeachers');
Route::get('student/class/{academic_class_id}/makeup-slots', 'Student\ClassController@makeupClassAvailability');
Route::post('student/class/{academic_class_id}/makeup', 'Student\ClassController@makeupClass');


Route::get('student/class/{academic_class_id}/recording', 'Student\ClassController@class_recording');
Route::post('student/course/{course_id}/select-teacher', 'Student\ClassController@teacher_replacement');
Route::post('student/course/{course_id}/request-admin', 'Student\CourseController@request_admin');

//Student Reschedule
Route::get('available-slots', 'TeacherAvailabilityController@available_slots');
Route::post('student/class/reschedule', 'Student\ClassController@reschedule_class');
Route::get('student/class/{class_id}', 'Student\ClassController@classDetail');

Route::group(['middleware' => ['cors']], function () {
    Route::get('get-country', 'TestController@get_country');
});

Route::get('local-utc', 'ClassController@local_to_utc');
Route::get('utc-local', 'ClassController@utc_to_local');
// });

Route::get('payment/prepare-checkout', 'PaymentController@prepareCheckout');
Route::get('payment/status', 'PaymentController@paymentStatus');
Route::get('classes-payment/status', 'PaymentController@classPaymentStatus');
Route::get('payment/{payment_id}/status', 'PaymentController@statusPayment');
Route::get('payment/details', 'PaymentController@payment_details');
Route::Post('payment/{payment_id}/refund', 'PaymentController@refund');
Route::Post('payment/refund2', 'PaymentController@refund2');
Route::get('test', 'TestController@test');
