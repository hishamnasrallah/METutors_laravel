<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Feedback;
use App\Models\User;
use App\Models\UserFeedback;
use App\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class DashboardController extends Controller
{
    //*************** Teacher Dashboard ***************
    public function dashboard(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $user_id = $token_user->id;

        if ($token_user->role_name == 'teacher') {
            $userrole = 'teacher_id';
        } elseif ($token_user->role_name == 'student') {
            $userrole = 'student_id';
        }

        $endDate = 0;
        $todays_date = Carbon::now()->format('d-M-Y [l]');
        $current_date = Carbon::now()->format('Y-m-d');
        if (count($request->all()) >= 1) {
            // return "not null";
            $startDate = Carbon::today()->format('Y-m-d');

            if ($request->search_query == '7days') {
                $endDate = Carbon::today()->subDays(7);
            }
            if ($request->search_query == '1month') {
                $endDate = Carbon::today()->subDays(30);
            }
            if ($request->search_query == '3months') {
                $endDate = Carbon::today()->subDays(90);
            }
            if ($request->search_query == '1year') {
                $endDate = Carbon::today()->subDays(365);
            }




            // $classes = AcademicClass::whereBetween('start_date', [$endDate, $startDate])->get();


            $course_details=[];
            $completed_courses = Course::with('subject', 'student', 'program', 'classes','feedbacks')->where('status', 'completed')->whereBetween('created_at', [$endDate, $current_date])->where($userrole, $user_id)->get();
            foreach ($completed_courses as $course) {
                $completed_classes = 0;
                $remaining_classes = 0;

                $completed_classes = count($course['classes']->where('status','completed'));

                $total_classes = count($course['classes']);
                if ($total_classes > 0) {
                    $percentage = ($completed_classes / $total_classes) * 100;
                }
                $total_rating = 0;
                $total_reviews = count($course['feedbacks']);

                if( $total_reviews > 0){
                    $total_rating = $course['feedbacks']->sum('rating')/$total_reviews;
                }

                array_push($course_details,[
                    'course'=>$course,
                    'percent_completed'=>$percentage,
                    'total_reviews'=> count($course['feedbacks']),
                    'total_rating'=> $total_rating,
                ]);
            }
            $todays_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'status')->with('teacher', 'course', 'course.subject', 'course.student', 'course.teacher', 'course.program')->where('start_date', $current_date)->where($userrole, $user_id)->get();
            $total_classes = AcademicClass::where($userrole, $user_id)->whereBetween('created_at', [$endDate, $current_date])->count();
            $attended_classes = Attendance::where('user_id', $user_id)->whereBetween('created_at', [$endDate, $current_date])->count();
            $total_payment = Course::whereBetween('created_at', [$endDate, $current_date])->where($userrole, $user_id)->sum('total_price');

            $overall_progress = 0;
            $attendence_rate = 100;
            if ($attended_classes > 0) {
                $overall_progress = ($attended_classes / $total_classes) * 100;
                if ($attended_classes == $total_classes) {
                    $attendence_rate = 100;
                } else {
                    $attendence_rate = ($attended_classes / $total_classes) * 100;
                }
            }
            if($overall_progress == 0){
                $overall_progress = 100;
            }

            return response()->json([
                "status" => true,
                "message" => "todays classes",
                "todays_date" => $todays_date,
                "attendence_rate" => ceil($attendence_rate),
                "total_progress" => ceil($overall_progress),
                "total_payment" => $total_payment,
                "total_completed_courses" => count($completed_courses),
                "todays_classes" => $todays_classes,
                "completed_courses" => $course_details,
            ]);
        } else {

            $course_details=[];
            $completed_courses = Course::with('subject', 'student', 'program', 'classes','feedbacks')->where('status', 'completed')->where($userrole, $user_id)->get();
            foreach ($completed_courses as $course) {
                $completed_classes = 0;
                $remaining_classes = 0;

                $completed_classes = count($course['classes']->where('status','completed'));


                $total_classes = count($course['classes']);
                if ($total_classes > 0) {
                    $percentage = ($completed_classes / $total_classes) * 100;
                }
                $total_rating = 0;
                $total_reviews = count($course['feedbacks']);

                if( $total_reviews > 0){
                    $total_rating = $course['feedbacks']->sum('rating')/$total_reviews;
                }


                array_push($course_details,[
                    'course'=>$course,
                    'percent_completed'=>$percentage,
                    'total_reviews'=> count($course['feedbacks']),
                    'total_rating'=> $total_rating,
                ]);
            }
            $todays_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'status')->with('course','teacher','course.subject', 'course.student','course.teacher', 'course.program')->where('start_date', $current_date)->where($userrole, $user_id)->get();
            $total_classes = AcademicClass::where($userrole, $user_id)->count();
            $attended_classes = Attendance::where('user_id', $user_id)->count();
            $total_payment = Course::where($userrole, $user_id)->sum('total_price');

            $overall_progress = 0;
            $attendence_rate = 100;
            if ($attended_classes > 0) {
                $overall_progress = ($attended_classes / $total_classes) * 100;
                if ($attended_classes == $total_classes) {
                    $attendence_rate = 100;
                } else {
                    $attendence_rate = ($attended_classes / $total_classes) * 100;
                }
            }
            if($overall_progress == 0){
                $overall_progress = 100;
            }


            return response()->json([
                "status" => true,
                "message" => "todays classes",
                "todays_date" => $todays_date,
                "attendence_rate" => ceil($attendence_rate),
                "total_progress" => ceil($overall_progress),
                "total_payment" => $total_payment,
                "total_completed_courses" => count($completed_courses),
                "todays_classes" => $todays_classes,
                "completed_courses" => $course_details,
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
            $message->from('metutorsmail@gmail.com', 'MeTutor');
        });

        //********* Sending Invoive Email ends **********//
        return response()->json([
            'status' => true,
            'message' => "Invoice Email sent Successfully",
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
            'message' => 'Profile Updated Successfully!'
        ]);
    }
}