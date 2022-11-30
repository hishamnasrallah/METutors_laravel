<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Feedback;
use App\Models\User;
use App\Models\UserFeedback;
use App\Models\UserPrefrence;
use App\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use stdClass;

class DashboardController extends Controller
{
    //*************** Teacher Dashboard ***************
    public function dashboard(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $user_id = $token_user->id;

        $endDate = 0;
        $todays_date = Carbon::now()->format('d-M-Y [l]');

        if (count($request->all()) >= 1) {
            // return "not null";
            $startDate = Carbon::today()->format('Y-m-d');

            if ($request->search_query == '7days') {
                $endDate = Carbon::today()->subDays(7);
            }
            if ($request->search_query == '1month') {
                $endDate = Carbon::today()->subMonth(1);
            }
            if ($request->search_query == '3months') {
                $endDate = Carbon::today()->subMonth(3);
            }
            if ($request->search_query == '1year') {
                $endDate = Carbon::today()->subYear(1);
            }


            $classes = AcademicClass::whereBetween('start_date', [$endDate, $startDate])->get();
            $current_date = Carbon::now()->format('Y-m-d');

            $total_courses = Course::whereBetween('created_at', [$endDate, $current_date])->where('teacher_id', $user_id)->count();
            $total_completed_courses = Course::whereBetween('created_at', [$endDate, $current_date])->where('teacher_id', $user_id)->where('status', 'completed')->count();

            $newly_assigned_courses = Course::with('subject', 'student', 'program', 'classes',)
                ->whereBetween('created_at', [$endDate, $current_date])->where('teacher_id', $user_id)->where('status', 'pending')->get();

            $todays_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", "status", "duration")->with('course', 'course.subject.country', 'course.student', 'course.program', 'attendence')->where('start_date', $current_date)->where('teacher_id', $user_id)->get();
            //checking if class has completed
            $currentTime = Carbon::now()->format('H:i:s');
            foreach ($todays_classes as $class) {
                // return $class;
                $classTime = Carbon::parse($class->end_time)->format('H:i:s');
                $attend = $class->attendence->where('user_id', $user_id);
                if ($currentTime >= $classTime &&  count($attend) > 0) {
                    $class->status = "completed";
                    $class->update();
                }
            }
            $feedbacks = UserFeedback::with('course', 'sender', 'feedback')->whereBetween('created_at', [$endDate, $current_date])->where('receiver_id', $user_id)->get();

            $total_newly_courses = Course::whereBetween('created_at', [$endDate, $current_date])->where('teacher_id', $user_id)->where('status', 'pending')->count();
            $missed_classes = AcademicClass::where('teacher_id', $user_id)->where('start_date', '<', $current_date)->whereBetween('created_at', [$endDate, $current_date])->where('status', '!=', 'completed')->count();
            return response()->json([
                "status" => true,
                "message" => "todays classes",
                "todays_date" => $todays_date,
                "todays_classes" => $todays_classes,
                "total_courses" => $total_courses,
                "total_completed_courses" => $total_completed_courses,
                "kudos_points" => $token_user->kudos_points,
                "feedbacks" => $feedbacks,
                "newly_assigned_courses" => $newly_assigned_courses,
                "total_newly_courses" => $total_newly_courses,
                "missed_classes" => $missed_classes,
            ]);
        } else {

            $classes = AcademicClass::all();
            $current_date = Carbon::now()->format('Y-m-d');

            $total_courses = Course::where('teacher_id', $user_id)->count();
            $total_completed_courses = Course::where('status', 'completed')->where('teacher_id', $user_id)->count();
            $todays_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'status')->with('course', 'course.subject.country', 'course.student', 'course.program')->where('start_date', $current_date)->where('teacher_id', $user_id)->get();
            $feedbacks = UserFeedback::with('course', 'sender', 'feedback')->where('receiver_id', $user_id)->get();

            $newly_assigned_courses = Course::with('subject', 'student', 'program', 'classes')
                ->where('teacher_id', $user_id)->where('status', 'pending')->get();

            $total_newly_courses = Course::where('teacher_id', $user_id)->where('status', 'pending')->count();
            $missed_classes = AcademicClass::where('teacher_id', $user_id)->where('start_date', '<', $current_date)->where('status', '!=', 'completed')->count();

            return response()->json([
                "status" => true,
                "message" => "todays classes",
                "todays_date" => $todays_date,
                "todays_classes" => $todays_classes,
                "total_courses" => $total_courses,
                "total_completed_courses" => $total_completed_courses,
                "kudos_points" => $token_user->kudos_points,
                "feedbacks" => $feedbacks,
                "newly_assigned_courses" => $newly_assigned_courses,
                "total_newly_courses" => $total_newly_courses,
                "missed_classes" => $missed_classes,
            ]);
        }
    }

    public function invoice_mail(Request $request)
    {
        $rules = [
            'email' => 'required',
            'invoiceData' => 'required',
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

        //*********** Sending Invoive Email  ************\\
        $user_email = $request->email;
        $invoiceData = $request->invoiceData;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'invoiceData' =>  $invoiceData);

        Mail::send('email.invoice_mail', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Invoice Email');
            $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });

        //********* Sending Invoive Email ends **********//
        return response()->json([
            'status' => true,
            'message' => "Invoice email sent successfully",
        ]);
    }

    public function classes_dashboard()
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $todays_date = Carbon::now()->format('d-M-Y [l]');

        $teacher_id = $token_user->id;
        $current_date = Carbon::today()->format('Y-m-d');


        $todays_classes = AcademicClass::select('title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration')
            ->with('course', 'course.subject.country')
            ->where('start_date', $current_date)
            ->with('course')
            ->where('teacher_id', $teacher_id)
            ->get();

        $upcoming_classes = AcademicClass::select('title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration')
            ->with('course', 'course.subject.country')
            ->where('start_date', '>', $current_date)
            ->with('course')
            ->where('teacher_id', $teacher_id)
            ->get();

        $total_upcomingClasses = AcademicClass::where('start_date', '>', $current_date)
            ->where('teacher_id', $teacher_id)
            ->count();

        $past_classes = AcademicClass::select('title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration')
            ->with('course', 'course.subject.country')
            ->where('start_date', '<', $current_date)
            ->with('course')
            ->where('teacher_id', $teacher_id)
            ->get();

        $total_pastClasses = AcademicClass::where('start_date', '<', $current_date)
            ->where('teacher_id', $teacher_id)
            ->count();

        return response()->json([
            'status' => true,
            'todays_date' =>  $todays_date,
            'todays_classes' => $todays_classes,
            'total_upcomingClasses' => $total_upcomingClasses,
            'upcoming_classes' => $upcoming_classes,
            'past_classes' => $past_classes,
            'total_pastClasses' => $total_pastClasses,
        ]);
    }

    public function update_teacherProfile(Request $request)
    {
        // return $request;
        $rules = [
            'image' => 'image|max:2048',
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

        $user = User::find($token_user->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->bio = $request->bio;

        if ($request->hasFile('image')) {
            //************ Profile image *********\\
            $imageName = date('YmdHis') . '.' . \request('image')->getClientOriginalExtension();
            \request('image')->move(public_path('assets/images/profile_images'), $imageName);
            $user->avatar = $imageName;
            //************ Profile image ends *********\\
        }
        $user->update();

        return response()->json([
            'status' => true,
            'message' => trans('api_messages.PROFILE_UPDATED_SUCCESSFULLY')
        ]);
    }


    public function profile()
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $user = \App\User::with('country', 'userSignature','userResume','userDegrees','userCertificates', 'teacherSpecifications', 'teacherQualifications', 'teacherAvailability', 'spokenLanguages', 'spokenLanguages.language', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject.country', 'teacher_interview_request')
            ->find($token_user->id);

        $prefrences = UserPrefrence::select('id', 'user_id', 'role_name', 'preferred_gender', 'teacher_language', 'efficiency')
            ->with('spoken_language')
            ->where('user_id', $token_user->id)
            ->get();

        $spoken_languages = [];
        $final_prefrences = new stdClass();
        if (count($prefrences) > 0) {
            $final_prefrences->preferred_gender = $prefrences[0]->preferred_gender;
            foreach ($prefrences as $key => $prefrence) {
                $language = new stdClass();
                $object = new stdClass();
                $language->id = $prefrence->spoken_language->id;
                $language->name = $prefrence->spoken_language->name;
                $object->efficiency =   $prefrence->efficiency;
                $object->language =  $language;
                array_push($spoken_languages, $object);
            }

            $final_prefrences->spoken_languages = $spoken_languages;
            $user->preferences = $final_prefrences;
        }

        return response()->json([
            'status' => true,
            'message' => "Teacher Profile!",
            'user' => $user
        ]);
    }
}
