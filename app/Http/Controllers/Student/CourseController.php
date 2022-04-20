<?php

namespace App\Http\Controllers\Student;

use App\Events\CancelCourse;
use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\CanceledCourse;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class CourseController extends Controller
{
    public function refundCourse($course_id, Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $service_fee = 1;

        $course = Course::with('classes')->findOrFail($course_id);
        $refundable_classes = [];
        $refundable_classes_id = [];
        $today = Carbon::today()->format('Y-m-d');
        $remaining_classes = $course['classes']->where('status', '!=', 'completed');
        $classes = $course['classes']->where('start_date', '>=', $today);
        $duration = 0;
        foreach ($classes as $class) {
            $now = Carbon::now();
            $classStartDate = Carbon::parse($class->start_date);
            $difference =   $now->diffInHours($classStartDate);
            if ($difference >= 72) {
                array_push($refundable_classes, $class);
                array_push($refundable_classes_id, $class->id);
                $duration = $duration + $class->duration;
            }
        }

        $non_refundable_classes = $course->classes->whereNotIn('id', $refundable_classes_id);

        $subject = Subject::find($course->subject_id);
        if ($request->has('complete_course')) {

            return response()->json([
                'status' => true,
                'total_classes' => count($course['classes']),
                'remaining_classes' => count($remaining_classes),
                'total_refundable_classes' => count($refundable_classes),
                'non_refundable_classes' => count($non_refundable_classes),
                'per_class_refund' =>  $subject->price_per_hour,
                'service_fee' => $service_fee,
                'total_duration' => $duration,
                'total_refunds' => $duration  * $subject->price_per_hour,
                'total_service_fee' => count($refundable_classes)  * $service_fee,
                'course' => $course,
            ]);
        }


        if ($request->has('selective_classes')) {

            return response()->json([

                'status' => true,
                'refundable_classes' => $refundable_classes,
                'non_refundable_classes' => $non_refundable_classes,
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

        $my_attendence = Attendance::with('class')->where('user_id', $token_user->id)->where('course_id', $course_id)->get();


        $teacher_attendence = Attendance::with('class')->where('user_id', $course->teacher_id)->where('course_id', $course_id)->get();
        return response()->json([
            'status' => true,
            'message' => "Course Attendence!",
            'completed_classes' => count($completed_classes),
            'remaining_classes' => count($remaining_classes),
            'my_attendence' => $my_attendence,
            'teacher_attendence' => $teacher_attendence,
            'course' => $course,
        ]);
    }

    public function teacherAttendence(Request $request, $course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $course = Course::with('classes', 'participants', 'participants.user')->findOrFail($course_id);
        $students =  $course->participants->pluck('student_id');
        $completed_classes = $course['classes']->where('staus', 'completed');
        $remaining_classes = $course['classes']->where('staus', '!=', 'completed');

        $my_attendence = Attendance::with('class')->where('user_id', $token_user->id)->where('course_id', $course_id)->get();

        $students_attendance = Attendance::with('class')->whereIn('user_id', $students)->where('course_id', $course_id)->get();
        return response()->json([
            'status' => true,
            'message' => "Course Attendence!",
            'completed_classes' => count($completed_classes),
            'remaining_classes' => count($remaining_classes),
            'students_attendance' => $students_attendance,
            'my_attendence' => $my_attendence,
            'course' => $course,
        ]);
    }

    public function refundClasses(Request $request)
    {
        $rules = [
            'course_id' =>  'required',
            'academic_classes' =>  'required',
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

        // $token_1 = JWTAuth::getToken();
        // $token_user = JWTAuth::toUser($token_1);
        $service_fee = 1;

        $course = Course::find($request->course_id);
        $classes = json_decode(json_encode($request->academic_classes));

        $duration = 0;
        foreach ($classes as $class) {
            $academic_class = AcademicClass::find($class->id);
            $duration = $duration + $academic_class->duration;
        }

        $subject = Subject::find($course->subject_id);
        return response()->json([
            'status' => true,
            'total_classes' => count($classes),
            'price_per_hour' => $subject->price_per_hour,
            'total_duration' => $duration,
            'service_fee' => $service_fee,
            'total_refund' =>  $duration * $subject->price_per_hour,
            'total_service_fee' =>  count($classes) * $service_fee,
        ]);
    }

    public function cancelCourseReason(Request $request)
    {
        $rules = [
            'academic_classes' =>  'required',
            'reason' =>  'required|string',
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

        $course = Course::find($request->course_id);
        $classes = json_decode(json_encode($request->academic_classes));
        $canceled_classes = [];
        foreach ($classes as $class) {
            $academic_class = AcademicClass::find($class->id);
            $academic_class->teacher_id = null;
            array_push($canceled_classes, $academic_class);

            $course = new CanceledCourse();
            $course->course_id = $academic_class->course_id;
            $course->academic_class_id = $class->id;
            $course->user_id = $token_user->id;
            $course->reason = $request->reason;
            $course->save();
            $academic_class->update();
        }

        $clasroom = ClassRoom::where('course_id', $course->id)->get();
        foreach ($clasroom as $room) {
            $room->status = 'canceled';
            $room->update();
        }
        return response()->json([
            'status' => true,
            'reason' => $request->reason,
            'canceledclasses' => $canceled_classes,
        ]);
    }
}
