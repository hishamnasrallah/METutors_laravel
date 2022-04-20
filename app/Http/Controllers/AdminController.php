<?php

namespace App\Http\Controllers;

use App\Events\CancelCourse;
use App\Models\AcademicClass;
use App\Models\CanceledCourse;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Newsletter;
use App\Models\Role;
use App\Models\Testimonial;
use App\User;
use App\Models\UserFeedback;
use App\Models\UserTestimonial;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use stdClass;
use App\TeacherInterviewRequest;

class AdminController extends Controller
{
    //********/ Change the teacher for some Course ********
    public function change_teacher(Request $request)
    {

        $rules = [
            'teacher_id' =>  'required|integer',
            'course_id' =>  'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ]);
        }

        $course = Course::find($request->course_id);
        $course->teacher_id = $request->teacher_id;
        $classes = AcademicClass::where("course_id", $course->id)->where('status', '=', null)->get();

        foreach ($classes as $class) {
            $cls = AcademicClass::find($class->id);
            $cls->teacher_id = $request->teacher_id;
            $cls->update();
        }
        $course->update();

        return response()->json([
            'status' => true,
            'message' =>  "Teacher Assigned to course Successfully",
        ]);
    }

    public function warn_teacher(Request $request)
    {

        $rules = [
            'teacher_id' =>  'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => false,
                'errors' => $errors,
            ]);
        }

        $teacher = User::find($request->teacher_id);
        if ($teacher->ban <= 2) {
            $teacher->ban = $teacher->ban + 1;
            $teacher->update();

            return response()->json([
                'status' => true,
                'message' => "Warning has been given to teacher!",
            ]);
        } else {
            $teacher->status = "disabled";
            return response()->json([
                'status' => false,
                'message' => "Warning Limit Exceeded! Account has been banned",
            ]);
        }
    }

    public function teacher_performance(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required'
        ]);

        $user = User::find($request->teacher_id);
        $rating_sum = UserFeedback::where('receiver_id', $user->id)->sum('rating');
        $total_reviews = UserFeedback::where('receiver_id', $user->id)->count();
        $average_rating = $rating_sum / $total_reviews;

        return response()->json([
            'status' => true,
            'message' => "Teacher Performance",
            'average_rating' => $average_rating,
        ]);
    }

    public function block_user(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);
        $user->status = 'inactive';
        $user->update();

        return response()->json([
            'status' => true,
            'message' => "User Blocked Successfully",
        ]);
    }

    public function unblock_user(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);
        $user->status = 'active';
        $user->update();

        return response()->json([
            'status' => true,
            'message' => "User UnBlocked Successfully",
        ]);
    }

    public function add_role(Request $request)
    {
        $rules = [
            'name' =>  'required|unique:roles,name',
            'caption' =>  'required',
            'is_admin' =>  'required|bool',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => false,
                'errors' => $errors,
            ]);
        }
        $role = new Role();
        $role->name = $request->name;
        $role->caption = $request->caption;
        $role->is_admin = $request->is_admin;
        $role->save();

        return response()->json([
            'status' => true,
            'message' => "Role added Successfully",
            'role' => $role,
        ]);
    }

    public function update_role(Request $request, $role_id)
    {

        $role = Role::find($role_id);
        $userUpdated = $role->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "Role Updated Successfully",
            'role' =>  $role,
        ]);
    }

    public function delete_role($role_id)
    {
        $role = Role::find($role_id);
        $role->delete();

        return response()->json([
            'status' => true,
            'message' => "Role Deleted Successfully",
            'role' =>  $role,
        ]);
    }

    public function roles()
    {
        $roles = Role::all();

        return response()->json([
            'status' => true,
            'message' => "Role Deleted Successfully",
            'roles' => $roles,
        ]);
    }

    public function booked_courses()
    {
        $courses = Course::all();

        return response()->json([
            'status' => true,
            'message' => "All courses",
            'courses' => $courses,
        ]);
    }

    public function course_detail($id)
    {
        $course = Course::with('classes')->find($id);

        return response()->json([
            'status' => true,
            'message' => "Course Detail!",
            'course' => $course,
        ]);
    }

    public function courses_classes()
    {
        $courses = Course::with('classes')->where('status', 'active')->get();

        return response()->json([
            'status' => true,
            'message' => "Courses and classes!",
            'courses' => $courses,
        ]);
    }

    public function course_teachers()
    {
        $teachers = User::with(['course' => function ($q) {
            $q->where('status', 'active');
        }])->where('role_name', 'teacher')->get();

        return response()->json([
            'status' => true,
            'message' => "Teachers with courses!",
            'teacher' => $teachers,
        ]);
    }

    public function teacher_canceledcourses()
    {
        $courses = CanceledCourse::whereHas('user', function ($q) {
            $q->where('role_name', 'teacher');
        })
            ->with('user', 'course')
            ->get();

        return response()->json([
            'status' => true,
            'message' => "Courses Canceled by teacher",
            'courses' => $courses,
        ]);
    }

    public function student_canceledcourses()
    {
        $courses = CanceledCourse::whereHas('user', function ($q) {
            $q->where('role_name', 'student');
        })
            ->with('user', 'course')
            ->get();

        return response()->json([
            'status' => true,
            'message' => "Courses Canceled by student",
            'courses' => $courses,
        ]);
    }

    public function teachers_schedule()
    {
        $teachers = User::with(['teacher_courses.course', 'teacher_courses.course.attendence', 'teacher_courses' => function ($q) {
            $q->where('status', 'active');
            $q->with(['classes' => function ($qu) {
                $qu->where('status', 'scheduled');
            }]);
        }])
            ->where('role_name', 'teacher')->get();

        return response()->json([
            'status' => true,
            'message' => "Teachers Classes Schedule",
            'teachers' => $teachers,
        ]);
    }

    public function students_schedule()
    {
        $students = User::where('role_name', 'student')->get();
        $object = new stdClass();
        $counter = 0;
        foreach ($students as $student) {
            $counter++;
            // $courses = ClassRoom::where('student_id', $student->id)->get();
            $object->student =  $student;
            // if (count($courses) > 0) {
            //     foreach ($courses as $course) {
            //         $object->course = $course;
            //         $classes = AcademicClass::where('course_id', $course->course_id)->where('student_id', $student->id)->where('status', 'scheduled')->get();
            //         $object->classes = $classes;
            //     }
            // } else {
            //     $object->course = null;
            //     $object->classes = [];
            // }
        }

        return response()->json([
            'status' => true,
            'message' => "Student's Classes Schedule",
            'total_students' => count($students),
            'result' => $object,
        ]);
    }

    public function newsletter()
    {
        $newsletters = Newsletter::Paginate(10);

        return response()->json([
            'status' => true,
            'message' => "All Newsletters",
            'newsletters' => $newsletters,
        ]);
    }

    public function del_newsletter($id)
    {
        $newsletter = Newsletter::find($id);
        $newsletter->delete();

        return response()->json([
            'status' => true,
            'message' => "Newsletter Deleted Successfully!",
            'newsletter' => $newsletter,
        ]);
    }



    public function course_feedbacks()
    {
        $user_feedbacks = UserFeedback::with('sender', 'reciever', 'course', 'feedback')->get();

        return response()->json([
            'status' => true,
            'message' => "All User Feedbacks",
            'user_feedbacks' => $user_feedbacks,
        ]);
    }

    public function platform_feedbacks()
    {
        $testimonials = UserTestimonial::with('sender', 'testimonial')->get();

        return response()->json([
            'status' => true,
            'message' => "All User Testimonials",
            'user_testimonials' => $testimonials,
        ]);
    }

    public function del_coursefeedback(Request $request)
    {
        $rules = [
            'sender_id' =>  'required|integer',
            'course_id' =>  'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ]);
        }
        $user_feedbacks = UserFeedback::where('sender_id', $request->sender_id)->where('course_id', $request->course_id)->get();

        foreach ($user_feedbacks as $user_feedback) {
            $user_feedback->delete();
        }

        return response()->json([
            'status' => true,
            'message' => "User Feedback deleted Successfully!",
        ]);
    }

    public function del_usertestimonial($sender_id)
    {
        $user_testimonial = UserTestimonial::where('sender_id', $sender_id)->get();

        foreach ($user_testimonial as $user_test) {
            $user_test->delete();
        }

        return response()->json([
            'status' => true,
            'message' => "User Testimonial deleted Successfully!",
        ]);
    }

    public function teacher_ratings()
    {
        $teachers = User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar', 'kudos_points')->where('role_name', 'teacher')->get();

        foreach ($teachers as $teacher) {
            $rating_sum = 0;
            $average_rating = 0;
            $rating_sum = UserFeedback::where('reciever_id', $teacher->id)->sum('rating');
            if ($rating_sum > 0) {
                $total_reviews = UserFeedback::where('reciever_id', $teacher->id)->count();
                $average_rating = $rating_sum / $total_reviews;
            }

            $teacher->average_rating = $average_rating;
        }

        return response()->json([
            'status' => true,
            'message' => "Teachers rating!",
            'teachers' => $teachers,
        ]);
    }

    public function reassign_teacher(Request $request)
    {
        $rules = [
            'course_id' =>  'required|integer',
            'teacher_id' =>  'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ]);
        }

        $classes = AcademicClass::where('course_id', $request->course_id)->where('status', 'canceled')->get();

        foreach ($classes as  $class) {
            $class->teacher_id = $request->teacher_id;
            $class->status = 'scheduled';
            $class->update();
        }

        $class_rooms = ClassRoom::where('course_id', $request->course_id)->get();
        foreach ($class_rooms as  $class_room) {
            $class_room->teacher_id = $request->teacher_id;
            $class_room->status = 'active';
            $class_room->update();
        }

        $course = Course::find($request->course_id);
        $course->teacher_id = $request->teacher_id;
        $course->status = 'active';
        $course->update();

        $course = Course::with('classes')->find($request->course_id);

        return response()->json([
            'status' => true,
            'message' => "Teacher Assigned Successfully!",
            'course' => $course,
        ]);
    }

    public function reassignTeacher(Request $request)
    {
        $rules = [
            'course_id' =>  'required|integer',
            'teacher_id' =>  'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ]);
        }

        $classes = AcademicClass::where('course_id', $request->course_id)->where('status', 'rejected')->get();
        $course = Course::find($request->course_id);

        $counter = 1;
        foreach ($classes as  $class) {

            if ($counter == 1) {
                $class->title = "Introduction|class1";
                $class->lesson_name = "introduction";
            } else {
                $class->title = "class" . $counter;
                $class->lesson_name = "lesson" . $counter;
            }



            /// Curl Implementation
            $apiURL = 'https://api.braincert.com/v2/schedule';
            $postInput = [
                'apikey' => 'PU0MLbUZrGbmonA3PHny',
                'title' =>  'Introduction|class1',
                'timezone' => 90,
                'start_time' => $class->start_time,
                'end_time' => $class->end_time,
                'date' => $class->start_date,
                'currency' => "USD",
                'ispaid' => null,
                'is_recurring' => 0,
                'repeat' => 1,
                'weekdays' => $course->weekdays,
                'end_date' => $class->end_date,
                'seat_attendees' => null,
                'record' => 0,
                'isRecordingLayout ' => 1,
                'isVideo  ' => 1,
                'isBoard ' => 0,
                'isLang ' => null,
                'isRegion ' => null,
                'isCorporate ' => null,
                'isScreenshare ' => 1,
                'isPrivateChat  ' => 0,
                'description ' => null,
                'keyword ' => null,
                'format ' => "json",
            ];

            $client = new Client();
            $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody(), true);
            if ($responseBody['status'] == "ok") {
                $class->class_id = $responseBody['class_id'];
                $class->status = "scheduled";
                $class->teacher_id = $request->teacher_id;
                $class->update();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $responseBody['error'],
                ], 400);
            }
            $counter++;
        }

        $class_rooms = ClassRoom::where('course_id', $request->course_id)->get();
        foreach ($class_rooms as  $class_room) {
            $class_room->teacher_id = $request->teacher_id;
            $class_room->status = 'active';
            $class_room->update();
        }


        $course->teacher_id = $request->teacher_id;
        $course->status = 'active';
        $course->update();

        $course = Course::with('classes')->find($request->course_id);

        return response()->json([
            'status' => true,
            'message' => "Teacher Assigned Successfully!",
            'course' => $course,
        ]);
    }

    public function teachers(Request $request)
    {
        $teachers = User::where('role_name', 'teacher')->where('admin_approval', 'approved')->get();
        $message = 'All teachers!';

        if ($request->has('inactive')) {
            $teachers = User::where('role_name', 'teacher')->where('status', 'pending')->get();
            $message = 'All Inactive teachers!';
        }
        if ($request->has('active')) {
            $teachers = User::where('role_name', 'teacher')->where('status', 'active')->get();
            $message = 'All Active teachers!';
        }
        if ($request->has('suspended')) {
            $teachers = User::where('role_name', 'teacher')->where('status', 'inactive')->get();
            $message = 'All Suspended teachers!';
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'teachers' => $teachers,
        ]);
    }

    public function rejected_teachers()
    {
        $teachers = User::where('role_name', 'teacher')->where('admin_approval', 'rejected')->get();

        return response()->json([
            'status' => true,
            'message' => "Rejected Teachers!",
            'teachers' => $teachers,

        ]);
    }

    public function pending_teachers()
    {
        $teachers = User::where('role_name', 'teacher')->where('admin_approval', 'pending')->orWhere('admin_approval', null)->where('status', 'pending')->get();

        return response()->json([
            'status' => true,
            'message' => "Pending Teachers!",
            'teachers' => $teachers,

        ]);
    }

    public function current_teachers()
    {
        $teachers = User::where('role_name', 'teacher')->where('status', 'active')->where('admin_approval', 'approved')->get();

        return response()->json([
            'status' => true,
            'message' => "Current Teachers!",
            'teachers' => $teachers,

        ]);
    }


    public function schedule_meeting(Request $request){

          $rules = [
            'interview_request_id' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ];
        
        $validator=Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            
            return response()->json([
                
                'status' => 'false',
                'errors' => $errors,
                ],400) ;
           
        }

        // return $request->all();

        $int=TeacherInterviewRequest::find($request->interview_request_id);


        $apiURL = 'https://api.braincert.com/v2/schedule';
        $postInput = [
            'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
            'title' =>  'Interview with teacher',
            'timezone' => 90,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'date' => $request->date,
            'currency' => "USD",
            'record' => 1,
            'isRecordingLayout ' => 1,
            'isVideo  ' => 1,
            'isBoard ' => 0,
            'isScreenshare ' => 1,
            'isPrivateChat  ' => 0,
            'format ' => "json",
        ];

        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);
        if ($responseBody['status'] == "ok") {
          
            $int->meeting_id = $responseBody['class_id'];
            $int->status = "scheduled";
           
            $int->update();
          
            return response()->json([
                'success' => true,
                'message' => "Meeting Scheduled Successfully",
                
            ]);
        } else {

             return response()->json([
                'success' => true,
                'message' => $responseBody,
                
            ],400);
            
        }

        return $int;


    }


    public function join_meeting(Request $request,$id){

          $int=TeacherInterviewRequest::find($request->interview_request_id);


        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($int == null) {
            return response()->json([
                'status' => false,
                'message' => 'meeting not found'
            ], 400);
        }

        if ($token_user->role_name == "admin") {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $apiURL = 'https://api.braincert.com/v2/getclasslaunch';
        $postInput = [
            'apikey' => 'xKUyaLJHtbvBUtl3otJc',
            'class_title' =>  'Admin Interview',
            'class_id' => $int->meeting_id,
            'userId' => $token_user->id,
            'userName' => $token_user->first_name . " " . $token_user->last_name,
            'isTeacher' => $flag,
            'lessonName' => 'interview',
            'courseName' => "hiring",
        ];

        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody['status'] == 'error') {
            return response()->json([
                'status' => false,
                'message' => $responseBody['error'],
                'error' => $responseBody['error'],
            ], 400);
        } else {
          

            return response()->json([
                'status' => true,
                'meeting_url' => $responseBody['launchurl'],
            ]);
        }

    }




}
