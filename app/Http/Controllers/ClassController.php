<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\Models\Course;
use App\Models\ClassSession;
use App\Models\Feedback;
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

class ClassController extends Controller
{
    //*********/Schedule a class *********
    public function create_class(Request $request)
    {
        $rules = [
            'field_of_study' =>  'required',
            'course_level' =>  'required',
            'language_id' =>  'required',
            'book_type' =>  'required',
            'book_info' =>  'required',
            'total_classes' =>  'required',
            'total_hours' =>  'required',
            'total_price' =>  'required',
            'subject_id' =>  'required',

            'teacher_id' =>  'required',
            'start_date' =>  'required',
            'end_date' =>  'required',
            'class_type' =>  'required',
            'start_time' =>  'required',
            'end_time' =>  'required',
            'day' =>  'required',
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
        if ($request->book_info == 3) {
            $request->validate([
                'files' =>  'required',
            ]);
            $course->book_info = $request->book_info;
            if ($request->hasFile('files')) {
                //************* book files **********\\
                $images = array();
                $files = $request->file('files');
                    foreach ($files as $file) {
                        $imageName = date('YmdHis').random_int(10, 100).'.'.$file->getClientOriginalExtension();
                        $file->move(public_path('assets/images/class_files'), $imageName);
                        $images[] = $imageName;
                    }
                $course->files = implode("|", $images);
                //************* book files ends **********\\
            }
            
        }
        if ($request->book_info == 4) {
            $request->validate([
                'book_name' =>  'required',
                'book_edition' =>  'required',
                'book_author' =>  'required',
            ]);
            $course->book_info = $request->book_info;
            $course->book_name = $request->book_name;
            $course->book_edition = $request->book_edition;
            $course->book_author = $request->book_author;
            
        }
        else{
            $course->book_info = $request->book_info;
        }
        $course->field_of_study = $request->field_of_study;
        $course->course_level = $request->course_level;
        $course->language_id = $request->language_id;
        $course->book_type = $request->book_type;
        $course->total_price = $request->total_price;
        $course->total_hours = $request->total_hours;
        $course->total_classes = $request->total_classes;
        $course->subject_id = $request->subject_id;
        $course->save();

        for ($i = 0; $i < 3; $i++) {
            $class = new AcademicClass();
            $class->course_id = $course->id;
            $class->teacher_id = $request->teacher_id;
            $class->student_id = auth('sanctum')->user()->id;
            $class->start_date = $request->start_date;
            $class->end_date = $request->end_date;
            $class->start_time = $request->start_time;
            $class->end_time = $request->end_time;
            $class->class_type = $request->class_type;
            $class->day = $request->day;
            $class->save();
        }
        $program=Program::find( $request->program_id);
        $subject=Subject::find( $request->subject_id);
        $course_count=Course::where('subject_id',$request->subject_id)->where('program_id',$request->program_id)->count();

        $course = Course::with('subject','language','field')->find($course->id);
        $course->course_code = $program->code.'-'.Str::limit($subject->name, 3,'-').($course_count++);
        $course->course_name = $subject->name."0001";
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
            ]);
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
            ]);
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
        $teacher_id=auth('sanctum')->user()->id;

        $teacher = User::select('id','first_name','last_name','role_name','email','mobile','kudos_points')->with('teacher_qualification','teacher_specification','spoken_languages','teacher_subjects')
        ->with(['feedbacks' => function($query) use($teacher_id){
                $query->where('feedback_by','!=',$teacher_id);
            }])
        ->find($teacher_id);

        $total_feedbacks = Feedback::where('teacher_id',$teacher_id)->where('feedback_by','!=',$teacher_id)->count();
        $total_rating = Feedback::where('teacher_id',$teacher_id)->where('feedback_by','!=',$teacher_id)->sum('rating');
        $average_feedback = $total_rating / $total_feedbacks;
        
        return response()->json([
            'success' => true,
            'teacher' => $teacher,
            'total_feedbacks' => $total_feedbacks,
            'average_feedback' => $average_feedback,
        ]);
    }

    //*********/ Search Teacher *********
    public function search_tutor(Request $request)
    {
        $rules = [
            'name' =>  'required',
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

        $query = $request->name;
        $result = User::select('id','first_name','last_name','role_name','email','mobile','created_at')->with('teacher_subjects','teacher_subjects.subject')->where('role_name','=','teacher')->where('first_name', 'LIKE', "%$query%")->orWhere('last_name', 'LIKE', "%$query%")->get();
        
        $average_rating=0;
        $teacher_id=$request->teacher_id;
        $total_feedbacks = Feedback::where('teacher_id',$teacher_id)->where('feedback_by','!=',$teacher_id)->count();
        if($total_feedbacks != 0)
        {
            $total_rating = Feedback::where('teacher_id',$teacher_id)->where('feedback_by','!=',$teacher_id)->sum('rating');
            $average_rating = $total_rating / $total_feedbacks;
        }
        
        return response()->json([
            'success' => true,
            'tutors' => $result,
            'average_rating' => $average_rating,
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
            ]);
        }
        /// Curl Implementation
        $class_room = AcademicClass::find($request->academic_class_id);

        $apiURL = 'https://api.braincert.com/v2/schedule';
        $postInput = [
            'apikey' =>  "PU0MLbUZrGbmonA3PHny",
            'title' =>  $request->title,
            'timezone' => 90,
            'start_time' => $class_room->start_time,
            'end_time' => $class_room->end_time,
            'date' => $class_room->start_date,
            'currency' => "USD",
            'ispaid' => null,
            'is_recurring' => 1,
            'repeat' => 1,
            'weekdays' => null,
            'end_date' => $class_room->end_date,
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
            $class_room->title = $request->title;
            $class_room->lesson_name = $request->lesson_name;
            $class_room->class_id = $responseBody['class_id'];
            $class_room->status = "scheduled";
            $class_room->save();
            return response()->json([
                'success' => true,
                'message' => "Class Scheduled SuccessFully",
                'class' => $class_room,
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
            ]);
        }
        // return $request;
        $class = AcademicClass::where("class_id", $request->class_id)->first();

        if (auth("sanctum")->user()->role_name == "teacher") {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $apiURL = 'https://api.braincert.com/v2/getclasslaunch';
        $postInput = [
            'apikey' => "PU0MLbUZrGbmonA3PHny",
            'class_title' =>  $class->title,
            'class_id' => $class->class_id,
            'userId' => auth('sanctum')->user()->id,
            'userName' => auth('sanctum')->user()->first_name . " " . auth('sanctum')->user()->last_name,
            'isTeacher' => $flag,
            'lessonName' => $class->lesson_name,
            'courseName' => "laravells",
        ];

        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);

        $class->status="started";
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
        $rules = [
            'student_id' =>  'required',
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
        $student = User::select('id','first_name','last_name','role_name','email','mobile','avatar','created_at')->where('id',$request->student_id)->where('role_name','=','student')->first();
        return response()->json([
            'success' => true,
            'student' => $student,
        ]);
    }

    //*********/ Search Student *********
    public function search_student(Request $request)
    {
        $rules = [
            'search_query' =>  'required',
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

        $query = $request->search_query;
        $student = User::select('id','first_name','last_name','role_name','email','mobile','created_at')->where('role_name','=','teacher')->where('first_name', 'LIKE', "%$query%")->orWhere('last_name', 'LIKE', "%$query%")->orWhere('id', 'LIKE', "%$query%")->orWhere('created_at', 'LIKE', "%$query%")->get();

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

        $validator=Validator::make($request->all(),$rules);
        
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            
            return response()->json([
                'status' => 'false',
                'errors' => $errors,
                ]) ;
        }

        $class = AcademicClass::with('student', 'teacher','course')->find($request->academic_class_id);
        return response()->json([
            'success' => true,
            'classes' => $class,
        ]);
    }

    //*********/  All Courses details *********
    public function courses(Request $request)
    {
        $courses = Course::with('subject','language','program')->get();
        return response()->json([
            'success' => true,
            'classes' => $courses,
        ]);
    }

    //*********/ Search Course *********
    public function search_course(Request $request)
    {
        $rules = [
            'search_query' =>  'required',
        ];

        $validator=Validator::make($request->all(),$rules);
        
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            
            return response()->json([
                'status' => 'false',
                'errors' => $errors,
                ]) ;
        }

        $query = $request->search_query;
        $course = Course::with('subject','language','program')->where('course_name', 'LIKE', "%$query%")->get();

        return response()->json([
            'success' => true,
            'student' => $course,
        ]);
    }

    //*********/ Show ALL Classes *********
    public function show_classes()
    {
        $user_id = auth('sanctum')->user()->id;
        $user = User::find($user_id);
        if ($user->role_name == "student") {
            $classes = AcademicClass::with('student', 'teacher','course')->where('student_id', $user->id)->get();
            return response()->json([
                'success' => true,
                'classes' => $classes,
            ]);
        }
        if ($user->role_name == "teacher") {
            $classes = AcademicClass::with('student', 'teacher','course')->where('teacher_id', $user->id)->get();
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

        $validator=Validator::make($request->all(),$rules);
        
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            
            return response()->json([
                'status' => 'false',
                'errors' => $errors,
                ]) ;
        }

        $course = Course::with('subject','language','program')->find($request->course_id);
        return response()->json([
            'success' => true,
            'classes' => $course,
        ]);
    }

    //*********/ Search a specific Class *********
    public function search_class(Request $request)
    {
        $rules = [
            'search' =>  'required',
        ];

        $validator=Validator::make($request->all(),$rules);
        
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            
            return response()->json([
                'status' => 'false',
                'errors' => $errors,
                ]) ;
        }

        $apiURL = 'https://api.braincert.com/v2/listclass';
        $postInput = [
            'apikey' => "PU0MLbUZrGbmonA3PHny",
            'search'=> $request->search,
        ];

        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);
        $responseBody = json_decode($response->getBody(), true);

        $query = $request->title;
        $class =  AcademicClass::select('id','title','lesson_name','class_type','student_id','teacher_id','course_id')->with('student', 'teacher','course')->where('title', 'LIKE', "%$query%")->orWhere('lesson_name', 'LIKE', "%$query%")->get();
        $result=array();
        $result=$responseBody['classes'];
        $result=array_merge($result,$class->toArray());
        return response()->json([
            'success' => true,
            'braincert_class' => $result,
            // 'class' => $class,
        ]);

        if($responseBody['status']=='error'){
            return response()->json([
                'success' => false,
                'error' => $responseBody['errors'],
               
            ]);
        }
    }


    

    
}
