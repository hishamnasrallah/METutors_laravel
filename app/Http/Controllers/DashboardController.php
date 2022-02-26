<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\Models\Course;
use App\Models\Feedback;
use App\Models\User;
use App\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    //*************** Teacher Dashboard ***************
    public function teacher_dashboard(Request $request)
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

        $startDate = Carbon::today()->format('Y-m-d');

        if($request->search_query == '7days'){
            $endDate = Carbon::today()->subDays(7);
        }
        if($request->search_query == '1month'){
            $endDate = Carbon::today()->subDays(30);
        }
        if($request->search_query == '3month'){
            $endDate = Carbon::today()->subDays(90);
        }
        if($request->search_query == '1year'){
            $endDate = Carbon::today()->subDays(365);
        }
        $user_id=auth('sanctum')->user()->id;
        $classes = AcademicClass::whereBetween('start_date', [$endDate,$startDate])->get();
        $current_date = Carbon::now()->format('Y-m-d');
      
        $total_courses = Course::whereBetween('created_at', [$endDate, $current_date])->count();
        $total_completed_courses = Course::whereBetween('created_at', [$endDate, $current_date])->where('status', 'completed')->count();
        
        $newly_assigned_courses = Course::with('subject')->whereBetween('created_at', [$endDate, $current_date])->get();

        $todays_classes = AcademicClass::select("start_date", "end_date", "start_time", "course_id")->with('course')->where('start_date', $current_date)->get();
        $feedbacks = Feedback::with('student','teacher','course')->whereBetween('created_at', [$endDate, $current_date])->where('teacher_id',$user_id)->where('feedback_by','!=',$user_id)->get();


        return response()->json([
            "status" => true,
            "message" => "todays classes",
            "todays_classes" => $todays_classes,
            "total_courses" => $total_courses,
            "total_completed_courses" => $total_completed_courses,
            "kudos_points" => $feedbacks,
            "newly_assigned_courses" => $newly_assigned_courses,
        ]);
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
            ]);
        }

        //*********** Sending Invoive Email  ************\\
        $user_email = $request->email;
        $invoiceData=$request->invoiceData;
        $to_email = $user_email;

        $data = array('email' =>  $user_email,'invoiceData' =>  $invoiceData);

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

    public function classes_dashboard(){

        $teacher_id=auth('sanctum')->user()->id;
        $current_date = Carbon::today()->format('Y-m-d');
        
        
        $todays_classes = AcademicClass::select("start_date", "end_date", "start_time", "course_id")
        ->with('course','course.subject')
        ->where('start_date', $current_date)
        ->with('course')
        ->where('teacher_id', $teacher_id)
        ->get();

        $upcoming_classes = AcademicClass::select("start_date", "end_date", "start_time", "course_id")
        ->with('course','course.subject')
        ->where('start_date','>', $current_date)
        ->with('course')
        ->where('teacher_id', $teacher_id)
        ->get();

        $total_upcomingClasses = AcademicClass::where('start_date','>', $current_date)
        ->where('teacher_id', $teacher_id)
        ->count();

        $past_classes = AcademicClass::select("start_date", "end_date", "start_time", "course_id")
        ->with('course','course.subject')
        ->where('start_date','<', $current_date)
        ->with('course')
        ->where('teacher_id', $teacher_id)
        ->get();

        $total_pastClasses = AcademicClass::where('start_date','<', $current_date)
        ->where('teacher_id', $teacher_id)
        ->count();
        
        return response()->json([
            'status' => true,
            'todays_classes' => $todays_classes,
            'total_upcomingClasses' => $total_upcomingClasses,
            'upcoming_classes' => $upcoming_classes,
            'past_classes' => $past_classes,
            'total_pastClasses' => $total_pastClasses,
        ]);
    }
}
