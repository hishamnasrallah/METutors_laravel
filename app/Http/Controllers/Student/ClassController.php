<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Country;
use App\Events\ClassRescheduleEvent;
use App\Events\MakeupClassEvent;
use App\Events\SelectTeacherEvent;
use App\FieldOfStudy;
use App\Jobs\ClassRescheduleJob;
use App\Jobs\MakeupClassJob;
use App\Jobs\SelectTeacherJob;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\ClassSession;
use App\Models\Feedback;
use App\Models\LastActivity;
use App\Models\MakeupClass;
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
use Illuminate\Support\Facades\Mail;
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

        $user = User::find($token_user);
        $teacher = User::find($request->teacher_id);

        //*********** Sending email to Student ************\\
        $user_name = $user->name;
        $user_email = $user->email;
        $to_name = $user_name;
        $to_email = $user_email;
        $data = array('user' => $user_name, 'course' => $course->course_name, 'start_time' => $course->start_time, 'end_time' => $course->end_time, 'end_date' => $course->end_date, 'start_date' => $course->start_date);
        Mail::send('email.student_course', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Course Booking');
            $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });
        //********* Sending email ends **********//

        //*********** Sending email to Teacher ************\\
        $user_name = $teacher->name;
        $user_email = $teacher->email;
        $to_name = $user_name;
        $to_email = $user_email;
        $data = array('user' => $user_name, 'course' => $course->course_name, 'start_time' => $course->start_time, 'end_time' => $course->end_time, 'end_date' => $course->end_date, 'start_date' => $course->start_date);
        Mail::send('email.teacher_course', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Course Added');
            $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });
        //********* Sending email ends **********//

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
            ->with('teacher_qualification', 'teacher_specification', 'spoken_languages', 'teacher_subjects.subject.country')
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
            'repeat' => 1,
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
            return response()->json([
                'success' => false,
                'message' => $responseBody['error'],
            ], 400);
        }
    }


    //*********/ Start Class *********
    public function class_url(Request $request, $id)
    {
        $class = AcademicClass::find($id);
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($class->status == 'canceled') {
            return response()->json([
                'status' => false,
                'message' => 'Class has been Canceled!'
            ], 400);
        }

        if ($class->teacher_id == null) {
            return response()->json([
                'status' => false,
                'message' => 'Teacher not assigned yet!'
            ], 400);
        }


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
                return true;
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

        if ($token_user->role_name == 'teacher') {
            $courses = Course::where('teacher_id', $token_user->id)->get();
            $course_programs = $courses->unique('program_id')->pluck('program_id');
            $programs = Program::whereIn('id', $course_programs)->get();
            $course_field_of_studies = $courses->unique('field_of_study')->pluck('field_of_study');
            $field_of_studies = FieldOfStudy::whereIn('id', $course_field_of_studies)->get();
        }
        if ($token_user->role_name == 'student') {
            $courses = Course::where('student_id', $token_user->id)->get();
            $course_programs = $courses->unique('program_id')->pluck('program_id');
            $programs = Program::whereIn('id', $course_programs)->get();
            $course_field_of_studies = $courses->unique('field_of_study')->pluck('field_of_study');
            $field_of_studies = FieldOfStudy::whereIn('id', $course_field_of_studies)->get();
        }

        if (count($request->all()) >= 1) {

            if (count($request->all()) == 1) {
                $program = Program::find($request->program);
                $countries = Country::select('id', 'name', 'emojiU')->get();
                $fieldOfStudies = FieldOfStudy::whereIn('id', $course_field_of_studies)->where('program_id', $program->id)->get();


                $active_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'student', 'classes')->whereIn('id', $classroom)->whereIn('status', ['pending', 'active', 'inprogress'])->where('program_id', $program->id)->orderBy('id', 'desc')->get();
                $cancelled_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'student', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher.teacher_qualification', 'cancelled_by_student', 'cancelled_by_admin'])->where('program_id', $program->id)->orderBy('id', 'desc')->get();
                $completed_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'student', 'classes')->whereIn('id', $classroom)->where('status', 'completed')->where('program_id', $program->id)->orderBy('id', 'desc')->get();
                // $lastActivity_course = Course::with('subject.country', 'language', 'program', 'teacher', 'classes')->whereIn('id', $classroom)->where('program_id', $program->id)->orderBy('updated_at', 'desc')->first();
                $lastActivity_course = LastActivity::with('course.classes', 'course.subject', 'course.language', 'course.program', 'course.teacher.teacher_qualification')->where('user_id', $token_user->id)->where('role_name', $token_user->role_name)->where('program_id', $program->id)->latest('updated_at')->first();


                $progress = 0;
                foreach ($active_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);

                    $rating_sum = 0;
                    $average_rating = 0;
                    $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                    if ($rating_sum > 0) {
                        $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                        $average_rating = $rating_sum / $total_reviews;
                    }

                    $course['teacher']->average_rating = $average_rating;
                    $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                    unset($course['teacher']['teacher_qualification']);
                }

                $progress = 0;
                foreach ($cancelled_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);

                    $rating_sum = 0;
                    $average_rating = 0;
                    $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                    if ($rating_sum > 0) {
                        $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                        $average_rating = $rating_sum / $total_reviews;
                    }

                    $course['teacher']->average_rating = $average_rating;
                    $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                    unset($course['teacher']['teacher_qualification']);
                }

                $progress = 0;
                foreach ($completed_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);

                    $rating_sum = 0;
                    $average_rating = 0;
                    $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                    if ($rating_sum > 0) {
                        $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                        $average_rating = $rating_sum / $total_reviews;
                    }

                    $course['teacher']->average_rating = $average_rating;
                    $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                    unset($course['teacher']['teacher_qualification']);
                }

                $progress = 0;
                if ($lastActivity_course != null) {
                    $completed_classes = $lastActivity_course->course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $lastActivity_course->course->total_classes) * 100;
                    $lastActivity_course->course->progress = round($progress);

                    $rating_sum = 0;
                    $average_rating = 0;
                    $rating_sum = UserFeedback::where('receiver_id', $lastActivity_course->course->teacher_id)->sum('rating');
                    if ($rating_sum > 0) {
                        $total_reviews = UserFeedback::where('receiver_id', $lastActivity_course->course->teacher_id)->count();
                        $average_rating = $rating_sum / $total_reviews;
                    }

                    $lastActivity_course->course['teacher']->average_rating = $average_rating;
                    $lastActivity_course->course['teacher']->tag_line = $lastActivity_course->course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $lastActivity_course->course['teacher']['teacher_qualification'][0]->degree_field;
                    unset($lastActivity_course->course['teacher']['teacher_qualification']);
                }


                return response()->json([
                    'success' => true,
                    'programs' => $programs,
                    'field_of_studies' => $fieldOfStudies,
                    'countries' =>  $countries,
                    'lastActivity_course' => $lastActivity_course != null ? [$lastActivity_course->course] : [],
                    'active_courses' =>  $active_courses,
                    'cancelled_courses' =>  $cancelled_courses,
                    'completed_courses' => $completed_courses,
                ]);
            }

            if (count($request->all()) == 2) {
                // return "field of study 2";
                if ($request->has('field_of_study')) {
                    $program = Program::find($request->program);
                    $field_of_study = FieldOfStudy::find($request->field_of_study);
                    $countries = Country::select('id', 'name', 'emojiU')->get();

                    $fieldOfStudies =  FieldOfStudy::whereIn('id', $course_field_of_studies)->where('program_id', $program->id)->get();

                    $active_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'classes')->whereIn('id', $classroom)->whereIn('status', ['pending', 'active', 'inprogress'])->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->orderBy('id', 'desc')->get();
                    $cancelled_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->orderBy('id', 'desc')->get();
                    $completed_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'classes')->whereIn('id', $classroom)->where('status', 'completed')->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->orderBy('id', 'desc')->get();
                    // $lastActivity_course = Course::with('subject.country', 'language', 'program', 'teacher', 'classes')->whereIn('id', $classroom)->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->orderBy('updated_at', 'desc')->first();
                    $lastActivity_course = LastActivity::with('course.classes', 'course.subject', 'course.language', 'course.program', 'course.teacher.teacher_qualification')->where('user_id', $token_user->id)->where('role_name', $token_user->role_name)->where('program_id', $program->id)->where('field_of_study_id', $field_of_study->id)->first();


                    $progress = 0;
                    foreach ($active_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);

                        $rating_sum = 0;
                        $average_rating = 0;
                        $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                        if ($rating_sum > 0) {
                            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                            $average_rating = $rating_sum / $total_reviews;
                        }

                        $course['teacher']->average_rating = $average_rating;
                        $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                        unset($course['teacher']['teacher_qualification']);
                    }

                    $progress = 0;
                    foreach ($cancelled_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);

                        $rating_sum = 0;
                        $average_rating = 0;
                        $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                        if ($rating_sum > 0) {
                            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                            $average_rating = $rating_sum / $total_reviews;
                        }

                        $course['teacher']->average_rating = $average_rating;
                        $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                        unset($course['teacher']['teacher_qualification']);
                    }

                    $progress = 0;
                    foreach ($completed_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);

                        $rating_sum = 0;
                        $average_rating = 0;
                        $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                        if ($rating_sum > 0) {
                            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                            $average_rating = $rating_sum / $total_reviews;
                        }

                        $course['teacher']->average_rating = $average_rating;
                        $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                        unset($course['teacher']['teacher_qualification']);
                    }

                    $progress = 0;
                    if ($lastActivity_course != null) {
                        $completed_classes = $lastActivity_course->course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $lastActivity_course->course->total_classes) * 100;
                        $lastActivity_course->course->progress = round($progress);

                        $rating_sum = 0;
                        $average_rating = 0;
                        $rating_sum = UserFeedback::where('receiver_id', $lastActivity_course->course->teacher_id)->sum('rating');
                        if ($rating_sum > 0) {
                            $total_reviews = UserFeedback::where('receiver_id', $lastActivity_course->course->teacher_id)->count();
                            $average_rating = $rating_sum / $total_reviews;
                        }
                        // $lastActivity_course->course->teacher;
                        $lastActivity_course->course['teacher']->average_rating = $average_rating;
                        $lastActivity_course->course['teacher']->tag_line = $lastActivity_course->course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $lastActivity_course->course['teacher']['teacher_qualification'][0]->degree_field;
                        unset($lastActivity_course->course['teacher']['teacher_qualification']);
                    }

                    return response()->json([
                        'success' => true,
                        'programs' => $programs,
                        'field_of_studies' => $fieldOfStudies,
                        'countries' =>  $countries,
                        'lastActivity_course' => $lastActivity_course != null ? [$lastActivity_course->course] : [],
                        'active_courses' =>  $active_courses,
                        'cancelled_courses' =>  $cancelled_courses,
                        'completed_courses' => $completed_courses,

                    ]);
                }

                if ($request->has('country')) {
                    $program = Program::find($request->program);
                    $country = Country::find($request->country);
                    $countries = Country::select('id', 'name', 'emojiU')->get();

                    $fieldOfStudies = FieldOfStudy::whereIn('id', $course_field_of_studies)->where('program_id', $program->id)->where('country_id', $country->id)->get();

                    $active_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'country', 'classes')->whereIn('id', $classroom)->whereIn('status', ['pending', 'active', 'inprogress'])->where('program_id', $program->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                    $cancelled_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'country', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->where('program_id', $program->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                    $completed_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'country', 'classes')->whereIn('id', $classroom)->where('status', 'completed')->where('program_id', $program->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                    // $lastActivity_course = Course::with('subject.country', 'language', 'program', 'teacher', 'classes')->whereIn('id', $classroom)->orderBy('updated_at', 'desc')->first();
                    $lastActivity_course = LastActivity::with('course.classes', 'course.subject', 'course.language', 'course.program', 'course.teacher.teacher_qualification')->where('user_id', $token_user->id)->where('role_name', $token_user->role_name)->where('program_id', $program->id)->where('country_id', $country->id)->latest('updated_at')->first();


                    $progress = 0;
                    foreach ($active_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);

                        $rating_sum = 0;
                        $average_rating = 0;
                        $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                        if ($rating_sum > 0) {
                            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                            $average_rating = $rating_sum / $total_reviews;
                        }

                        $course['teacher']->average_rating = $average_rating;
                        $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                        unset($course['teacher']['teacher_qualification']);
                    }

                    $progress = 0;
                    foreach ($cancelled_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);

                        $rating_sum = 0;
                        $average_rating = 0;
                        $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                        if ($rating_sum > 0) {
                            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                            $average_rating = $rating_sum / $total_reviews;
                        }

                        $course['teacher']->average_rating = $average_rating;
                        $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                        unset($course['teacher']['teacher_qualification']);
                    }

                    $progress = 0;
                    foreach ($completed_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);

                        $rating_sum = 0;
                        $average_rating = 0;
                        $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                        if ($rating_sum > 0) {
                            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                            $average_rating = $rating_sum / $total_reviews;
                        }

                        $course['teacher']->average_rating = $average_rating;
                        $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                        unset($course['teacher']['teacher_qualification']);
                    }

                    $progress = 0;
                    if ($lastActivity_course != null) {
                        $completed_classes = $lastActivity_course->course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $lastActivity_course->course->total_classes) * 100;
                        $lastActivity_course->course->progress = round($progress);

                        $rating_sum = 0;
                        $average_rating = 0;
                        $rating_sum = UserFeedback::where('receiver_id', $lastActivity_course->course->teacher_id)->sum('rating');
                        if ($rating_sum > 0) {
                            $total_reviews = UserFeedback::where('receiver_id', $lastActivity_course->course->teacher_id)->count();
                            $average_rating = $rating_sum / $total_reviews;
                        }

                        $lastActivity_course->course['teacher']->average_rating = $average_rating;
                        $lastActivity_course->course['teacher']->tag_line = $lastActivity_course->course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $lastActivity_course->course['teacher']['teacher_qualification'][0]->degree_field;
                        unset($lastActivity_course->course['teacher']['teacher_qualification']);
                    }

                    return response()->json([
                        'success' => true,
                        'programs' => $programs,
                        'field_of_studies' => $fieldOfStudies,
                        'countries' =>  $countries,
                        'lastActivity_course' => $lastActivity_course != null ? [$lastActivity_course->course] : [],
                        'active_courses' =>  $active_courses,
                        'cancelled_courses' =>  $cancelled_courses,
                        'completed_courses' => $completed_courses,

                    ]);
                }
            }

            if (count($request->all()) == 3) {

                $program = Program::find($request->program);
                $field_of_study = FieldOfStudy::find($request->field_of_study);
                $country = Country::find($request->country);
                $countries = Country::select('id', 'name', 'emojiU')->get();


                $fieldOfStudies = FieldOfStudy::whereIn('id', $course_field_of_studies)->where('program_id', $program->id)->where('country_id', $country->id)->get();

                $active_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'country', 'classes')->whereIn('id', $classroom)->whereIn('status', ['pending', 'active', 'inprogress'])->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                $cancelled_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'country', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                $completed_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'country', 'classes')->whereIn('id', $classroom)->where('status', 'completed')->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                // $lastActivity_course = Course::with('subject.country', 'language', 'program', 'teacher', 'classes')->whereIn('id', $classroom)->orderBy('updated_at', 'desc')->first();
                $lastActivity_course = LastActivity::with('course.classes', 'course.subject', 'course.language', 'course.program', 'course.teacher.teacher_qualification')->where('user_id', $token_user->id)->where('role_name', $token_user->role_name)->where('field_of_study_id', $field_of_study->id)->where('country_id', $country->id)->latest('updated_at')->first();


                $progress = 0;
                foreach ($active_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);

                    $rating_sum = 0;
                    $average_rating = 0;
                    $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                    if ($rating_sum > 0) {
                        $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                        $average_rating = $rating_sum / $total_reviews;
                    }

                    $course['teacher']->average_rating = $average_rating;
                    $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                    unset($course['teacher']['teacher_qualification']);
                }

                $progress = 0;
                foreach ($cancelled_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);

                    $rating_sum = 0;
                    $average_rating = 0;
                    $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                    if ($rating_sum > 0) {
                        $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                        $average_rating = $rating_sum / $total_reviews;
                    }

                    $course['teacher']->average_rating = $average_rating;
                    $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                    unset($course['teacher']['teacher_qualification']);
                }

                $progress = 0;
                foreach ($completed_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);

                    $rating_sum = 0;
                    $average_rating = 0;
                    $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                    if ($rating_sum > 0) {
                        $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                        $average_rating = $rating_sum / $total_reviews;
                    }

                    $course['teacher']->average_rating = $average_rating;
                    $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                    unset($course['teacher']['teacher_qualification']);
                }

                $progress = 0;
                if ($lastActivity_course != null) {
                    $completed_classes = $lastActivity_course->course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $lastActivity_course->course->total_classes) * 100;
                    $lastActivity_course->course->progress = round($progress);

                    $rating_sum = 0;
                    $average_rating = 0;
                    $rating_sum = UserFeedback::where('receiver_id', $lastActivity_course->course->teacher_id)->sum('rating');
                    if ($rating_sum > 0) {
                        $total_reviews = UserFeedback::where('receiver_id', $lastActivity_course->course->teacher_id)->count();
                        $average_rating = $rating_sum / $total_reviews;
                    }

                    $lastActivity_course->course['teacher']->average_rating = $average_rating;
                    $lastActivity_course->course['teacher']->tag_line = $lastActivity_course->course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $lastActivity_course->course['teacher']['teacher_qualification'][0]->degree_field;
                    unset($lastActivity_course->course['teacher']['teacher_qualification']);
                }


                return response()->json([
                    'success' => true,
                    'programs' => $programs,
                    'field_of_studies' => $fieldOfStudies,
                    'countries' =>  $countries,
                    'lastActivity_course' => $lastActivity_course != null ? [$lastActivity_course->course] : [],
                    'active_courses' =>  $active_courses,
                    'cancelled_courses' =>  $cancelled_courses,
                    'completed_courses' => $completed_courses,

                ]);
            }
        } else {

            $program = Program::where('code', $request->program)->first();

            $active_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'classes')->whereIn('id', $classroom)->whereIn('status', ['pending', 'active', 'inprogress'])->orderBy('id', 'desc')->get();
            $cancelled_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->orderBy('id', 'desc')->get();
            $completed_courses = Course::with('subject.country', 'language', 'program', 'teacher.teacher_qualification', 'classes')->whereIn('id', $classroom)->where('status', 'completed')->orderBy('id', 'desc')->get();
            $lastActivity_course = LastActivity::with('course.classes', 'course.subject', 'course.language', 'course.program', 'course.teacher.teacher_qualification')
                ->where('user_id', $token_user->id)
                ->where('role_name', $token_user->role_name)
                ->latest('updated_at')
                ->first();

            // $lastActivity_course = Course::with('subject', 'language', 'program', 'teacher', 'classes')->whereIn('id', $classroom)->orderBy('updated_at', 'desc')->first();


            $progress = 0;
            foreach ($active_courses as $course) {
                $completed_classes = $course->classes->where('status', 'completed')->count();
                $progress = ($completed_classes / $course->total_classes) * 100;
                $course->progress = round($progress);

                $rating_sum = 0;
                $average_rating = 0;
                $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                if ($rating_sum > 0) {
                    $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                    $average_rating = $rating_sum / $total_reviews;
                }

                $course['teacher']->average_rating = $average_rating;
                $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                unset($course['teacher']['teacher_qualification']);
            }

            $progress = 0;
            foreach ($cancelled_courses as $course) {
                $completed_classes = $course->classes->where('status', 'completed')->count();
                $progress = ($completed_classes / $course->total_classes) * 100;
                $course->progress = round($progress);

                $rating_sum = 0;
                $average_rating = 0;
                $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                if ($rating_sum > 0) {
                    $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                    $average_rating = $rating_sum / $total_reviews;
                }

                $course['teacher']->average_rating = $average_rating;
                $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                unset($course['teacher']['teacher_qualification']);
            }

            $progress = 0;
            foreach ($completed_courses as $course) {
                $completed_classes = $course->classes->where('status', 'completed')->count();
                $progress = ($completed_classes / $course->total_classes) * 100;
                $course->progress = round($progress);

                $rating_sum = 0;
                $average_rating = 0;
                $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
                if ($rating_sum > 0) {
                    $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
                    $average_rating = $rating_sum / $total_reviews;
                }

                $course['teacher']->average_rating = $average_rating;
                $course['teacher']->tag_line = $course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $course['teacher']['teacher_qualification'][0]->degree_field;
                unset($course['teacher']['teacher_qualification']);
            }

            $progress = 0;
            if ($lastActivity_course != null) {
                $completed_classes = $lastActivity_course->course->classes->where('status', 'completed')->count();
                $progress = ($completed_classes / $lastActivity_course->course->total_classes) * 100;
                $lastActivity_course->course->progress = round($progress);

                $rating_sum = 0;
                $average_rating = 0;
                $rating_sum = UserFeedback::where('receiver_id', $lastActivity_course->course->teacher_id)->sum('rating');
                if ($rating_sum > 0) {
                    $total_reviews = UserFeedback::where('receiver_id', $lastActivity_course->course->teacher_id)->count();
                    $average_rating = $rating_sum / $total_reviews;
                }

                $lastActivity_course->course['teacher']->average_rating = $average_rating;
                $lastActivity_course->course['teacher']->tag_line = $lastActivity_course->course['teacher']['teacher_qualification'][0]->degree_level . ' in ' . $lastActivity_course->course['teacher']['teacher_qualification'][0]->degree_field;
                unset($lastActivity_course->course['teacher']['teacher_qualification']);
            }



            return response()->json([
                'success' => true,
                'programs' => $programs,
                'lastActivity_course' => $lastActivity_course != null ? [$lastActivity_course->course] : [],
                'active_courses' =>  $active_courses,
                'cancelled_courses' =>  $cancelled_courses,
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

        $course = Course::with('subject', 'language', 'program', 'teacher', 'classes')->find($course_id);
        // return $course->field_of_study;

        //Add or update course to Last Activity
        $last_activity = LastActivity::updateOrCreate([
            'user_id'   => $token_user->id,
            'course_id' => $course_id,
            'field_of_study_id' => $course->field_of_study,
            'country_id' => $course->country_id,
            'program_id' => $course->program_id,
        ], [
            'user_id' => $token_user->id,
            'course_id' => $course_id,
            'role_name' => $token_user->role_name,
            'field_of_study_id' => $course->field_of_study,
            'country_id' => $course->country_id,
            'program_id' => $course->program_id,
        ]);

        $last_activity->updated_at = Carbon::now();
        $last_activity->update();

        //************ If class date and time passed then roll call attendence ************

        foreach ($course['classes'] as $class) {
            $classStart = Carbon::parse($class->start_date)->format('Y-m-d');
            if ($classStart <= $current_date) {
                $current_time = Carbon::now();
                $endTime = ($class->end_time);
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

        $todays_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'day', 'status', 'teacher_id')
            ->with('course', 'course.teacher', 'course.subject', 'course.student', 'teacher')
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

        $upcoming_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'day', 'status', 'teacher_id')
            // ->with('course', 'course.teacher', 'course.subject', 'course.student')
            ->with('student_attendence', function ($q) {
                $q->where('role_name', 'student');
            })
            ->with(['teacher_attendence' => function ($q) {
                $q->where('role_name', 'teacher');
            }])
            ->with('teacher')
            ->where('start_date', '>', $current_date)
            // ->with('course')
            ->where($userrole, $user_id)
            ->where('course_id', $course->id)
            ->get();

        $total_upcomingClasses = AcademicClass::where('start_date', '>', $current_date)
            ->where($userrole, $user_id)
            ->where('course_id', $course->id)
            ->count();

        $past_classes = AcademicClass::select('id', 'class_id', 'teacher_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'day', 'status')
            ->with('course', 'teacher', 'course.subject', 'course.student')
            ->with(['student_attendence' => function ($q) {
                $q->where('role_name', 'student');
            }])
            ->with(['teacher_attendence' => function ($q) {
                $q->where('role_name', 'teacher');
            }])
            // ->whereHas('attendence', function ($query) use ($user_id) {
            //     $query->where(['user_id' => $user_id]);
            // })
            ->where('start_date', '<', $current_date)
            // ->with('course')
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
        $inProgress = 0;
        if ($totalClases > 0) {
            $inProgress = ($completedClases / $totalClases) * 100;
        }


        return response()->json([
            'status' => true,
            'todays_date' =>  $todays_date,
            'total_previousClasses' => $total_pastClasses,
            'total_upcomingClasses' => $total_upcomingClasses,
            'remaining_classes' => $remaining_classes,
            'completed_classes' => $completed_classes,
            'progress' => round($inProgress),
            'course' =>  $course,
            'todays_classes' =>  $todays_classes,
            'upcoming_classes' => $upcoming_classes,
            'previous_classes' =>   $past_classes,
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

    //*********/ Student: Reschedule Class *********
    public function reschedule_class(Request $request)
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $rules = [
            'academic_class_id' =>  'required',
            'start_time' =>  'required',
            'end_time' =>  'required',
            'start_date' =>  'required', //|date|after:today
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

            $classes = AcademicClass::where('id', '!=', $academic_id)
                ->where('start_date', $request->start_date)
                ->where('teacher_id', $class->teacher_id)
                ->where('status', '!=', 'completed')
                ->get();

            if ($classes->isNotEmpty()) {
                foreach ($classes as $class) {
                    $db_startTime = date("G:i", strtotime($class->start_time));
                    $db_endTime = date("G:i", strtotime($class->end_time));
                    if (($start_time >= $db_startTime) && ($start_time <= $db_endTime) || ($end_time >= $db_startTime) && ($end_time <= $db_endTime)) {
                        return response()->json([
                            'status' => false,
                            'errors' => "Already have Scheduled Class at this time! please check teacher availability first",
                        ], 400);
                    }
                }
            }



            $availabilites = TeacherAvailability::where('user_id', $class->teacher_id)->where('day', $request->day)->get();
            if ($availabilites->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => "Teacher is not Available at this day! please check teacher availability first",
                ], 400);
            }


            $counter = 0;
            foreach ($availabilites as $availability) {
                $time_from = Carbon::parse($availability->time_from)->format('G:i');
                $time_to = Carbon::parse($availability->time_to)->format('G:i');

                if (($start_time >= $time_from) && ($end_time >= $time_from) && ($start_time <=  $time_to) && ($end_time <=  $time_to)) {
                    break;
                } else {
                    $counter++;
                    if (count($availabilites) == $counter) {
                        return response()->json([
                            'status' => false,
                            'message' => "Teacher is not Available at this time! please check teacher availability first",
                        ], 400);
                    }
                }
            }



            if ($totalDuration > 48) {
                $class = AcademicClass::where('id',  $request->academic_class_id)->first();
                // if class is scheduled by braincert
                if ($class->class_id != null) {
                    $class->start_time = $request->start_time;
                    $class->end_time = $request->end_time;
                    $class->start_date = $request->start_date;
                    $class->class_type = 'one-to-one';
                    $class->duration = $request->duration;


                    $apiURL = 'https://api.braincert.com/v2/updateclass';
                    $postInput = [
                        'apikey' => 'xKUyaLJHtbvBUtl3otJc',
                        'id' => $class->class_id,
                        'start_time' => Carbon::parse($request->start_time)->format('h:i a'),
                        'end_time' => Carbon::parse($request->end_time)->format('h:i a'),
                        'date' =>  Carbon::parse($request->start_date)->format('Y-m-d'),
                    ];

                    $client = new Client();
                    $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);
                    $responseBody = json_decode($response->getBody(), true);
                    if ($responseBody['status'] == "ok") {
                        $class->class_id = $responseBody['class_id'];
                        $class->status = 'scheduled';
                        $class->update();
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => $responseBody['error'],
                        ], 400);
                    }

                    $reschedule_class = new RescheduleClass();
                    $reschedule_class->rescheduled_by = $token_user->id;
                    $reschedule_class->academic_class_id = $request->academic_class_id;
                    $reschedule_class->course_id = $class->course_id;
                    $reschedule_class->course_id = $class->course_id;
                    $reschedule_class->start_time = $request->start_time;
                    $reschedule_class->end_time = $request->end_time;
                    $reschedule_class->day = $request->day;
                    $reschedule_class->status = 'rescheduled_by_teacher';
                    $reschedule_class->save();

                    return response()->json([
                        'status' => true,
                        'message' => "Class Rescheduled Successfully!",
                        'class' => $class,
                    ]);
                } else {

                    // if class is not scheduled by braincert
                    $class->start_time = $request->start_time;
                    $class->end_time = $request->end_time;
                    $class->start_date = $request->start_date;
                    $class->class_type = 'one-to-one';
                    $class->day = $request->day;
                    $class->duration = $request->duration;

                    $apiURL = 'https://api.braincert.com/v2/schedule';
                    $postInput = [
                        'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
                        'title' =>  'class',
                        'timezone' => 90,
                        'start_time' => Carbon::parse($request->start_time)->format('h:i a'),
                        'end_time' => Carbon::parse($request->end_time)->format('h:i a'),
                        'date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                        'currency' => "USD",
                        'ispaid' => null,
                        'is_recurring' => 0,
                        'repeat' => 0,
                        'weekdays' => null,
                        'end_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
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
                        $class->status = 'scheduled';
                        $class->update();
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => $responseBody['error'],
                        ], 400);
                    }

                    $reschedule_class = new RescheduleClass();
                    $reschedule_class->rescheduled_by = $token_user->id;
                    $reschedule_class->academic_class_id = $request->academic_class_id;
                    $reschedule_class->course_id = $class->course_id;
                    $reschedule_class->start_time = $request->start_time;
                    $reschedule_class->end_time = $request->end_time;
                    $reschedule_class->day = $request->day;
                    $reschedule_class->status = 'rescheduled_by_student';
                    $reschedule_class->save();

                    return response()->json([
                        'status' => true,
                        'message' => "Class Rescheduled Successfully!",
                        'class' => $class,
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "You dont have time to reschdule the class",
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

    //************* Student: Add class  *************
    public function addClass($course_id, Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $course = Course::with('classes')->find($course_id);
        if ($course->status == 'completed') {
            return response()->json([
                'status' => false,
                'message' => 'Course has been completed!',
            ], 400);
        }

        //Teacher Day Availability and requested classes Availability
        $availability = TeacherAvailability::where('user_id', $course->teacher_id)->get();
        $requestedClasses = json_decode(json_encode($request->classes));
        foreach ($requestedClasses as $class) {
            $weekAvailabilities = $availability->where('day', $class->day);
            if (count($weekAvailabilities) == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher not available at this day! please check teacher availability first',
                ], 400);
            }
            $flag = 0;
            foreach ($weekAvailabilities as $weekAvailability) {
                $start_time = Carbon::parse($weekAvailability->time_from)->format('G:i');
                $end_time = Carbon::parse($weekAvailability->time_to)->format('G:i');
                $request_startTime = Carbon::parse($class->start_time)->format('G:i');
                $request_endTime = Carbon::parse($class->end_time)->format('G:i');

                if (($request_startTime >= $start_time) && ($request_startTime <= $end_time) && ($request_endTime >= $start_time) && ($request_endTime <= $end_time)) {
                    break;
                } else {
                    $flag++;
                    if ($flag == count($weekAvailabilities))
                        return response()->json([
                            'status' => false,
                            'message' => 'Teacher not available at this time slot! please check teacher availability first',
                        ], 400);
                }
            }
            // Checking if teacher is booked
            $classes = AcademicClass::where('day', $class->day)->where('teacher_id', $course->teacher_id)
                ->where('start_date', $class->date)
                ->get();
            if (count($classes) > 0) {
                $flag = 0;
                foreach ($classes as $academicClass) {
                    $start_time = Carbon::parse($academicClass->start_time)->format('G:i');
                    $end_time = Carbon::parse($academicClass->end_time)->format('G:i');
                    $request_startTime = Carbon::parse($class->start_time)->format('G:i');
                    $request_endTime = Carbon::parse($class->end_time)->format('G:i');

                    if (($request_startTime >= $start_time) && ($request_startTime <= $end_time) || ($request_endTime >= $start_time) && ($request_endTime <= $end_time)) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Teacher is not available on this slot please check teacher availability first',
                        ], 400);
                    }
                }
            }
        }

        $course->total_classes = $course->total_classes + $request->total_classes;
        $course->total_price = $course->total_price + $request->total_price;
        $course->total_hours = $course->total_hours + $request->total_hours;
        $counter = count($course['classes']) + 1;

        foreach ($requestedClasses as $reqClass) {
            $academicClass = new AcademicClass();
            $academicClass->start_date = $reqClass->date;
            $academicClass->end_date = $reqClass->date;
            $academicClass->day = $reqClass->day;
            $academicClass->start_time = $reqClass->start_time;
            $academicClass->end_time = $reqClass->end_time;
            $academicClass->duration = $reqClass->duration;
            $academicClass->status = 'pending';
            $academicClass->student_id = $token_user->id;
            $academicClass->teacher_id = $course->teacher_id;
            $academicClass->class_type = $course->classes[0]->class_type;
            $academicClass->course_id = $course_id;
            $academicClass->class_paradigm = "added";
            $academicClass->save();

            /// Curl Implementation

            $apiURL = 'https://api.braincert.com/v2/schedule';
            $postInput = [
                'apikey' =>  "xKUyaLJHtbvBUtl3otJc",
                'title' =>  'class' . $counter,
                'timezone' => 90,
                'start_time' => $academicClass->start_time,
                'end_time' => $academicClass->end_time,
                'date' => $academicClass->start_date,
                'currency' => "USD",
                'ispaid' => null,
                'is_recurring' => 0,
                'repeat' => 1,
                'weekdays' => $reqClass->day,
                'end_date' => $academicClass->end_date,
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
                $academicClass->title = 'class' . $counter;
                $academicClass->lesson_name = 'lesson' . $counter;
                $academicClass->class_id = $responseBody['class_id'];
                $academicClass->status = "scheduled";
                $course->status = "active";
                $course->update();
                $academicClass->save();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $responseBody['error'],
                ], 400);
            }
            $counter++;
        }
        $course->update();

        $course = Course::with('classes')->find($course_id);

        return response()->json([
            'status' => true,
            'message' => 'Class Added Successfully',
            'course' => $course,
        ]);
    }

    public function makeupClassAvailability($class_id, Request $request)
    {
        $rules = [
            'start_date' =>  'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => false,
                'errors' => $errors,
            ], 400);
        }

        $day = Carbon::parse($request->start_date)->format('D');
        if ($day == 'Sun') {
            $day = 1;
        } elseif ($day == 'Mon') {
            $day = 2;
        } elseif ($day == 'Tue') {
            $day = 3;
        } elseif ($day == 'Wed') {
            $day = 4;
        } elseif ($day == 'Thu') {
            $day = 5;
        } elseif ($day == 'Fri') {
            $day = 6;
        } else {
            $day = 7;
        }

        $academic_class = AcademicClass::find($class_id);
        $availabilities = TeacherAvailability::where('user_id', $academic_class->teacher_id)->where('day', $day)->get();
        if ($availabilities == []) {
            return response()->json([
                'status' => false,
                'errors' => 'Teacher Not Available',
            ], 400);
        }
        $final_availabilities =  [];
        $classes = AcademicClass::where('start_date', $request->start_date)->where('teacher_id', $academic_class->teacher_id)->get();
        //if no academic classes found
        if (count($classes) == 0) {
            return response()->json([
                'status' => true,
                'message' => "Makeup Class Scheduled",
                'day' => $day,
                // 'availabilities' => $availabilities,
                'availabilities' => $availabilities,
                // 'classes' => $classes,
            ]);
        }


        //if classes has some data
        foreach ($availabilities as $availability) {
            $availability_time_from =  Carbon::parse($availability->time_from)->format("G:i");
            $availability_time_to = Carbon::parse($availability->time_to)->format("G:i");
            $counter = 0;
            foreach ($classes as $class) {
                $class_start_time = Carbon::parse($class->start_time)->format("G:i");
                $class_end_time =  Carbon::parse($class->end_time)->format("G:i");
                // comparing times
                if (($class_start_time >= $availability_time_from) && ($class_end_time <= $availability_time_to)) {
                } else {
                    $counter++;
                    if (count($classes) == $counter) {
                        array_push($final_availabilities, $availability);
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => "Available Slots for Makeup Class",
            'day' => $day,
            // 'availabilities' => $availabilities,
            'availabilities' => $final_availabilities,
            // 'classes' => $classes,
        ]);
    }

    public function makeupClass($class_id, Request $request)
    {
        $rules = [
            'start_date' =>  'required',
            'start_time' =>  'required',
            'end_time' =>  'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => false,
                'errors' => $errors,
            ], 400);
        }

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $day = Carbon::parse($request->start_date)->format('D');
        if ($day == 'Sun') {
            $day = 1;
        } elseif ($day == 'Mon') {
            $day = 2;
        } elseif ($day == 'Tue') {
            $day = 3;
        } elseif ($day == 'Wed') {
            $day = 4;
        } elseif ($day == 'Thu') {
            $day = 5;
        } elseif ($day == 'Fri') {
            $day = 6;
        } else {
            $day = 7;
        }

        $academic_class = AcademicClass::find($class_id);
        $availabilities = TeacherAvailability::where('user_id', $academic_class->teacher_id)->where('day', $day)->get();

        $request_time_from =  Carbon::parse($request->start_time)->format("G:i");
        $request_time_to = Carbon::parse($request->end_time)->format("G:i");
        $counter = 0;
        //Checking if teacher has available at given time
        foreach ($availabilities as $availability) {
            $availability_time_from =  Carbon::parse($availability->time_from)->format("G:i");
            $availability_time_to = Carbon::parse($availability->time_to)->format("G:i");
            if (($request_time_from >= $availability_time_from) && ($request_time_to <= $availability_time_to)) {
                $counter++;
            }
        }
        if ($counter < 1) {
            return response()->json([
                'status' => false,
                'error' => 'Teacher Not Available',
            ], 400);
        }
        //Checking if teacher has no class at given time
        $flag = 0;
        $classes = AcademicClass::where('id', '!=', $academic_class->id)->where('start_date', $request->start_date)->get();
        if (count($classes) > 0) {
            foreach ($classes as $class) {
                $class_start_time =  Carbon::parse($class->start_time)->format("G:i");
                $class_end_time = Carbon::parse($class->end_time)->format("G:i");
                if (($request_time_from >= $class_start_time) && ($request_time_to <= $class_end_time)) {
                    return response()->json([
                        'status' => false,
                        'error' => 'Teacher Not Available at this time',
                    ], 400);
                }
            }
        }

        $academic_class->start_date = $request->start_date;
        $academic_class->end_date = $request->start_date;
        $academic_class->start_time = $request->start_time;
        $academic_class->end_time = $request->end_time;
        $academic_class->day = $day;


        $apiURL = 'https://api.braincert.com/v2/schedule';
        $postInput = [
            'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
            'title' =>   $academic_class->title,
            'timezone' => 90,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'date' => $request->start_date,
            'currency' => "USD",
            'ispaid' => null,
            'is_recurring' => 0,
            'repeat' => 0,
            'weekdays' => $day,
            'end_date' => $request->start_date,
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
            $academic_class->class_id = $responseBody['class_id'];
            $academic_class->status = "scheduled";

            $makeup_class = new MakeupClass();
            $makeup_class->academic_class_id = $academic_class->id;
            $makeup_class->course_id = $academic_class->course_id;
            $makeup_class->user_id =  $token_user->id;
            $makeup_class->save();

            $academic_class->update();
        } else {
            return response()->json([
                'status' => false,
                'error' => $responseBody['error'],
            ], 400);
        }

        $student = User::findOrFail($token_user->id);
        $teacher = User::findOrFail($academic_class->teacher_id);
        $teacher_message = "Makeup class has been added!";
        $student_message = "Makeup class Added Successfully!";

        // Emails and Notifications
        // event(new MakeupClassEvent($student->id, $student, $student_message, $academic_class));
        // event(new MakeupClassEvent($teacher->id, $teacher, $teacher_message, $academic_class));
        // dispatch(new MakeupClassJob($student->id, $student, $student_message, $academic_class));
        // dispatch(new MakeupClassJob($teacher->id, $teacher, $teacher_message, $academic_class));

        return response()->json([
            'status' => true,
            'message' => "Makeup Class Scheduled",
            'academic_class' => $academic_class,
        ]);
    }

    public function class_recording(Request $request, $academic_class_id)
    {
        $class = AcademicClass::find($academic_class_id);
        /// Curl Implementation
        $apiURL = 'https://api.braincert.com/v2/getclassrecording';
        $postInput = [
            'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
            'class_id' =>  $class->class_id,
        ];

        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);
        return $responseBody;
        // if ($responseBody['status'] == "ok") {
        //     return response()->json([
        //         'success' => true,
        //         'message' => "Class Recordings!",
        //         'class' => $class,
        //     ]);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => $responseBody['error'],
        //     ], 400);
        // }
    }

    public function teacher_replacement(Request $request, $course_id)
    {

        $rules = [
            'teacher_id' =>  'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => false,
                'errors' => $errors,
            ], 400);
        }


        $course = Course::find($course_id);
        $course->teacher_id = $request->teacher_id;
        $course->status = 'pending';
        $course->update();

        $classrooms = ClassRoom::where('course_id', $course_id)->get();
        foreach ($classrooms as $classroom) {
            $classroom->teacher_id = $request->teacher_id;
            $classroom->status = 'pending';
            $classroom->save();
        }

        $academic_classes = AcademicClass::where('course_id', $course_id)->where('status', 'canceled')->get();
        foreach ($academic_classes as $academic_class) {
            $academic_class->teacher_id = $request->teacher_id;
            $academic_class->status = 'pending';
        }

        $student = User::findOrFail($course->student_id);
        $teacher = User::findOrFail($course->teacher_id);

        // event(new SelectTeacherEvent($student->id, $student, $course, "Request Completed! Wait for Teacher Approval!"));
        // event(new SelectTeacherEvent($teacher->id, $teacher, $course, "New Course Requested!"));
        // dispatch(new SelectTeacherJob($student->id, $student, $course, "Request Completed! Wait for Teacher Approval!"));
        // dispatch(new SelectTeacherJob($teacher->id, $teacher, $course, "New Course Requested!"));

        return response()->json([
            'status' => true,
            'message' => "Your Request has been submitted! Wait for Teacher Approval!",
            'course' => $course,
        ]);
    }

    //*********/ Specific Class details *********
    public function classDetail(Request $request, $class_id)
    {
        $class = AcademicClass::findOrFail($class_id);
        return response()->json([
            'success' => true,
            'message' => 'Class Detail!',
            'class' => $class,
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
