<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JWTAuth;

class CourseController extends Controller
{
    public function refundCourse($course_id, Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $course = Course::with('classes')->findOrFail($course_id);
        $refundable_classes = [];
        $today = Carbon::today()->format('Y-m-d');
        $remaining_classes = $course['classes']->where('status', '!=', 'completed');
        $classes = $course['classes']->where('start_date', '>=', $today);
        foreach ($classes as $class) {
            $now = Carbon::now();
            $classStartDate = Carbon::parse($class->start_date);
            $difference =   $now->diffInHours($classStartDate);
            if ($difference >= 72) {
                array_push($refundable_classes, $class);
            }
            $non_refundable_classes = count($course['classes']) - count($refundable_classes);
        }

        if ($request->has('complete_course')) {

            return response()->json([
                'status' => true,
                'total_classes' => count($course['classes']),
                'remaining_classes' => count($remaining_classes),
                'total_refundable_classes' => count($refundable_classes),
                'non_refundable_classes' => $non_refundable_classes,
                'per_class_refund' => 7,
                'service_fee' => 2,
                'total_refunds' => $request->total_refunds,
                'course' => $course,
            ]);
        }


        if ($request->has('selected_classes')) {

            return response()->json([
                'status' => true,
                'total_classes' => count($course['classes']),
                'remaining_classes' => count($remaining_classes),
                'total_refundable_classes' => count($refundable_classes),
                'non_refundable_classes' => $non_refundable_classes,
                'per_class_refund' => 7,
                'service_fee' => 2,
                'total_refunds' => $request->total_refunds,
                'classes' => $course['classes'],
            ]);
        }
    }

    public function cancelCourse($course_id, Request $request)
    {
        $rules = [
            'reason' =>  'required',
            'total_funds' =>  'required',
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

        return $course = Course::with('classes')->findOrFail($course_id);
    }

    public function getCourseAttendence(Request $request, $course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $course = Course::with('classes', 'participants', 'participants.user')->findOrFail($course_id);
        $completed_classes = $course['classes']->where('staus', 'completed');
        $remaining_classes = $course['classes']->where('staus', '!=', 'completed');
        if ($request->has('my_attendance')) {
            $userAttendence = Attendance::where('user_id', $token_user->id)->where('course_id', $course_id)->get();
            return response()->json([
                'status' => true,
                'message' => "Course Attendence!",
                'completed_classes' => count($completed_classes),
                'remaining_classes' => count($remaining_classes),
                'my_attendence' => $userAttendence,
                'course' => $course,
            ]);
        }
        if ($request->has('teacher_attendance')) {

            $userAttendence = Attendance::where('user_id', $course->teacher_id)->where('course_id', $course_id)->get();
            return response()->json([
                'status' => true,
                'message' => "Course Attendence!",
                'completed_classes' => count($completed_classes),
                'remaining_classes' => count($remaining_classes),
                'teacher_attendence' => $userAttendence,
                'course' => $course,
            ]);
        }
    }

    
}
