<?php

namespace App\Http\Controllers;

use App\Events\CancelCourse;
use App\Models\AcademicClass;
use App\Models\CanceledCourse;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Newsletter;
use App\Models\RescheduleClass;
use App\Models\Role;
use App\Models\Testimonial;
use App\User;
use App\Models\UserFeedback;
use App\Models\UserTestimonial;
use App\Subject;
use App\FieldOfStudy;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use stdClass;
use App\TeacherInterviewRequest;
use App\TeacherSubject;
use Carbon\Carbon;

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
        $course = Course::with('teacher', 'program', 'field')
            ->withCount('students')
            ->withCount(['remaining_classes' => function ($q) {
                $q->where('status', '!=', 'completed');
            }])
            ->withCount(['completed_classes' => function ($q) {
                $q->where('status', 'completed');
            }])
            ->findOrFail($id);

        $rating =

            $completed_classes = [];
        $remaining_classes = [];

        $course1 = Course::findOrFail($id);

        foreach ($course1->classes as $class) {
            if ($class->status == 'completed') {
                $class->topic;
                $class->attendees;
                array_push($completed_classes, $class);
            } else {
                $class->attendees;
                $class->topic;
                array_push($remaining_classes, $class);
            }
        }

        $progress = ($course->completed_classes_count / $course->total_classes) * 100;

        $average_rating = 5.0;
        $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
        $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
        if ($total_reviews > 0) {
            $average_rating = $rating_sum / $total_reviews;
        }

        return response()->json([
            'status' => true,
            'message' => "Course Detail!",
            'course_progress' => $progress,
            'teacher_rating' => $average_rating,
            'course' => $course,
            'remaining_classes' => $remaining_classes,
            'completed_classes' => $completed_classes,
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
        $courses = CanceledCourse::with('teacher', 'student', 'course')
            ->where('cancelled_by', 'teacher')
            ->get();

        //calculating the ratings
        foreach ($courses as $course) {
            #for student
            $student_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->student_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->student_id)->count();
            if ($total_reviews > 0) {
                $student_rating = $rating_sum / $total_reviews;
            }
            $course->student_rating = $student_rating;

            #for teacher
            $teacher_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
            if ($total_reviews > 0) {
                $teacher_rating = $rating_sum / $total_reviews;
            }
            $course->teacher_rating = $teacher_rating;
        }

        return response()->json([
            'status' => true,
            'message' => "Courses Canceled by teacher!",
            'canceled_courses' => $courses,
        ]);
    }

    public function student_canceledcourses()
    {
        $courses = CanceledCourse::with('teacher', 'student', 'course')
            ->where('cancelled_by', 'student')
            ->get();

        //calculating the ratings
        foreach ($courses as $course) {
            #for student
            $student_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->student_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->student_id)->count();
            if ($total_reviews > 0) {
                $student_rating = $rating_sum / $total_reviews;
            }
            $course->student_rating = $student_rating;

            #for teacher
            $teacher_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
            if ($total_reviews > 0) {
                $teacher_rating = $rating_sum / $total_reviews;
            }
            $course->teacher_rating = $teacher_rating;
        }

        return response()->json([
            'status' => true,
            'message' => "Courses Canceled by student!",
            'canceled_courses' => $courses,
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
        $feedbacks = UserFeedback::whereHas('sender', function ($q) {
            $q->where('role_name', 'student');
        })
            ->get();


        return response()->json([
            'status' => true,
            'message' => "Students Feedbacks",
            'user_feedbacks' => $feedbacks,
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
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

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
            $class->status = 'reassigned_by_admin';
            $class->update();
        }

        $class_rooms = ClassRoom::where('course_id', $request->course_id)->get();
        foreach ($class_rooms as  $class_room) {
            $class_room->teacher_id = $request->teacher_id;
            $class_room->status = 'reassigned_by_admin';
            $class_room->update();
        }

        $course = Course::find($request->course_id);
        $course->teacher_id = $request->teacher_id;
        $course->status = 'reassigned_by_admin';
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
                'apikey' => 'xKUyaLJHtbvBUtl3otJc',
                'title' =>  'Introduction|class1',
                'timezone' => 90,
                'start_time' => $class->start_time,
                'end_time' => $class->end_time,
                'date' => $class->start_date,
                'currency' => "USD",
                'ispaid' => null,
                'is_recurring' => 0,
                'repeat' => 0,
                'weekdays' => $course->weekdays,
                'end_date' => $class->end_date,
                'seat_attendees' => null,
                'record' => 0,
                'isRecordingLayout ' => 1,
                'isVideo  ' => 1,
                'isBoard ' => 1,
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


        $interview = TeacherInterviewRequest::pluck("user_id");
        $teachers = User::with('country', 'teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject', 'teacher_interview_request')->whereIn('id', $interview)->get();

        $all_teachers = User::whereIn('id', $interview)->count();
        $active_teachers = User::where('status', 'active')->where('admin_approval', 'approved')->whereIn('id', $interview)->count();
        $inactive_teachers = User::where('status', 'inactive')->where('admin_approval', 'approved')->whereIn('id', $interview)->count();
        $pending_teachers = User::where('status', 'pending')->whereIn('id', $interview)->count();
        $suspended_teachers = User::where('status', 'suspended')->whereIn('id', $interview)->count();

        $message = 'All teachers!';

        // if ($request->has('inactive')) {
        //     $teachers = User::where('role_name', 'teacher')->where('status', 'pending')->get();
        //     $message = 'All Inactive teachers!';
        // }
        // if ($request->has('active')) {
        //     $teachers = User::where('role_name', 'teacher')->where('status', 'active')->get();
        //     $message = 'All Active teachers!';
        // }
        // if ($request->has('suspended')) {
        //     $teachers = User::where('role_name', 'teacher')->where('status', 'inactive')->get();
        //     $message = 'All Suspended teachers!';
        // }

        return response()->json([
            'status' => true,
            'message' => $message,
            'teachers' => $teachers,
            'all_teachers' => $all_teachers,
            'active_teachers' => $active_teachers,
            'inactive_teachers' => $inactive_teachers,
            'pending_teachers' => $pending_teachers,
            'suspended_teachers' => $suspended_teachers,
        ]);
    }

    public function rejected_teachers()
    {
        $teachers = User::with('teacher_interview_request')->where('role_name', 'teacher')->where('status', 'rejected')->get();

        return response()->json([
            'status' => true,
            'message' => "Rejected Teachers!",
            'teachers' => $teachers,

        ]);
    }

    public function suspended_teachers()
    {
        $teachers = User::with('teacher_interview_request')->where('role_name', 'teacher')->where('status', 'suspended')->get();

        return response()->json([
            'status' => true,
            'message' => "Suspended Teachers!",
            'teachers' => $teachers,

        ]);
    }

    public function pending_teachers(Request $request)
    {

        $interview = TeacherInterviewRequest::where('status', 'pending')->pluck("user_id");
        $pending_teachers = User::with('teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject', 'teacher_interview_request')
            ->whereIn('id', $interview)
            ->get();



        $rejected_teachers = User::with('teacher_interview_request')
            ->where('role_name', 'teacher')
            ->where('status', 'rejected')
            ->get();

        return response()->json([
            'status' => true,
            'message' => "Rejected Teachers!",
            'pending_teachers_count' => count($pending_teachers),
            'pending_teachers' => $pending_teachers,
            'rejected_teachers_count' => count($rejected_teachers),
            'rejected_teachers' => $rejected_teachers,

        ]);
    }

    public function current_teachers(Request $request)
    {
        $teachers = User::with('teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject')
            ->where('role_name', 'teacher')
            ->whereIn('status', ['active', 'inactive'])
            ->get();
        $pluckedteachers = $teachers->pluck('id');

        if ($request->has('active')) {
            $teachers = User::with('teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject')
                ->where('role_name', 'teacher')
                ->where('status', 'active')
                ->get();
            $pluckedteachers = $teachers->pluck('id');
        }
        if ($request->has('inactive')) {
            $teachers = User::with('teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject')
                ->where('role_name', 'teacher')
                ->where('status', 'inactive')
                ->get();
            $pluckedteachers = $teachers->pluck('id');
        }

        $courses = Course::all();
        $running_courses = $courses->whereIn('status', ['pending', 'active', 'inprogress'])->whereIn('teacher_id', $pluckedteachers);
        $running_teachers = $running_courses->unique('teacher_id');
        $runing_teachers = [];
        $engaged_teachers = [];
        foreach ($running_teachers as $teacher) {
            array_push($runing_teachers, $teacher->teacher_id);
            array_push($engaged_teachers, $teacher);
        }


        $available_teachers = [];
        foreach ($teachers as $teacher) {
            if (!(in_array($teacher->id, $runing_teachers))) {
                array_push($available_teachers, $teacher);
            }
        }

        $inactive_teachers = User::where('role_name', 'teacher')->where('status', 'inactive')->get();

        //calculating the ratings
        foreach ($teachers as $teacher) {
            #for teacher
            $teacher_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $teacher->id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $teacher->id)->count();
            if ($total_reviews > 0) {
                $teacher_rating = $rating_sum / $total_reviews;
            }

            $bookings = Course::where('teacher_id', $teacher->id)->get();

            $teacher->teacher_rating = $teacher_rating;
            $teacher->bookings = count($bookings);
            $teacher->amount = $bookings->sum('total_price');
        }


        if ($request->has('active') || $request->has('inactive')) {
            return response()->json([
                'status' => true,
                'message' => "Current Teachers",
                'total' => count($teachers),
                'available' => count($available_teachers),
                'engaged' => count($engaged_teachers),
                // 'inactive' => count($inactive_teachers),
                'teachers' => $teachers,

            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Current Teachers",
                'total' => count($teachers),
                'available' => count($available_teachers),
                'engaged' => count($engaged_teachers),
                'inactive' => count($inactive_teachers),
                'teachers' => $teachers,

            ]);
        }
    }


    public function schedule_meeting(Request $request)
    {

        $rules = [
            'interview_request_id' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([

                'status' => 'false',
                'errors' => $errors,
            ], 400);
        }

        // return $request->all();

        $int = TeacherInterviewRequest::find($request->interview_request_id);


        $apiURL = 'https://api.braincert.com/v2/schedule';
        $postInput = [
            'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
            'title' =>  'Interview with teacher',
            'timezone' => 90,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'date' => $request->date,
            'currency' => "USD",
            'record' => 0,
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
            $int->time_for_interview = $request->start_time;
            $int->date_for_interview = $request->date;

            $int->update();

            return response()->json([
                'success' => true,
                'message' => "Meeting Scheduled Successfully",

            ]);
        } else {

            return response()->json([
                'success' => true,
                'message' => $responseBody,

            ], 400);
        }

        return $int;
    }


    public function join_meeting(Request $request, $id)
    {

        $int = TeacherInterviewRequest::find($request->interview_request_id);


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

    public function subject_teachers($subject_id)
    {
        $subject_teachers = TeacherSubject::where('subject_id', $subject_id)->pluck("user_id");

        $teachers = User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar')->whereIn('id', $subject_teachers)->get();

        return response()->json([
            'status' => true,
            'message' => "Active Teachers related to Subject!",
            'teachers_count' => count($teachers),
            'teachers' => $teachers,
        ]);
    }

    public function subject_activeclasses($subject_id)
    {
        // $subject_courses = Course::whereHas('classes', function ($q) {
        //     $q->where('status', 'inprogress');
        // })->with('classes', function ($q) {
        //     $q->where('status', 'inprogress');
        // })->where('subject_id', $subject_id)->get();

        $subject_courses = Course::where('subject_id', $subject_id)->pluck('id');

        $classes = AcademicClass::where('status', 'inprogress')->whereIn('course_id', $subject_courses)->get();

        return response()->json([
            'status' => true,
            'message' => "Active Classes related to Subject!",
            'classes_count' => count($classes),
            'classes' => $classes,
        ]);
    }

    public function subject_upcomingclasses($subject_id)
    {
        $subject_courses = Course::where('subject_id', $subject_id)->pluck('id');

        $classes = AcademicClass::where('status', 'scheduled')->whereIn('course_id', $subject_courses)->get();

        return response()->json([
            'status' => true,
            'message' => "Upcoming Classes related to Subject!",
            'classes_count' => count($classes),
            'classes' => $classes,
        ]);
    }

    public function subject_canceledclasses($subject_id)
    {
        // $subject_canceled_classes = Course::whereHas('canceled_classes')->with('canceled_classes')->where('subject_id', $subject_id)->get();

        $subject_courses = Course::where('subject_id', $subject_id)->pluck('id');

        $classes = CanceledCourse::whereIn('course_id', $subject_courses)->get();
        return response()->json([
            'status' => true,
            'message' => "Canceled Classes related to Subject!",
            'classes_count' => count($classes),
            'classes' => $classes,
        ]);
    }

    public function subject_rescheduledclasses($subject_id)
    {
        $subject_courses = Course::where('subject_id', $subject_id)
            ->pluck('id');

        $classes = RescheduleClass::whereIn('course_id', $subject_courses)->get();
        return response()->json([
            'status' => true,
            'message' => "Rescheduled Classes related to Subject!",
            'classes_count' => count($classes),
            'classes' => $classes,
        ]);
    }

    public function program_subjects(Request $request, $program_id)
    {

        $subjects = Subject::with('program', 'country', 'field')->where('program_id', $program_id)->get();
        $field_of_studies = FieldOfStudy::with('program', 'country')->where('program_id', $program_id)->get();

        if ($program_id == 3) {
            $rules = [
                'country_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->messages();
                $errors = $messages->all();

                return response()->json([

                    'status' => 'false',
                    'errors' => $errors,
                ], 400);
            }

            $country_id = $request->country_id;
            $subjects = Subject::with('program', 'country', 'field')->where('program_id', $program_id)->where('country_id', $country_id)->get();
            $field_of_studies = FieldOfStudy::with('program', 'country')->where('program_id', $program_id)->where('country_id', $country_id)->get();
        }

        return response()->json([
            'status' => true,
            'subjects' => $subjects,
            'field_of_studies' => $field_of_studies,
        ]);
    }

    public function workforce_capacity(Request $request)
    {
        $subjects_array = [];
        $courses_subjects = Course::pluck('subject_id')->all();
        $courses = Course::all();
        $final_subjects = [];
        //----- Workforce Filters starts -------
        $current_date = Carbon::now()->format('Y-m-d');
        if ($request->has('week')) {
            $endDate = Carbon::today()->subWeek($request->week);
            $courses_subjects = Course::whereBetween('created_at', [$endDate, $current_date])->pluck('subject_id')->all();
            $courses = Course::whereBetween('created_at', [$endDate, $current_date])->get();
        }
        if ($request->has('month')) {
            $endDate = Carbon::today()->subMonth($request->month);
            $courses_subjects = Course::whereBetween('created_at', [$endDate, $current_date])->pluck('subject_id')->all();
            $courses = Course::whereBetween('created_at', [$endDate, $current_date])->get();
        }
        if ($request->has('year')) {
            $endDate = Carbon::today()->subYear($request->year);
            $courses_subjects = Course::whereBetween('created_at', [$endDate, $current_date])->pluck('subject_id')->all();
            $courses = Course::whereBetween('created_at', [$endDate, $current_date])->get();
        }
        if ($request->has('teacher')) {
            $courses_subjects = Course::where('teacher_id', $request->teacher)->pluck('subject_id')->all();
            $courses = Course::where('teacher_id', $request->teacher)->get();
        }
        // if ($request->has('date')) {
        //     $date = Carbon::parse($request->date);
        //     $courses_subjects = Course::where('created_at',  $date)->pluck('subject_id')->all();
        //     return $courses = Course::where('created_at', 'LIKE', '%$date%')->get();
        // }
        //------- Workforce Filters ends -------



        foreach (array_unique($courses_subjects) as $subject) {
            $hired_teachers = 0;
            array_push($subjects_array, $subject);

            $hired_teachers = $courses->where('subject_id', $subject)->pluck('teacher_id')->unique()->count();

            $Subject = Subject::with('program', 'field')
                ->withCount(['available_teachers' => function ($q) use ($subject) {
                    $q->where('subject_id', $subject);
                }])
                ->find($subject);
            $capacity = 100;
            if ($Subject->available_teachers_count > 0 && $hired_teachers > 0) {
                $capacity = 100 - (($hired_teachers / $Subject->available_teachers_count) * 100);
            }


            array_push($final_subjects, [
                "subject" => $Subject,
                'total_bookings' => $courses->where('subject_id', $subject)->count(),
                'total_revenue' => $courses->where('subject_id', $subject)->sum('total_price'),
                'hired_tutors' => $hired_teachers,
                'capacity' => $capacity,
            ]);
        }

        // $teacher_subjects = TeacherSubject::whereIn('subject_id', $subjects_array)->get();

        return response()->json([
            'status' => true,
            // 'subjects' => $subjects_array,
            'subjects' => $final_subjects,
        ]);
    }

    public function available_teachers($subject_id)
    {
        $subject = Subject::with(['available_teachers' => function ($q) use ($subject_id) {
            $q->where('subject_id', $subject_id);
        }])
            ->find($subject_id);

        $hired_teachers = Course::where('subject_id', $subject_id)->whereIn('status', ['active', 'pending', 'reassigned', 'inprogress'])->pluck('teacher_id')->toArray();
        $teachers = $subject['available_teachers']->pluck('user_id');

        $teachers_available = [];
        foreach ($teachers as $teacher) {
            if (!(in_array($teacher, $hired_teachers))) {
                array_push($teachers_available, $teacher);
            }
        }
        $available_teachers = User::whereIn('id', $teachers_available)->get();

        return response()->json([
            'status' => true,
            'message' => 'Available teachers!',
            'teachers' => $available_teachers,
        ]);
    }

    public function hired_teachers($subject_id)
    {
        $hired_teachers = Course::where('subject_id', $subject_id)->whereIn('status', ['active', 'pending', 'reassigned', 'inprogress'])->pluck('teacher_id');
        $teachers = User::whereIn('id', $hired_teachers)->get();

        return response()->json([
            'status' => true,
            'message' => 'Hired teachers!',
            'teachers' => $teachers,
        ]);
    }

    public function subject_bookings($subject_id)
    {
        $subject = Subject::with(['courses'  => function ($q) {
            $q->with('teacher');
            // $q->where('status', 'active')->with('teacher');
        }])
            ->findorfail($subject_id);

        return response()->json([
            'status' => true,
            'message' => "All Bookings of given Subject!",
            'subject' => $subject,
        ]);
    }

    public function classroom(Request $request)
    {
        $courses = Course::with('student', 'teacher', 'program')
            ->get();

        if ($request->has('running')) {
            $courses = Course::with('student', 'teacher', 'program')->where('status', 'inprogress')->get();
        }
        if ($request->has('completed')) {
            $courses = Course::with('student', 'teacher', 'program')->where('status', 'completed')->get();
        }


        //calculating the ratings
        foreach ($courses as $course) {
            #for student
            $student_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->student_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->student_id)->count();
            if ($total_reviews > 0) {
                $student_rating = $rating_sum / $total_reviews;
            }
            $course->student_rating = $student_rating;

            #for teacher
            $teacher_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
            if ($total_reviews > 0) {
                $teacher_rating = $rating_sum / $total_reviews;
            }
            $course->teacher_rating = $teacher_rating;
        }

        $completed_courses = $courses->where('status', 'completed')->count();
        $running_courses = $courses->where('status', 'inprogress')->count();
        $cancelled_courses = CanceledCourse::all();

        if ($request->has('running') || $request->has('completed')) {
            return response()->json([
                'status' => true,
                'message' => "Bookings",
                'courses' => $courses,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => "Bookings",
            'all_courses' => count($courses),
            'completed_courses' => $completed_courses,
            'running_courses' => $running_courses,
            'cancelled_courses' => count($cancelled_courses),
            'courses' => $courses,
        ]);
    }

    public function student_feedbacks($course_id)
    {
        $course = Course::findOrFail($course_id);
        $user_feedbacks = UserFeedback::with('feedback', 'sender', 'reciever')
            ->where('receiver_id', $course->teacher_id)
            ->where('sender_id', '!=', $course->teacher_id)
            ->where('course_id', $course->id)
            ->get();


        $feedbacks_count = $user_feedbacks->pluck('sender_id')->unique()->count();

        foreach ($user_feedbacks as $user_feedback) {
            $rating_sum = $user_feedbacks->where('sender_id', $user_feedback->sender_id)->sum('rating');
            $total_reviews = $user_feedbacks->where('sender_id', $user_feedback->sender_id)->count();
            $average_rating = $rating_sum / $total_reviews;
            $user_feedback->average_rating = $average_rating;
        }

        $five_stars = $user_feedbacks->where('rating', 5)->count();
        $four_stars = $user_feedbacks->where('rating', 4)->count();
        $three_stars = $user_feedbacks->where('rating', 3)->count();
        $two_stars = $user_feedbacks->where('rating', 2)->count();
        $one_stars = $user_feedbacks->where('rating', 1)->count();

        $feedback_id_1 = $user_feedbacks->where('feedback_id', 1)->sum('rating');
        $feedback_id_1 = $feedback_id_1 /  $user_feedbacks->where('feedback_id', 1)->count();

        $feedback_id_2 = $user_feedbacks->where('feedback_id', 2)->sum('rating');
        $feedback_id_2 = $feedback_id_2 /  $user_feedbacks->where('feedback_id', 1)->count();

        $feedback_id_3 = $user_feedbacks->where('feedback_id', 3)->sum('rating');
        $feedback_id_3 = $feedback_id_3 /  $user_feedbacks->where('feedback_id', 1)->count();

        $feedback_id_4 = $user_feedbacks->where('feedback_id', 4)->sum('rating');
        $feedback_id_4 = $feedback_id_4 /  $user_feedbacks->where('feedback_id', 1)->count();

        $feedbacks = [];
        $user_feedbacks = $user_feedbacks->groupBy('sender_id');
        foreach ($user_feedbacks as $user_feedback) {
            array_push($feedbacks, $user_feedback);
        }
        return response()->json([
            'status' => true,
            'message' => "Student feedback on Course!",
            'feedbacks_count' => $feedbacks_count,
            'five_stars' => $five_stars,
            'four_stars' => $four_stars,
            'three_stars' => $three_stars,
            'two_stars' => $two_stars,
            'one_stars' => $one_stars,
            'Expert in the subject' => $feedback_id_1,
            'Present Complex Topics clearly and easily' => $feedback_id_2,
            'Skillfull in engaging students' => $feedback_id_3,
            'Always on time' => $feedback_id_4,
            'student_feedbacks' => $feedbacks,
        ]);
    }

    public function previous_teachers($course_id)
    {
        $canceled_courses = CanceledCourse::with('teacher')->where('course_id', $course_id)->get();
        $previous_teachers = [];
        foreach ($canceled_courses as $teacher) {
            array_push($previous_teachers, $teacher['teacher']);
        }

        $previous_teachers = array_unique($previous_teachers);
        return response()->json([
            'status' => true,
            'message' => "Previous teachers!",
            'previous_teachers' => $previous_teachers,
        ]);
    }

    public function cancelledCourses(Request $request)
    {
        $course = Course::find(7);
        return $cancelled_classes = $course->cancelled_classes(7);

        $courses = CanceledCourse::with('teacher', 'student', 'course')
            ->get();
        $by_teachers =  $courses->where('cancelled_by', 'teacher')->count();
        $by_students =  $courses->where('cancelled_by', 'student')->count();
        $by_admins =  $courses->where('cancelled_by', 'admin')->count();

        if ($request->has('student')) {
            $courses = CanceledCourse::with('teacher', 'student', 'course')
                ->where('cancelled_by', 'student')
                ->get();
        }
        if ($request->has('teacher')) {
            $courses = CanceledCourse::with('teacher', 'student', 'course')
                ->where('cancelled_by', 'teacher')
                ->get();
        }
        if ($request->has('admin')) {
            $courses = CanceledCourse::with('teacher', 'student', 'course')
                ->where('cancelled_by', 'admin')
                ->get();
        }

        //calculating the ratings
        foreach ($courses as $course) {
            #for student
            $student_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->student_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->student_id)->count();
            if ($total_reviews > 0) {
                $student_rating = $rating_sum / $total_reviews;
            }
            $course->student_rating = $student_rating;

            #for teacher
            $teacher_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
            if ($total_reviews > 0) {
                $teacher_rating = $rating_sum / $total_reviews;
            }
            $course->teacher_rating = $teacher_rating;
        }




        if ($request->has('admin') || $request->has('teacher') || $request->has('student')) {
            return response()->json([
                'status' => true,
                'message' => "Cancelled Courses!",
                'cancelled_courses' => $courses,
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Cancelled Courses!",
                'total' => count($courses),
                'by_teachers' => $by_teachers,
                'by_students' => $by_students,
                'by_admins' => $by_admins,
                'cancelled_courses' => $courses,
            ]);
        }
    }

    public function teacher_status(Request $request)
    {
        $rules = [
            'teacher_id' =>  'required|integer',
            'status' =>  'required|string',
            'reason' =>  'required|string',
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

        $courses = Course::where('teacher_id', $request->teacher_id)
            ->whereIn('status', ['active', 'inprogress'])
            ->get();

        //if teacher has active courses
        if (count($courses) > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Access Denied! Please assign these courses to someone else.',
                'courses' => $courses,
            ], 400);
        } else {
            $teacher = User::findOrFail($request->teacher_id);
            $teacher->status = $request->status;
            $teacher->update();
            return response()->json([
                'status' => true,
                'message' => 'Status updated successfully! ',
                'teacher' => $teacher,
            ]);
        }
    }
}
