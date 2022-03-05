<?php

namespace App\Http\Controllers;

use App\FieldOfStudy;
use App\Models\AcademicClass;
use App\Models\Course;
use App\Models\ClassSession;
use App\Models\Feedback;
use App\Models\UserFeedback;
use App\User;
use App\Program;
use App\Subject;
use App\TimeZone;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JWTAuth;

class ClassController extends Controller
{
    //********* Schedule a Calss *********
    public function create_class(Request $request)
    {
        $rules = [
            'field_of_study' =>  'required',
            'course_level' =>  'required',
            'language_id' =>  'required',
            // 'book_type' =>  'required',
            'book_info' =>  'required',
            'total_classes' =>  'required',
            'total_hours' =>  'required',
            'total_price' =>  'required',
            'subject_id' =>  'required',
            'weekdays' =>  'required',

            'teacher_id' =>  'required',
            'start_date' =>  'required',
            'end_date' =>  'required',
            'class_type' =>  'required',
            'classes' =>  'required',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ],400);
        }

        if ($request->program_id == 3) {
            $request->validate([
                'country_id' =>  'required',
            ]);
        }

        //*********** Saving records to Courses table ***********
        $course = new Course();
        if ($request->program_id == 3) {
            $course->program_id = $request->program_id;
            $course->country_id = $request->country_id;
        } else {
            $course->program_id = $request->program_id;
        }
        if ($request->book_info == 2) {
            $request->validate([
                'files' =>  'required',
            ]);
            $course->book_info = $request->book_info;
            if ($request->hasFile('files')) {
                //************* book files **********\\
                $images = array();
                $files = $request->file('files');
                foreach ($files as $file) {
                    $imageName = date('YmdHis') . random_int(10, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('assets/images/class_files'), $imageName);
                    $images[] = $imageName;
                }
                $course->files = implode("|", $images);
                //************* book files ends **********\\
            }
        }
        if ($request->book_info == 3) {
            $request->validate([
                'book_name' =>  'required',
                'book_edition' =>  'required',
                'book_author' =>  'required',
            ]);
            $course->book_info = $request->book_info;
            $course->book_name = $request->book_name;
            $course->book_edition = $request->book_edition;
            $course->book_author = $request->book_author;
        } else {
            $course->book_info = $request->book_info;
        }

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $course->field_of_study = $request->field_of_study;
        $course->course_level = $request->course_level;
        $course->language_id = $request->language_id;
        // $course->book_type = $request->book_type;
        $course->total_price = $request->total_price;
        $course->total_hours = $request->total_hours;
        $course->total_classes = $request->total_classes;
        $course->subject_id = $request->subject_id;
        $course->teacher_id = $request->teacher_id;
        $course->student_id = $token_user->id;
        $course->weekdays = $request->weekdays;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->save();

        $classes = $request->classes;
        $classes = json_decode($classes);
        foreach ($classes as $session) {
            $class = new AcademicClass();
            $class->course_id = $course->id;
            $class->teacher_id = $request->teacher_id;
            $class->student_id = $token_user->id;
            $class->start_date = $request->start_date;
            $class->end_date = $request->end_date;
            $class->start_time = $session->start_time;
            $class->end_time = $session->end_time;
            $class->class_type = $request->class_type;
            $class->duration = $session->duration;
            $class->day = $session->day;
            $class->save();
        }
        $program = Program::find($request->program_id);
        $subject = Subject::find($request->subject_id);
        $course_count = Course::where('subject_id', $request->subject_id)->where('program_id', $request->program_id)->count();

        $course = Course::with('subject', 'language', 'field', 'teacher')->find($course->id);
        $course->course_code = $program->code . '-' . Str::limit($subject->name, 3, '-') . ($course_count++);
        $course->course_name = $subject->name . "0001";
        $course->update();

        return response()->json([
            'message' => "Course data added Successfully!",
            'success' => true,
            'class' => $course,
        ]);
    }

    //*********/ Deleting class session *********
    public function del_session(Request $request)
    {
        $rules = [
            'session_id' =>  'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ],400);
        }

        $session = ClassSession::find($request->session_id);
        $session->delete();
        return response()->json([
            'success' => true,
            'message' => "Session has been deleted!",
            'deleted_session' => $session,
        ]);
    }

    //********* Updating class session *********
    public function update_session(Request $request)
    {
        $rules = [
            'session_id' =>  'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ],400);
        }

        $session = ClassSession::find($request->session_id);
        $session->day = $request->day;
        $session->date = $request->date;
        $session->start_time = $request->start_time;
        $session->end_time = $request->end_time;
        $timeDifference = Carbon::parse($request->start_time)->diffInMinutes(Carbon::parse($request->end_time));
        $session->total_hours = round($timeDifference / 60, 2);
        $session->update();


        return response()->json([
            'success' => true,
            'message' => "Session Updated Succesfully!",
            'session' => $session,
        ]);
    }

    //*********/ View Teacher Profile *********
    public function teacher_profile(Request $request)
    {
        // return $request;
        $teacher_id = $request->id;
        $average_feedback = 0;

        $teacher = User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'kudos_points')
            ->with('teacher_qualification', 'teacher_specification', 'spoken_languages', 'teacher_subjects')
            ->with(['feedbacks' => function ($query) use ($teacher_id) {
                $query->where('reciever_id', $teacher_id);
            }])
            // ->with('feedbacks.user')
            ->find($teacher_id);

        $total_feedbacks = UserFeedback::where('reciever_id', $teacher_id)->count();
        if ($total_feedbacks > 0) {
            $total_rating = UserFeedback::where('reciever_id', $teacher_id)->sum('rating');
            $average_feedback = $total_rating / $total_feedbacks;
        }

        $students_teaching=Course::where('teacher_id',$teacher_id)->count();
        $courses_created=Course::where('teacher_id',$teacher_id)->count();

        $expert_rating = 0;
        $expert_rating_total=UserFeedback::where('feedback_id',1)->where('reciever_id',$teacher_id)->count();
        if ($expert_rating_total > 0) {
            $expert_rating_sum=UserFeedback::where('feedback_id',1)->where('reciever_id',$teacher_id)->sum('rating');
            $expert_rating = $expert_rating_sum / $expert_rating_total;
        }

        $complexity_rating = 0;
        $complexity_rating_total = UserFeedback::where('feedback_id',2)->where('reciever_id',$teacher_id)->count();
        if ($complexity_rating_total > 0) {
            $complexity_rating_sum=UserFeedback::where('feedback_id',2)->where('reciever_id',$teacher_id)->sum('rating');
            $complexity_rating = $complexity_rating_sum / $complexity_rating_total;
        }

        $skillfull_rating = 0;
        $skillfull_rating_total = UserFeedback::where('feedback_id',3)->where('reciever_id',$teacher_id)->count();
        if ($skillfull_rating_total > 0) {
            $skillfull_rating_sum=UserFeedback::where('feedback_id',3)->where('reciever_id',$teacher_id)->sum('rating');
            $skillfull_rating = $skillfull_rating_sum / $skillfull_rating_total;
        }

        $onTime_rating = 0;
        $onTime_rating_total = UserFeedback::where('feedback_id',4)->where('reciever_id',$teacher_id)->count();
        if ($onTime_rating_total > 0) {
            $onTime_rating_sum=UserFeedback::where('feedback_id',4)->where('reciever_id',$teacher_id)->sum('rating');
            $onTime_rating = $onTime_rating_sum / $onTime_rating_total;
        }
       
        return response()->json([
            'success' => true,
            'teacher' => $teacher,
            'total_feedbacks' => $total_feedbacks,
            'average_rating' => $average_feedback,
            'students_teaching' => $students_teaching,
            'courses_created' => $courses_created,
            'expert_rating' => $expert_rating,
            'complexity_rating' => $complexity_rating,
            'skillfull_rating' => $skillfull_rating,
            'onTime_rating' => $onTime_rating,
        ]);
    }

    //*********/ Search Teacher *********
    public function search_tutor(Request $request)
    {

        $query = $request->search_query;
        $result = User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'created_at')
            ->with('teacher_subjects', 'teacher_subjects.subject','feedbacks')
            // ->whereHas('feedbacks',function($query){
            //     $query->
            // })
            ->where('role_name', '=', 'teacher')
            ->where('first_name', 'LIKE', "%$query%")
            ->orWhere('last_name', 'LIKE', "%$query%")
            ->orWhere('id', $query)
            ->orWhere('created_at', 'LIKE', "%$query%")
            ->get();

        $average_rating = 0;
        $teacher_id = $request->teacher_id;
        $total_feedbacks = UserFeedback::where('reciever_id', $teacher_id)->count();
        if ($total_feedbacks != 0) {
            $total_rating = UserFeedback::where('reciever_id', $teacher_id)->sum('rating');
            $average_rating = $total_rating / $total_feedbacks;
        }

        return response()->json([
            'success' => true,
            'teachers' => $result,
            // 'average_rating' => $average_rating,
        ]);
    }

    //*********/ Schedule Class *********
    public function schedule_class(Request $request)
    {
        $rules = [
            "academic_class_id" => "required",
            "title" => "required",
            "lesson_name" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ],400);
        }
        /// Curl Implementation
        $class = AcademicClass::find($request->academic_class_id);
        $course = Course::find($class->course_id);

        $apiURL = 'https://api.braincert.com/v2/schedule';
        $postInput = [
            'apikey' =>  "PU0MLbUZrGbmonA3PHny",
            'title' =>  $request->title,
            'timezone' => 90,
            'start_time' => $class->start_time,
            'end_time' => $class->end_time,
            'date' => $class->start_date,
            'currency' => "USD",
            'ispaid' => null,
            'is_recurring' => 1,
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
            $class->title = $request->title;
            $class->lesson_name = $request->lesson_name;
            $class->class_id = $responseBody['class_id'];
            $class->status = "scheduled";
            $class->save();
            return response()->json([
                'success' => true,
                'message' => "Class Scheduled SuccessFully",
                'class' => $class,
            ]);
        } else {
            return $responseBody;
        }
    }


    //*********/ Start Class *********
    public function class_url(Request $request)
    {
        $rules = [
            "class_id" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ],400);
        }
        // return $request;
        $class = AcademicClass::where("class_id", $request->class_id)->first();
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($token_user->role_name == "teacher") {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $apiURL = 'https://api.braincert.com/v2/getclasslaunch';
        $postInput = [
            'apikey' => "PU0MLbUZrGbmonA3PHny",
            'class_title' =>  $class->title,
            'class_id' => $class->class_id,
            'userId' => $token_user->id,
            'userName' => $token_user->first_name . " " . $token_user->last_name,
            'isTeacher' => $flag,
            'lessonName' => $class->lesson_name,
            'courseName' => "laravells",
        ];

        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);

        $class->status = "started";
        $class->update();
        return response()->json([
            'success' => true,
            'class_url' => $responseBody['launchurl'],
        ]);
    }

    //*********/ All Registered Students *********
    public function registered_students()
    {
        $students = User::where("role_name", 'student')->get();
        return response()->json([
            'success' => true,
            'students' => $students,
        ]);
    }

    //*********/ All Registered Teachers *********
    public function registered_teachers()
    {
        $teachers = User::where("role_name", 'teacher')->get();
        return response()->json([
            'success' => true,
            'students' => $teachers,
        ]);
    }

    //*********/ Student Profile *********
    public function student_profile(Request $request)
    {
        $student_id = $request->id;
        $student = User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar', 'created_at')
            ->where('id',  $student_id)
            ->where('role_name', '=', 'student')
            ->first();
        // $total_courses = Course::where('user_id', $student_id)->count();

        return response()->json([
            'success' => true,
            'student' => $student,
            // 'total_courses' => $total_courses
        ]);
    }

    //*********/ Search Student *********
    public function search_student(Request $request)
    {

        $query = $request->search_query;
        $student = (User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'created_at')
            ->where('first_name', 'LIKE', "%$query%")
            ->orWhere('last_name', 'LIKE', "%$query%")
            ->orWhere('id', "%$query%")
            ->orWhere('created_at', 'LIKE', "%$query%")
            ->get()
        )->where('role_name', '=', 'student');

        return response()->json([
            'success' => true,
            'student' => $student,
        ]);
    }

    //*********/ Specific Class details *********
    public function class_detail(Request $request)
    {
        $rules = [
            "academic_class_id" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ],400);
        }

        $class = AcademicClass::with('student', 'teacher', 'course')->find($request->academic_class_id);
        return response()->json([
            'success' => true,
            'classes' => $class,
        ]);
    }

    //*********/  All Courses details *********
    public function courses(Request $request)
    {
        $courses = Course::with('subject', 'language', 'program')->get();
        return response()->json([
            'success' => true,
            'classes' => $courses,
        ]);
    }

    //*********/ Search Course *********
    public function search_course(Request $request)
    {
        $string = $request->search_query;
        $course  = Course::with('student', 'language')->whereHas('field', function ($query) use ($string) {
            $query->where('name', 'like', "%$string%");
        })->orWhere('field_of_study', $request->search_query)->get();

        return response()->json([
            'success' => true,
            'course' => $course,
        ]);
    }

    //*********/ Show ALL Classes *********
    public function show_classes()
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $user_id = $token_user->id;
        $user = User::find($user_id);
        // return $user->role_name;
        if ($user->role_name == "student") {
            $classes = AcademicClass::with('teacher', 'course', 'course.subject')->where('student_id', $user->id)->get();
            return response()->json([
                'success' => true,
                'classes' => $classes,
            ]);
        }
        if ($user->role_name == "teacher") {
            $classes = AcademicClass::with('student', 'course', 'course.subject')->where('teacher_id', $user->id)->get();
            return response()->json([
                'success' => true,
                'classes' => $classes,
            ]);
        }
    }

    //*********/ Specific Course details *********
    public function course_detail(Request $request)
    {
        $rules = [
            "course_id" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ],400);
        }

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $todays_date = Carbon::now()->format('d-M-Y [l]');

        $teacher_id = $token_user->id;
        $current_date = Carbon::today()->format('Y-m-d');

        $course = Course::with('subject', 'language', 'program')->find($request->course_id);

        $todays_classes = AcademicClass::select('title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration')
            ->with('course', 'course.subject')
            ->where('start_date', $current_date)
            ->with('course')
            ->where('teacher_id', $teacher_id)
            ->get();

        $upcoming_classes = AcademicClass::select('title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration')
            ->with('course', 'course.subject')
            ->where('start_date', '>', $current_date)
            ->with('course')
            ->where('teacher_id', $teacher_id)
            ->get();

        $total_upcomingClasses = AcademicClass::where('start_date', '>', $current_date)
            ->where('teacher_id', $teacher_id)
            ->count();

        $past_classes = AcademicClass::select('title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration')
            ->with('course', 'course.subject')
            ->where('start_date', '<', $current_date)
            ->with('course')
            ->where('teacher_id', $teacher_id)
            ->get();

        $total_pastClasses = AcademicClass::where('start_date', '<', $current_date)
            ->where('teacher_id', $teacher_id)
            ->count();

        $remaining_classes=AcademicClass::where('course_id',$request->course_id)->where('status','!=','completed')->count();
        $completed_classes=AcademicClass::where('course_id',$request->course_id)->where('status','completed')->count();

        return response()->json([
            'status' => true,
            'todays_date' =>  $todays_date,
            'course' =>  $course,
            'todays_classes' => $todays_classes,
            'upcoming_classes' => $upcoming_classes,
            'past_classes' => $past_classes,
            'total_pastClasses' => $total_pastClasses,
            'total_upcomingClasses' => $total_upcomingClasses,
            'remaining_classes' => $remaining_classes,
            'completed_classes' => $completed_classes,
        ]);
    }

    //*********/ Search a specific Class *********
    public function search_class(Request $request)
    {
        $rules = [
            'search' =>  'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ],400);
        }

        $apiURL = 'https://api.braincert.com/v2/listclass';
        $postInput = [
            'apikey' => "PU0MLbUZrGbmonA3PHny",
            'search' => $request->search,
        ];

        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);
        $responseBody = json_decode($response->getBody(), true);

        $query = $request->search;
        $class =  AcademicClass::select('id', 'title', 'lesson_name', 'class_type', 'student_id', 'teacher_id', 'course_id')
            ->with('student', 'teacher', 'course')
            ->where('title', 'LIKE', "%$query%")
            ->orWhere('lesson_name', 'LIKE', "%$query%")
            ->orWhere('class_id', 'LIKE', "%$query%")
            ->get();
        $result = array();
        $result = $responseBody['classes'];
        $result = array_merge($result, $class->toArray());
        return response()->json([
            'success' => true,
            'braincert_class' => $result,
            // 'class' => $class,
        ]);

        if ($responseBody['status'] == 'error') {
            return response()->json([
                'success' => false,
                'error' => $responseBody['errors'],

            ],400);
        }
    }

   
}
