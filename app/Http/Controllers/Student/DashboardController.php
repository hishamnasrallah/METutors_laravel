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
use Illuminate\Support\Str;


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
        $current_date = Carbon::now();
        if (count($request->all()) >= 1) {
            // return "not null";
            $startDate = Carbon::today()->format('Y-m-d');

            if ($request->search_query == '7days') {
                $endDate = Carbon::today()->subDays(7);
                $compareDate = Carbon::today()->subDays(14);
            }
            if ($request->search_query == '1month') {
                $endDate = Carbon::today()->subMonth(1);
                $compareDate = Carbon::today()->subMonth(2);
            }
            if ($request->search_query == '3months') {
                $endDate = Carbon::today()->subMonth(3);
                $compareDate = Carbon::today()->subMonth(6);
            }
            if ($request->search_query == '1year') {
                $endDate = Carbon::today()->subYear(1);
                $compareDate = Carbon::today()->subYear(2);
            }




            // $classes = AcademicClass::whereBetween('start_date', [$endDate, $startDate])->get();


            $course_details = [];
            $completed_courses = Course::with('subject.country', 'student', 'program', 'classes', 'feedbacks')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$endDate, $current_date])
                ->where($userrole, $user_id)
                ->get();
            // return count($completed_courses);
            $last_completed_courses = Course::with('subject.country', 'student', 'program', 'classes', 'feedbacks')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$compareDate, $endDate])
                ->where($userrole, $user_id)
                ->get();
            // return count($last_completed_courses);
            //Completed Courses Growth
            $completed_courses_growth = 0;
            $greater = 0;
            $greater = count($completed_courses) > count($last_completed_courses) ? count($completed_courses) : count($last_completed_courses);
            // return $token_user->created_at;
            if ($greater > 0 && $token_user->created_at <= $compareDate) {
                $completed_courses_growth = ((count($last_completed_courses) - count($completed_courses)) / $greater) * 100;
            }


            foreach ($completed_courses as $course) {
                $completed_classes = 0;
                $remaining_classes = 0;

                $completed_classes = count($course['classes']->where('status', 'completed'));

                $total_classes = count($course['classes']);
                if ($total_classes > 0) {
                    $percentage = ($completed_classes / $total_classes) * 100;
                }
                $total_rating = 0;
                $total_reviews = count($course['feedbacks']);

                if ($total_reviews > 0) {
                    $total_rating = $course['feedbacks']->sum('rating') / $total_reviews;
                }

                array_push($course_details, [
                    'course' => $course,
                    'percent_completed' => $percentage,
                    'total_reviews' => count($course['feedbacks']),
                    'total_rating' => $total_rating,
                ]);
            }
            $todays_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'status', 'teacher_id')
                ->with('teacher', 'course', 'course.subject.country', 'course.student', 'course.teacher', 'course.program')
                ->where('start_date', $current_date)->where($userrole, $user_id)
                ->where('status', '!=', 'pending')
                ->orderBy('start_time', 'desc')
                ->get();
            $total_classes = AcademicClass::where($userrole, $user_id)->whereBetween('created_at', [$endDate, $current_date])->count();
            $attended_classes = Attendance::where('user_id', $user_id)->whereBetween('created_at', [$endDate, $current_date])->where('status', 'present')->count();
            //Attendence Growth
            $last_attended_classes = Attendance::where('user_id', $user_id)->whereBetween('created_at', [$compareDate, $endDate])->where('status', 'present')->count();
            $attendence_growth = 0;
            $greater = 0;
            $greater = $attended_classes > $last_attended_classes ? $attended_classes : $last_attended_classes;
            if ($greater > 0 && $token_user->created_at <= $compareDate) {
                $attendence_growth = (($last_attended_classes - $attended_classes) / $greater) * 100;
            }
            // return $current_date;
            // return $endDate.$Carbion(z);
            $total_payment = Course::whereBetween('created_at', [$endDate, $current_date])
                ->where($userrole, $user_id)->where('course_code', '!=', null)->sum('total_price');
            // return $total_payment = Course::whereBetween('created_at', [$endDate, $current_date])->where($userrole, $user_id)->sum('total_price');
            //Payment Growth
            $total_last_payment = Course::whereBetween('created_at', [$compareDate, $endDate])->where($userrole, $user_id)->sum('total_price');
            $payment_growth = 0;
            $greater = 0;
            $greater = $total_payment > $total_last_payment ? $total_payment : $total_last_payment;
            if ($greater > 0 && $token_user->created_at <= $compareDate) {
                $payment_growth = (($total_last_payment - $total_payment) / $greater) * 100;
            }

            $overall_progress = 0;
            $attendence_rate = 100;
            if ($total_classes > 0) {
                $overall_progress = ($attended_classes / $total_classes) * 100;
                if ($attended_classes == $total_classes) {
                    $attendence_rate = 100;
                } else {
                    $attendence_rate = ($attended_classes / $total_classes) * 100;
                }
            }

            // if ($overall_progress == 0) {
            //     $overall_progress = 100;
            // }

            $last_progress = 0;
            $last_attendence_rate = 100;
            if ($total_classes > 0) {
                $last_progress = ($last_attended_classes / $total_classes) * 100;
                if ($last_attended_classes == $total_classes) {
                    $attendence_rate = 100;
                } else {
                    $last_attendence_rate = ($last_attended_classes / $total_classes) * 100;
                }
            }

            // if ($last_progress == 0) {
            //     $last_progress = 100;
            // }

            $overall_progress_growth = 0;
            $greater = 0;
            $greater = $overall_progress > $last_progress ? $overall_progress : $last_progress;
            if ($token_user->created_at <= $compareDate && $greater > 0) {
                $overall_progress_growth = (($last_progress - $overall_progress) / $greater) * 100;
            }



            return response()->json([
                "status" => true,
                "message" => "todays classes",
                "todays_date" => $todays_date,
                "attendence_rate" => floatval(Str::limit($attendence_rate, 4, '')),
                "attendence_growth" => $attendence_growth,
                "attendence_rate_last_count" =>  $last_attendence_rate,
                "total_progress" => floatval(Str::limit($overall_progress, 4, '')),
                "total_progress_growth" => round($overall_progress_growth),
                "total_progress_last_count" => round($last_progress),
                "total_payment" => $total_payment,
                "payment_growth" => round($payment_growth),
                "payment_last_count" => round($total_last_payment),
                "total_completed_courses" => count($completed_courses),
                "completed_courses_growth" => round($completed_courses_growth),
                "completed_courses_last_count" => round(count($last_completed_courses)),
                "todays_classes" => $todays_classes,
                "completed_courses" => $course_details,
            ]);
        } else {

            $course_details = [];
            $completed_courses = Course::with('subject.country', 'student', 'program', 'classes', 'feedbacks')->where('status', 'completed')->where($userrole, $user_id)->get();
            foreach ($completed_courses as $course) {
                $completed_classes = 0;
                $remaining_classes = 0;

                $completed_classes = count($course['classes']->where('status', 'completed'));


                $total_classes = count($course['classes']);
                if ($total_classes > 0) {
                    $percentage = ($completed_classes / $total_classes) * 100;
                }
                $total_rating = 0;
                $total_reviews = count($course['feedbacks']);

                if ($total_reviews > 0) {
                    $total_rating = $course['feedbacks']->sum('rating') / $total_reviews;
                }


                array_push($course_details, [
                    'course' => $course,
                    'percent_completed' => $percentage,
                    'total_reviews' => count($course['feedbacks']),
                    'total_rating' => $total_rating,
                ]);
            }
            $todays_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'status')->with('course', 'teacher', 'course.subject.country', 'course.student', 'course.teacher', 'course.program')
                ->where('start_date', $current_date)->where($userrole, $user_id)
                ->where('status', '!=', 'pending')
                ->orderBy('start_time', 'asc')
                ->get();
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
            if ($overall_progress == 0) {
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
            'message' => 'Profile updated successfully!'
        ]);
    }
}
