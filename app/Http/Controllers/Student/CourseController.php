<?php

namespace App\Http\Controllers\Student;

use App\Events\CancelCourse;
use App\Events\RequestTeacherEvent;
use App\Http\Controllers\Controller;
use App\Jobs\RequestTeacherJob;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\CanceledClass;
use App\Models\CanceledCourse;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\RefundClass;
use App\Models\RefundCourse;
use App\Models\User;
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
        $classes = $course['classes']->where('start_date', '>=', $today)->where('status', '!=', 'completed');
        $duration = 0;
        foreach ($classes as $class) {
            $now = Carbon::now();
            $classStartDate = Carbon::parse($class->start_date);
            $difference =   $now->diffInHours($classStartDate);
            if ($difference >= 72 &&  $now < $classStartDate) {
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
                'total_non_refundable_classes' => count($non_refundable_classes),
                'per_hour_price' =>  $subject->price_per_hour,
                'service_fee' => $service_fee,
                'total_duration' => $duration,
                'subtotal_refunds' => $duration  * $subject->price_per_hour,
                'total_refunds' => $duration  * $subject->price_per_hour - count($refundable_classes)  * $service_fee,
                'total_service_fee' => count($refundable_classes)  * $service_fee,
                'course' => $course,
            ]);
        }


        if ($request->has('selected_classes')) {

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

        $course = Course::find($course_id);

        return response()->json([
            'status' => true,
            'message' => "Classes cancelled successfully",
        ]);
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

    public function refundClasses(Request $request, $course_id)
    {
        $rules = [
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

        $course = Course::find($course_id);
        $classes = json_decode(json_encode($request->academic_classes));

        $duration = 0;
        foreach ($classes as $class) {
            $academic_class = AcademicClass::find($class);
            $duration = $duration + $academic_class->duration;
        }

        $subject = Subject::find($course->subject_id);
        return response()->json([
            'status' => true,
            'per_hour_price' => $subject->price_per_hour,
            'total_classes' => count($classes),
            'total_refundable_classes' => count($classes),
            'price_per_hour' => $subject->price_per_hour,
            'total_duration' => $duration,
            'service_fee' => $service_fee,
            'subtotal_refunds' =>  $duration * $subject->price_per_hour,
            'total_refunds' =>  $duration * $subject->price_per_hour - count($classes) * $service_fee,
            'total_service_fee' =>  count($classes) * $service_fee,

        ]);
    }

    public function cancelCourseReason(Request $request, $course_id)
    {
        $rules = [
            'is_complete' =>  'required',
            'reason' =>  'required|string',
        ];

        if ($request->is_complete == 0) {
            // $rules = [
            //     'academic_classes' =>  'required',
            // ];

            // $validator = Validator::make($request->academic_classes, $rules);

            // if ($validator->fails()) {
            //     $messages = $validator->messages();
            //     $errors = $messages->all();

            //     return response()->json([
            //         'status' => false,
            //         'errors' => $errors,
            //     ], 400);
            // }
            $request->validate([
                'academic_classes' =>  'required',
            ]);
        }


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

        $course = Course::find($course_id);
        if ($course->status == 'completed') {
            return response()->json([
                'status' => true,
                'message' => 'Access Denied! Course has been Completed!',
            ], 400);
        }
        $subject = Subject::find($course->subject_id);



        // return $request->is_complete;
        if ($request->is_complete == '0') {

            if ($course->status == 'cancelled_by_teacher' || $course->status == 'cancelled_by_student' || $course->status == 'cancelled_by_admin') {
                return response()->json([
                    'status' => true,
                    'message' => 'Course Already cancelled!',
                ], 400);
            }

            $classes = json_decode(json_encode($request->academic_classes));

            $canceledCourse = new CanceledCourse();
            $canceledCourse->cancelled_by = $token_user->role_name;
            $canceledCourse->student_id = $course->student_id;
            $canceledCourse->teacher_id = $course->teacher_id;
            $canceledCourse->course_id = $course->id;
            $canceledCourse->canceled_classes_count = count($classes);
            $canceledCourse->reason = $request->reason;
            $canceledCourse->save();



            $canceled_classes = [];
            $duration = 0;
            foreach ($classes as $class) {
                $academic_class = AcademicClass::findOrFail($class->id);
                // $academic_class->teacher_id = null;
                $academic_class->status = 'canceled';
                array_push($canceled_classes, $academic_class);

                $canceledClass = new CanceledClass();
                $canceledClass->academic_class_id = $academic_class->id;
                $canceledClass->canceled_course_id = $canceledCourse->id;
                $canceledClass->save();
                $academic_class->update();

                $duration = $academic_class->duration + $duration;
            }

            //Refund Course
            $refund_course = new RefundCourse();
            $refund_course->course_id =  $course->id;
            $refund_course->student_id = $course->student_id;
            $refund_course->teacher_id = $course->teacher_id;
            $refund_course->refunded_classes_count = count($classes);
            $refund_course->total_refunds = ($duration * $subject->price_per_hour) - (count($classes) * 1);
            $refund_course->total_service_fee = count($classes) * 1;
            $refund_course->save();
            //Refunded Classes
            foreach ($classes as $class) {
                $academic_class = AcademicClass::findOrFail($class->id);
                $refund_class = new RefundClass();
                $refund_class->refund_course_id = $refund_course->id;
                $refund_class->academic_class_id = $academic_class->id;
                $refund_class->service_fee = 1;
                $refund_class->refund = $academic_class->duration * $subject->price_per_hour;
                $refund_class->save();
            }
        }

        if ($request->is_complete == '1') {



            $refundable_classes = [];
            //finding refundable classes
            foreach ($course->classes as $class) {
                $now = Carbon::now();
                $classStartDate = Carbon::parse($class->start_date);
                $difference =   $now->diffInHours($classStartDate);
                if ($difference >= 72  &&  $now < $classStartDate) {
                    array_push($refundable_classes, $class);
                }
            }

            if (count($refundable_classes) == 0) {
                return response()->json([
                    'status' => false,
                    'message' => "Course has no class to refund!",
                ], 400);
            }

            $canceledCourse = new CanceledCourse();
            $canceledCourse->cancelled_by = $token_user->role_name;
            $canceledCourse->student_id = $course->student_id;
            $canceledCourse->teacher_id = $course->teacher_id;
            $canceledCourse->course_id = $course->id;
            $canceledCourse->canceled_classes_count = count($refundable_classes);
            $canceledCourse->reason = $request->reason;
            $canceledCourse->save();





            //Cancelling refundable classes
            $canceled_classes = [];
            $duration = 0;
            foreach ($refundable_classes as $class) {
                $academic_class = AcademicClass::find($class->id);
                // $academic_class->teacher_id = null;
                $academic_class->status = 'canceled';
                array_push($canceled_classes, $academic_class);

                $canceledClass = new CanceledClass();
                $canceledClass->academic_class_id = $academic_class->id;
                $canceledClass->canceled_course_id = $canceledCourse->id;
                $canceledClass->save();
                $academic_class->update();

                //Calculating duration 
                $academic_class = AcademicClass::find($class->id);
                $duration = $duration + $academic_class->duration;
            }

            //Refund Course
            $refund_course = new RefundCourse();
            $refund_course->course_id =  $course->id;
            $refund_course->student_id = $course->student_id;
            $refund_course->teacher_id = $course->teacher_id;
            $refund_course->refunded_classes_count = count($refundable_classes);
            $refund_course->total_refunds = ($duration * $subject->price_per_hour) - (count($refundable_classes) * 1);
            $refund_course->total_service_fee = count($refundable_classes) * 1;
            $refund_course->save();
            //Refunded Classes
            foreach ($refundable_classes as $class) {
                $refund_class = new RefundClass();
                $refund_class->refund_course_id = $refund_course->id;
                $refund_class->academic_class_id = $class->id;
                $refund_class->service_fee = 1;
                $refund_class->refund = $class->duration * $subject->price_per_hour;
                $refund_class->save();
            }
        }


        $clasroom = ClassRoom::where('course_id', $course->id)->get();
        foreach ($clasroom as $room) {
            $room->status = 'cancelled_by_student';
            $room->update();
        }

        $course->status = 'cancelled_by_student';
        // $course->teacher_id = null;
        $course->update();

        $course = Course::find($course_id);

        $admin = User::where('role_name', "admin")->first();
        $teacher = User::findOrFail($course->teacher_id);
        $student = User::findOrFail($course->student_id);

        $teacher_message = "Student Canceled the Course";
        $student_message = "Course Cancelled Successfully";
        $admin_message = "Student Canceled the Course";

        // event(new CancelCourseEvent($course, $course->teacher_id, $teacher_message, $teacher));
        // event(new CancelCourseEvent($course, $course->student_id, $student_message, $user));
        // event(new CancelCourseEvent($course, $admin->id, $admin_message, $admin));
        // dispatch(new CancelCourseJob($course, $course->teacher_id, $teacher_message, $teacher));
        // dispatch(new CancelCourseJob($course, $course->student_id, $student_message, $user));
        // dispatch(new CancelCourseJob($course, $admin->id, $admin_message, $admin));

        return response()->json([
            'status' => true,
            'message' => "Course Cancelled Successfully!",
            'reason' => $request->reason,
            'course' => $course,
            'canceledclasses' => $canceled_classes,
        ]);
    }

    public function request_admin($course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $course = Course::findOrFail($course_id);
        $course->status = 'requested_to_metutors';
        $course->update();

        $clasrooms = ClassRoom::where('course_id', $course_id)->get();
        foreach ($clasrooms as $clasroom) {
            $clasroom->status = 'requested_to_metutors';
            $clasroom->update();
        }

        foreach ($course->classes as $class) {
            $class->status = 'requested';
            $class->update();
        }

        $admin = User::where('role_name', 'admin')->first();
        $student = User::findOrFail($course->student_id);
        $student_message = "Requested Successfully sent to Metutors";
        $admin_message = "New Course !Please Assign Teacher";

        event(new RequestTeacherEvent($student->id, $student, $course, $student_message));
        event(new RequestTeacherEvent($admin->id, $admin, $course, $admin_message));
        dispatch(new RequestTeacherJob($student->id, $student, $course, $student_message));
        dispatch(new RequestTeacherJob($admin->id, $admin, $course, $admin_message));

        return response()->json([
            'status' => true,
            'message' => "Request Successfully Sent to METUTORS!",
            'course' => $course,
        ]);
    }
}
