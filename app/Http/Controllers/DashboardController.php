<?php

namespace App\Http\Controllers;

use App\Events\UpdateProfile;
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
use stdClass;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    //*************** Teacher Dashboard ***************
    public function teacher_dashboard(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $user_id = $token_user->id;

        $endDate = 0;
        $todays_date = Carbon::now()->format('d-M-Y [l]');

        if (count($request->all()) >= 1) {
            // return "not null";
            $startDate = Carbon::now();

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



            $classes = AcademicClass::whereBetween('start_date', [$endDate, $startDate])->get();
            $current_date = Carbon::now();

            $total_courses = Course::whereBetween('created_at', [$endDate, $current_date])->where('teacher_id', $user_id)->where('course_code', '!=', null)->count();
            $total_last_courses = Course::whereBetween('created_at', [$compareDate, $endDate])->where('teacher_id', $user_id)->count();
            //Total courses Growth
            $courses_growth = 0;
            $courses_last_count = 0;
            $greater = 0;
            $greater = $total_courses > $total_last_courses ? $total_courses : $total_last_courses;
            if ($greater > 0 && $token_user->created_at <= $compareDate) {
                $courses_growth = (($total_last_courses - $total_courses) / $greater) * 100;
                $courses_last_count = $total_last_courses;
            }

            $total_completed_courses = Course::whereBetween('created_at', [$endDate, $current_date])->where('teacher_id', $user_id)->where('status', 'completed')->count();
            $total_last_completed_courses = Course::whereBetween('created_at', [$compareDate, $endDate])->where('teacher_id', $user_id)->where('status', 'completed')->count();
            //Total completed courses Growth
            $completed_courses_growth = 0;
            $completed_courses_last_count = 0;
            $greater = 0;
            $greater = $total_completed_courses > $total_last_completed_courses ? $total_completed_courses : $total_last_completed_courses;
            if ($greater > 0 && $token_user->created_at <= $compareDate) {
                $completed_courses_growth = (($total_last_completed_courses - $total_completed_courses) / $greater) * 100;
                $completed_courses_last_count = $total_last_completed_courses;
            }
            // return $endDate . ' - ' . $current_date;

            $newly_assigned_courses = Course::with('subject.country', 'student', 'program', 'classes')
                ->whereBetween('created_at', [$endDate, $current_date])
                // ->where('created_at', '>=', $endDate)
                // ->where('created_at', '<=', $current_date)
                ->where('teacher_id', $user_id)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();

            $todays_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", "status", "duration")
                ->with('course', 'course.subject.country', 'course.student', 'course.program', 'attendence')
                ->where('start_date', $current_date)->where('teacher_id', $user_id)->where('status', '!=', 'pending')
                ->orderBy('start_time', 'asc')->get();

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
            $feedbacks = UserFeedback::with('course', 'sender', 'feedback')
                ->whereBetween('created_at', [$endDate, $current_date])
                ->where('receiver_id', $user_id)
                ->orderBy('id','desc')
                ->get();
            $last_feedbacks = UserFeedback::with('course', 'sender', 'feedback')
                ->whereBetween('created_at', [$compareDate, $endDate])
                ->where('receiver_id', $user_id)
                // ->groupBy('')
                ->get();

            //Feedbacks Growth
            $feedbacks_growth = 0;
            $kudos_points_last_count = 0;
            $greater = 0;
            $greater = count($feedbacks) > count($last_feedbacks) ? count($feedbacks) : count($last_feedbacks);
            if ($greater > 0 && $token_user->created_at <= $compareDate) {
                $feedbacks_growth = ((count($last_feedbacks) - count($feedbacks)) / $greater) * 100;
                $kudos_points_last_count = count($last_feedbacks);
            }


            $total_newly_courses = Course::whereBetween('created_at', [$endDate, $current_date])->where('teacher_id', $user_id)->where('status', 'pending')->count();
            $total_last_newly_courses = Course::whereBetween('created_at', [$compareDate, $endDate])->where('teacher_id', $user_id)->where('status', 'pending')->count();
            //Newly assigned courses Growth
            $newly_courses_growth = 0;
            $greater = 0;
            $greater = $total_newly_courses > $total_last_newly_courses ? $total_newly_courses : $total_last_newly_courses;
            if ($greater > 0 && $token_user->created_at <= $compareDate) {
                $newly_courses_growth = ($total_last_newly_courses / $total_newly_courses) * 100;
                $newly_courses_last_count = $total_last_newly_courses;
            }


            $missed_classes = AcademicClass::where('teacher_id', $user_id)->where('start_date', '<', $current_date)->whereBetween('created_at', [$endDate, $current_date])->where('status', '!=', 'completed')->count();

            $feedbacks = $feedbacks->groupBy(['course_id', 'sender_id']);
            //converted to all feedbacks per course 
            $points_array = [];
            $sum_feedback = 0;
            foreach ($feedbacks as $feedback) {
                // converted to a single user feedback
                foreach ($feedback as $user_feedback) {
                    $points_detail = new stdClass();
                    $points_detail->student_name = $user_feedback[0]->sender->first_name . ' ' . $user_feedback[0]->sender->last_name;
                    $points_detail->avatar = $user_feedback[0]->sender->avatar;
                    $points_detail->course_name = $user_feedback[0]->course->course_name;
                    $points_detail->course_date = $user_feedback[0]->course->created_at->format('d M Y');
                    $points_detail->kudos_points = $user_feedback->sum('kudos_points');
                    $sum_feedback = $sum_feedback + $user_feedback->sum('kudos_points');
                    array_push($points_array, $points_detail);
                }
            }



            return response()->json([
                "status" => true,
                "message" => "todays classes",
                "todays_date" => $todays_date,
                "courses_growth" => round($courses_growth),
                "courses_last_count" => $total_last_courses,
                "total_courses" => $total_courses,
                "total_completed_courses" => $total_completed_courses,
                "completed_courses_growth" => round($completed_courses_growth),
                "completed_courses_last_count" => $total_last_completed_courses,
                "kudos_points" => $sum_feedback,
                "kudos_points_growth" => round($feedbacks_growth),
                "kudos_points_last_count" => count($last_feedbacks),
                "feedbacks" =>  $this->paginate($points_array, $request->per_page ?? 10),
                "newly_assigned_courses" => $newly_assigned_courses,
                "total_newly_courses" => $total_newly_courses,
                "newly_courses_growth" => $newly_courses_growth,
                "newly_courses_last_count" => $total_last_newly_courses,
                "missed_classes" => $missed_classes,
                "todays_classes" => $todays_classes,
            ]);
        } else {

            $classes = AcademicClass::all();
            $current_date = Carbon::now()->format('Y-m-d');

            $total_courses = Course::where('teacher_id', $user_id)->count();
            $total_completed_courses = Course::where('status', 'completed')->where('teacher_id', $user_id)->count();
            $todays_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'status')
                ->with('course', 'course.subject.country', 'course.student', 'course.program')
                ->where('start_date', $current_date)->where('teacher_id', $user_id)->where('status', '!=', 'pending')
                ->orderBy('start_time', 'asc')->get();
            $feedbacks = UserFeedback::with('course', 'sender', 'feedback')
                ->where('receiver_id', $user_id)
                ->orderBy('id','desc')
                // ->where('course_id', '183')
                ->get();



            $newly_assigned_courses = Course::with('subject.country', 'student', 'program', 'classes')
                ->where('teacher_id', $user_id)->where('status', 'pending')->get();

            $total_newly_courses = Course::where('teacher_id', $user_id)->where('status', 'pending')->count();
            $missed_classes = AcademicClass::where('teacher_id', $user_id)->where('start_date', '<', $current_date)->where('status', '!=', 'completed')->count();

            $feedbacks = $feedbacks->groupBy(['course_id', 'sender_id']);
            //converted to all feedbacks per course 
            $points_array = [];
            $sum_feedback = 0;

            foreach ($feedbacks as $feedback) {
                // converted to a single user feedback
                foreach ($feedback as $user_feedback) {
                    $points_detail = new stdClass();
                    $points_detail->student_name = $user_feedback[0]->sender->first_name . ' ' . $user_feedback[0]->sender->last_name;
                    $points_detail->avatar = $user_feedback[0]->sender->avatar;
                    $points_detail->course_name = $user_feedback[0]->course->course_name;
                    $points_detail->course_date = $user_feedback[0]->course->created_at->format('d M Y');
                    $points_detail->kudos_points = $user_feedback->sum('kudos_points');
                    $sum_feedback = $sum_feedback + $user_feedback->sum('kudos_points');
                    array_push($points_array, $points_detail);
                }
            }

            return response()->json([
                "status" => true,
                "message" => "todays classes",
                "todays_date" => $todays_date,
                "todays_classes" => $todays_classes,
                "total_courses" => $total_courses,
                "total_completed_courses" => $total_completed_courses,
                "kudos_points" => $sum_feedback,
                "feedbacks" => $this->paginate($points_array, $request->per_page ?? 10),
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

        $message = "Profile updated Successfully";

        //*********** Sending Rejection Email to Student  ************\\

        $user->update();

        //********* Sending Email to user **********
        // $user_email = $user->email;
        // // $custom_message = $custom_message;
        // $to_email = $user_email;

        // $data = array('email' =>  $user_email, 'user' => $user);

        // Mail::send('email.update_password', $data, function ($message) use ($to_email) {
        //     $message->to($to_email)->subject('Teacher Profile Updated Successfully on MEtutors');
        //    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        // });
        //********* Sending Email ends **********

        event(new UpdateProfile($user, $user->id, $message));

        return response()->json([
            'status' => true,
            'message' => 'Profile Updated Successfully!'
        ]);
    }

    public function kudos_detail()
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $user_id = $token_user->id;

        $feedbacks = UserFeedback::with('course', 'sender', 'feedback')
            ->where('receiver_id', $user_id)
            ->get();

        $feedbacks = $feedbacks->groupBy(['course_id', 'sender_id']);
        //converted to all feedbacks per course 
        $points_array = [];
        $sum_feedback = 0;
        foreach ($feedbacks as $feedback) {
            // converted to a single user feedback
            $sum_stars = 0;
            foreach ($feedback as $user_feedback) {
                // return $user_feedback;
                $points_detail = new stdClass();
                $points_detail->student_name = $user_feedback[0]->sender->first_name . ' ' . $user_feedback[0]->sender->last_name;
                $points_detail->avatar = $user_feedback[0]->sender->avatar;
                $points_detail->course_name = $user_feedback[0]->course->course_name;
                $points_detail->date = $user_feedback[0]->created_at->format('d M Y');
                $sum_stars =  $user_feedback->sum('rating') / count($user_feedback);
                $points_detail->stars = $sum_stars;
                $points_detail->review = $user_feedback[0]->review;
                $points_detail->kudos_points = $user_feedback->sum('kudos_points');
                $sum_feedback = $sum_feedback + $user_feedback->sum('kudos_points');
                array_push($points_array, $points_detail);
            }
        }

        return response()->json([
            "status" => true,
            "message" => "Teacher Kudos points",
            "kudos_points" => $sum_feedback,
            "points_detail" => $points_array,
        ]);
    }

    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }
}
