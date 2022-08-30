<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Country;
use App\Events\ClassRescheduleEvent;
use App\FieldOfStudy;
use App\Jobs\ClassRescheduleJob;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\ClassSession;
use App\Models\Feedback;
use App\Models\RescheduleClass;
use App\Models\UserFeedback;
use App\User;
use App\Program;
use App\Subject;
use App\TeacherAvailability;
use App\TimeZone;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JWTAuth;

class ClassController extends Controller
{
    //********* Create Course *********
    public function create_course(Request $request)
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
            ], 400);
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
        $course->status = 'pending';
        $course->save();

        $classes = $request->classes;
        $classes = json_decode($classes);
        foreach ($classes as $session) {
            $class = new AcademicClass();
            $class->course_id = $course->id;
            $class->teacher_id = $request->teacher_id;
            $class->student_id = $token_user->id;
            $class->start_date = $session->date;
            $class->end_date = $request->end_date;
            $class->start_time = $session->start_time;
            $class->end_time = $session->end_time;
            $class->class_type = $request->class_type;
            $class->duration = $session->duration;
            $class->day = $session->day;
            $class->status = 'pending';
            $class->save();
        }
        $program = Program::find($request->program_id);
        $subject = Subject::find($request->subject_id);
        $course_count = Course::where('subject_id', $subject->id)->where('program_id', $request->program_id)->count();

        $course = Course::with('subject', 'language', 'field', 'teacher', 'program')->find($course->id);
        // $findcourse = Course::where('subject_id',$subject->id)->
        $course->course_code = $program->code . '-' . Str::limit($subject->name, 3, '-') . ($course_count++);

        if ($course_count == 0) {
            $course->course_name = $subject->name . "0001";
        } else {
            $course->course_name = $subject->name . "000" . --$course_count;
        }


        $course->update();

        return response()->json([
            'message' => "Course data added Successfully!",
            'success' => true,
            'course' => $course,
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
            ], 400);
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
            ], 400);
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
                $query->where('receiver_id', $teacher_id);
            }])
            // ->with('feedbacks.user')
            ->find($teacher_id);

        $total_feedbacks = UserFeedback::where('receiver_id', $teacher_id)->count();
        if ($total_feedbacks > 0) {
            $total_rating = UserFeedback::where('receiver_id', $teacher_id)->sum('rating');
            $average_feedback = $total_rating / $total_feedbacks;
        }

        $students_teaching = Course::where('teacher_id', $teacher_id)->count();
        $courses_created = Course::where('teacher_id', $teacher_id)->count();

        $expert_rating = 0;
        $expert_rating_total = UserFeedback::where('feedback_id', 1)->where('receiver_id', $teacher_id)->count();
        if ($expert_rating_total > 0) {
            $expert_rating_sum = UserFeedback::where('feedback_id', 1)->where('receiver_id', $teacher_id)->sum('rating');
            $expert_rating = $expert_rating_sum / $expert_rating_total;
        }

        $complexity_rating = 0;
        $complexity_rating_total = UserFeedback::where('feedback_id', 2)->where('receiver_id', $teacher_id)->count();
        if ($complexity_rating_total > 0) {
            $complexity_rating_sum = UserFeedback::where('feedback_id', 2)->where('receiver_id', $teacher_id)->sum('rating');
            $complexity_rating = $complexity_rating_sum / $complexity_rating_total;
        }

        $skillfull_rating = 0;
        $skillfull_rating_total = UserFeedback::where('feedback_id', 3)->where('receiver_id', $teacher_id)->count();
        if ($skillfull_rating_total > 0) {
            $skillfull_rating_sum = UserFeedback::where('feedback_id', 3)->where('receiver_id', $teacher_id)->sum('rating');
            $skillfull_rating = $skillfull_rating_sum / $skillfull_rating_total;
        }

        $onTime_rating = 0;
        $onTime_rating_total = UserFeedback::where('feedback_id', 4)->where('receiver_id', $teacher_id)->count();
        if ($onTime_rating_total > 0) {
            $onTime_rating_sum = UserFeedback::where('feedback_id', 4)->where('receiver_id', $teacher_id)->sum('rating');
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
            ->with('teacher_subjects', 'teacher_subjects.subject', 'feedbacks')
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
        $total_feedbacks = UserFeedback::where('receiver_id', $teacher_id)->count();
        if ($total_feedbacks != 0) {
            $total_rating = UserFeedback::where('receiver_id', $teacher_id)->sum('rating');
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
            ], 400);
        }
        /// Curl Implementation
        $class = AcademicClass::find($request->academic_class_id);
        $course = Course::find($class->course_id);

        $apiURL = 'https://api.braincert.com/v2/schedule';
        $postInput = [
            'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
            'title' =>  $request->title,
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
            $class->title = $request->title;
            $class->lesson_name = $request->lesson_name;
            $class->class_id = $responseBody['class_id'];
            $class->status = "scheduled";
            $course->status = "active";
            $course->update();
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
    public function class_url(Request $request, $id)
    {

        $class = AcademicClass::find($id);
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($class == null) {
            return response()->json([
                'status' => false,
                'message' => 'class not found'
            ], 400);
        }

        if ($token_user->role_name == "teacher") {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $apiURL = 'https://api.braincert.com/v2/getclasslaunch';
        $postInput = [
            'apikey' => 'xKUyaLJHtbvBUtl3otJc',
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

        if ($responseBody['status'] == 'error') {
            return response()->json([
                'status' => false,
                'message' => $responseBody['error'],
                'error' => $responseBody['error'],
            ], 400);
        } else {
            $class->status = "inprogress";
            $class->update();
            $course = Course::find($class->course_id);
            $course->status = "inprogress";
            $course->update();

            $classrooms = ClassRoom::where('course_id', $class->course_id)->get();
            if (count($classrooms) > 0) {
                foreach ($classrooms as $classroom) {
                    $classroom->status = "inprogress";
                    $classroom->save();
                }
            }

            // User Attendence starts
            $check_attd = Attendance::where('user_id', $token_user->id)->where('academic_class_id', $class->id)->count();
            if ($check_attd == 0) {
                $attendance = new Attendance();
                $attendance->user_id = $token_user->id;
                $attendance->academic_class_id = $class->id;
                $attendance->course_id = $class->course_id;
                $attendance->status = 'present';
                if ($token_user->rolename == 'teacher') {
                    $attendance->rolename = 'teacher';
                }
                if ($token_user->rolename == 'student') {
                    $attendance->rolename = 'teacher';
                }
                $attendance->save();
            }
            // User Attendence ends

            return response()->json([
                'status' => true,
                'class_url' => $responseBody['launchurl'],
            ]);
        }
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
            ], 400);
        }

        $class = AcademicClass::with('student', 'teacher', 'course')->find($request->academic_class_id);
        return response()->json([
            'success' => true,
            'classes' => $class,
        ]);
    }

    //*********/ Classroom Dashboard *********
    public function courses(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($token_user->role_name == 'teacher') {
            $userrole = 'teacher_id';
        } elseif ($token_user->role_name == 'student') {
            $userrole = 'student_id';
        }

        $classroom = ClassRoom::where($userrole, $token_user->id)->pluck('course_id');
        $programs = Program::all();
        if (count($request->all()) >= 1) {

            if (count($request->all()) == 1) {
                $program = Program::find($request->program);
                $countries = Country::select('id', 'name', 'emojiU')->get();
                $fieldOfStudies = FieldOfStudy::where('program_id', $program->id)->get();

                $newly_assigned_courses = Course::with('subject', 'language', 'program', 'student', 'student', 'classes')
                ->whereIn('id', $classroom)->where('status', 'pending')->where('program_id', $program->id)->orderBy('id', 'desc')->get();
                $active_courses = Course::with('subject', 'language', 'program', 'student', 'student', 'classes')->whereIn('id', $classroom)->whereIn('status', ['active', 'inprogress'])->where('program_id', $program->id)->orderBy('id', 'desc')->get();
                $cancelled_courses = Course::with('subject', 'language', 'program', 'student', 'student', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->where('program_id', $program->id)->orderBy('id', 'desc')->get();
                $completed_courses = Course::with('subject', 'language', 'program', 'student', 'student', 'classes')->whereIn('id', $classroom)->where('status', 'completed')->where('program_id', $program->id)->get();

                return response()->json([
                    'success' => true,
                    'programs' => $programs,
                    'field_of_studies' => $fieldOfStudies,
                    'countries' => $countries,
                    'newly_assigned_courses' => $newly_assigned_courses,
                    'active_courses' => $active_courses,
                    'completed_courses' => $completed_courses,
                ]);
            }

            if (count($request->all()) == 2) {
                // return "field of study 2";
                if ($request->has('field_of_study')) {
                    $program = Program::find($request->program);
                    $field_of_study = FieldOfStudy::find($request->field_of_study);
                    $countries = Country::select('id', 'name', 'emojiU')->get();

                    $fieldOfStudies = FieldOfStudy::where('program_id', $program->id)->get();

                    $newly_assigned_courses = Course::with('subject', 'language', 'program', 'student', 'classes')->whereIn('id', $classroom)->where('status', 'pending')->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->orderBy('id', 'desc')->get();
                    $active_courses = Course::with('subject', 'language', 'program', 'student', 'classes')->whereIn('id', $classroom)->whereIn('status', ['active', 'pending'])->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->orderBy('id', 'desc')->get();
                    $cancelled_courses = Course::with('subject', 'language', 'program', 'student', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->orderBy('id', 'desc')->get();
                    $completed_courses = Course::with('subject', 'language', 'program', 'student', 'classes')->whereIn('id', $classroom)->where('status', 'completed')->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->get();

                    return response()->json([
                        'success' => true,
                        'programs' => $programs,
                        'field_of_studies' => $fieldOfStudies,
                        'countries' => $countries,
                        'newly_assigned_courses' => $newly_assigned_courses,
                        'active_courses' => $active_courses,
                        'completed_courses' => $completed_courses,

                    ]);
                }

                if ($request->has('country')) {
                    $program = Program::find($request->program);
                    $country = Country::find($request->country);
                    $countries = Country::select('id', 'name', 'emojiU')->get();

                    $fieldOfStudies = FieldOfStudy::where('program_id', $program->id)->where('country_id', $country->id)->get();

                    $newly_assigned_courses = Course::with('subject', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->where('status', 'pending')->where('program_id', $program->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                    $active_courses = Course::with('subject', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->whereIn('status', ['active', 'inprogress'])->where('program_id', $program->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                    $cancelled_courses = Course::with('subject', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->where('program_id', $program->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                    $completed_courses = Course::with('subject', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->where('status', 'completed')->where('program_id', $program->id)->where('country_id', $country->id)->get();

                    return response()->json([
                        'success' => true,
                        'programs' => $programs,
                        'field_of_studies' => $fieldOfStudies,
                        'countries' => $countries,
                        'newly_assigned_courses' => $newly_assigned_courses,
                        'active_courses' => $active_courses,
                        'completed_courses' => $completed_courses,

                    ]);
                }
            }

            if (count($request->all()) == 3) {

                $program = Program::find($request->program);
                $field_of_study = FieldOfStudy::find($request->field_of_study);
                $country = Country::find($request->country);
                $countries = Country::select('id', 'name', 'emojiU')->get();


                $fieldOfStudies = FieldOfStudy::where('program_id', $program->id)->where('country_id', $country->id)->get();

                $newly_assigned_courses = Course::with('subject', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->where('status', 'pending')->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                $active_courses = Course::with('subject', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->whereIn('status', ['active', 'inprogress'])->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                $cancelled_courses = Course::with('subject', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                $completed_courses = Course::with('subject', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->where('status', 'completed')->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->where('country_id', $country->id)->get();

                return response()->json([
                    'success' => true,
                    'programs' => $programs,
                    'field_of_studies' => $fieldOfStudies,
                    'countries' => $countries,
                    'newly_assigned_courses' => $newly_assigned_courses,
                    'active_courses' => $active_courses,
                    'completed_courses' => $completed_courses,

                ]);
            }
        } else {

            $program = Program::where('code', $request->program)->first();
            $newly_assigned_courses = Course::with('subject', 'language', 'program', 'student', 'classes')->where($userrole, $token_user->id)->where('status', 'pending')->orderBy('id', 'desc')->get();
            $active_courses = Course::with('subject', 'language', 'program', 'student', 'classes')->where($userrole, $token_user->id)->whereIn('status', ['active', 'inprogress'])->orderBy('id', 'desc')->get();
            $cancelled_courses = Course::with('subject', 'language', 'program', 'student', 'classes')->where($userrole, $token_user->id)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->orderBy('id', 'desc')->get();
            $completed_courses = Course::with('subject', 'language', 'program', 'student', 'classes')->where($userrole, $token_user->id)->where('status', 'completed')->get();

            return response()->json([
                'success' => true,
                'programs' => $programs,
                'newly_assigned_courses' => $newly_assigned_courses,
                'active_courses' => $active_courses,
                'completed_courses' => $completed_courses,
            ]);
        }
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
    public function course_detail(Request $request, $course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($token_user->role_name == 'teacher') {
            $userrole = 'teacher_id';
        } elseif ($token_user->role_name == 'student') {
            $userrole = 'student_id';
        }

        $todays_date = Carbon::now()->format('d-M-Y [l]');

        $user_id = $token_user->id;
        $current_date = Carbon::today()->format('Y-m-d');

        $course = Course::with('subject', 'language', 'program', 'classes')->find($course_id);

        //************ If class date and time passed then roll call attendence ************
        foreach ($course['classes'] as $class) {
            $classStart = Carbon::parse($class->start_date)->format('Y-m-d');
            if ($current_date >= $classStart) {
                $current_time = Carbon::now();
                $endTime = Carbon::parse($class->end_time);
                if ($current_time > $endTime) {
                    //for students
                    foreach ($class->participants as $participant) {
                        $attendance = Attendance::where('user_id', $participant->student_id)->where('academic_class_id', $class->id)->first();

                        if ($attendance == null) {
                            $userAttendence = new Attendance();
                            $userAttendence->academic_class_id = $class->id;
                            $userAttendence->course_id = $class->course_id;
                            $userAttendence->user_id = $participant->student_id;
                            $userAttendence->status = 'absent';
                            $userAttendence->role_name = 'student';
                            $userAttendence->save();
                        }
                    }
                    //for teacher
                    $attendance = Attendance::where('user_id', $class->teacher_id)->where('academic_class_id', $class->id)->first();
                    if ($attendance == null) {
                        $userAttendence = new Attendance();
                        $userAttendence->academic_class_id = $class->id;
                        $userAttendence->course_id = $class->course_id;
                        $userAttendence->user_id = $class->teacher_id;
                        $userAttendence->status = 'absent';
                        $userAttendence->role_name = 'teacher';
                        $userAttendence->save();
                    }
                }
            }
        }
        // ************************************************

        $todays_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'day', 'status')
            ->with('course', 'course.subject', 'course.student')
            ->with(['student_attendence' => function ($q) {
                $q->where('role_name', 'student');
            }])
            ->with(['teacher_attendence' => function ($q) {
                $q->where('role_name', 'teacher');
            }])
            ->where('start_date', $current_date)
            ->with('course')
            ->where($userrole, $user_id)
            ->where('course_id', $course->id)
            ->orderBy('start_time', 'desc')
            ->get();

        $upcoming_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'day', 'status')
            ->with('course', 'course.subject', 'course.student')
            ->with(['student_attendence' => function ($q) {
                $q->where('role_name', 'student');
            }])
            ->with(['teacher_attendence' => function ($q) {
                $q->where('role_name', 'teacher');
            }])
            ->where('start_date', '>', $current_date)
            ->with('course')
            ->where($userrole, $user_id)
            ->where('course_id', $course->id)
            ->get();

        $total_upcomingClasses = AcademicClass::where('start_date', '>', $current_date)
            ->where($userrole, $user_id)
            ->where('course_id', $course->id)
            ->count();

        $past_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'day', 'status')
            ->with('course', 'course.subject', 'course.student')
            ->with(['student_attendence' => function ($q) {
                $q->where('role_name', 'student');
            }])
            ->with(['teacher_attendence' => function ($q) {
                $q->where('role_name', 'teacher');
            }])
            ->whereHas('attendence', function ($query) use ($user_id) {
                $query->where(['user_id' => $user_id]);
            })
            ->where('start_date', '<', $current_date)
            ->with('course')
            ->where($userrole, $user_id)
            ->where('course_id', $course->id)
            ->get();

        $total_pastClasses = AcademicClass::where('start_date', '<', $current_date)
            ->where($userrole, $user_id)
            ->where('course_id', $course->id)
            ->count();

        $remaining_classes = AcademicClass::where('course_id', $course_id)->where('status', '!=', 'completed')->count();
        $completed_classes = AcademicClass::where('course_id', $course_id)->where('status', 'completed')->count();

        $totalClases = AcademicClass::where('course_id', $course_id)->where($userrole, $user_id)->count();
        $completedClases = AcademicClass::where('course_id', $course_id)->where('status', 'completed')->where($userrole, $user_id)->count();
        if ($totalClases > 0) {
            $inProgress = ($completedClases / $totalClases) * 100;
        }


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
            'progress' => $inProgress,
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
            ], 400);
        }

        $apiURL = 'https://api.braincert.com/v2/listclass';
        $postInput = [
            'apikey' => 'xKUyaLJHtbvBUtl3otJc',
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

            ], 400);
        }
    }

    //*********/ Teacher: Reschedule Class *********
    public function reschedule_class(Request $request)
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $rules = [
            'academic_class_id' =>  'required',
            'start_time' =>  'required',
            'end_time' =>  'required',
            'start_date' =>  'required|date|after:today',
            'duration' =>  'required',
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


        $academic_id = $request->academic_class_id;
        $class = AcademicClass::where('id', $academic_id)->first();
        //checking date
        $currentdate = Carbon::now()->format('Y-m-d');
        $currentdate = Carbon::parse($currentdate);
        $db_date = Carbon::parse($class->start_date);
        if ($db_date >= $currentdate) {
            $dayOrNight = substr($class->start_time, 6, 9);
            $trimed_time = Str::limit($class->start_time, 5, '');
            $final_time = $trimed_time;
            // converting pm to 24 hour format
            if ($dayOrNight == "PM") {

                $time = $trimed_time;
                $time2 = "12:00:00";
                $secs = strtotime($time2) - strtotime("00:00:00");
                $final_time = date("H:i", strtotime($time) + $secs);
            }
            // converting pm to 24 hour format ends

            // calculating difference
            $crrentTime = Carbon::now()->format('Y-m-d H:i');
            $startTime = Carbon::parse($crrentTime);
            $endTime = Carbon::parse($class->start_date . ' ' . $final_time);
            $totalDuration = $endTime->diffInHours($crrentTime);
            // calculating difference ends

            $EndTime = substr($request->end_time, 0, 5);
            $StartTime = substr($request->start_time, 0, 5);

            $start_date = Carbon::parse($class->start_date);

            $start_time = date("G:i", strtotime($request->start_time));
            $end_time = date("G:i", strtotime($request->end_time));

            $classes = AcademicClass::where('id', '!=', $academic_id)->where('start_date', $request->start_date)->where('teacher_id', $token_user->id)->get();

            foreach ($classes as $class) {
                $db_startTime = date("G:i", strtotime($class->start_time));
                $db_endTime = date("G:i", strtotime($class->end_time));
                if (($start_time >= $db_startTime) && ($start_time <= $db_endTime) || ($end_time >= $db_startTime) && ($end_time <= $db_endTime)) {
                    return response()->json([
                        'status' => false,
                        'errors' => "Already have Scheduled Class at this time!",
                    ], 400);
                }
            }
            // return $totalDuration;
            if ($totalDuration > 48) {
                $class->start_time = $request->start_time;
                $class->end_time = $request->end_time;
                $class->start_date = $request->start_date;
                $class->class_type = $request->class_type;
                $class->duration = $request->duration;

                $apiURL = 'https://api.braincert.com/v2/updateclass';
                $postInput = [
                    'apikey' => 'xKUyaLJHtbvBUtl3otJc',
                    'id' => $class->class_id,
                    'start_time' => Carbon::parse($request->start_time)->format('h:i a'),
                    'end_time' => Carbon::parse($request->end_time)->format('h:i a'),
                    'date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                    'end_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                ];
                $client = new Client();
                $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);
                $responseBody = json_decode($response->getBody(), true);
                if ($responseBody['status'] == 'error') {
                    return response()->json([
                        'status' => false,
                        'message' => $responseBody['error'],
                    ], 400);
                } else {
                    $class->class_id = $responseBody['class_id'];
                }
                $academic_class = AcademicClass::find($request->academic_class_id);
                $reschedule_class = new RescheduleClass();
                $reschedule_class->rescheduled_by = $token_user->id;
                $reschedule_class->academic_class_id =  $academic_class->id;
                $reschedule_class->course_id = $academic_class->course_id;
                $reschedule_class->start_date = $academic_class->start_date;
                $reschedule_class->start_time = $academic_class->start_time;
                $reschedule_class->end_time = $academic_class->end_time;
                $reschedule_class->day = $academic_class->day;
                $reschedule_class->status = "rescheduled_by_teacher";
                $reschedule_class->save();
                $class->update();

                $student = User::findOrFail($token_user->id);
                $teacher = User::findOrFail($academic_class->teacher_id);
                $teacher_message = "Class Rescheduled Successfully!";
                $student_message = "Teacher Rescheduled a class!";

                // event(new ClassRescheduleEvent($student->id, $student, $academic_class, $student_message));
                // event(new ClassRescheduleEvent($teacher->id, $teacher, $academic_class, $teacher_message));
                // dispatch(new ClassRescheduleJob($student->id, $student, $academic_class, $student_message));
                // dispatch(new ClassRescheduleJob($teacher->id, $teacher, $academic_class, $teacher_message));


                return response()->json([
                    'status' => true,
                    'message' => "Class Rescheduled Successfully!",
                    'class' => $class,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'errors' => "You dont have time to reschdule the class",
                ], 400);
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => "Class Date has been Passed",
            ], 400);
        }
    }

    //************* Program Subjects *************
    public function program_subjects($program_id)
    {
        $fieldOfStudies = FieldOfStudy::where('program_id', $program_id)->get();
        $subjects_array = [];
        foreach ($fieldOfStudies as $fieldOfStudy) {
            $subjects = $subject = Subject::select('id', 'name')->where('field_id', $fieldOfStudy->id)->get();
            foreach ($subjects as $subject) {
                array_push($subjects_array, $subject);
            }
        }

        return response()->json([
            'status' => true,
            'subjects' => $subjects_array,
        ]);
    }

    //************* Teacher Kudos Points *************
    public function kudos_points(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $teacher = User::find($token_user->id);
        $feedbacks = UserFeedback::with('sender', 'feedback')->where('receiver_id', $token_user->id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Teacher Kudos points',
            'kudos_points' => $teacher->kudos_points,
            'feedbacks' => $feedbacks,
        ]);
    }

    //************* Paginator functtion *************
    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }
}
