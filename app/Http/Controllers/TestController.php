<?php

namespace App\Http\Controllers;

use App\Events\AcceptCourse;
use App\Events\AssignmentDeadlineEvent;
use App\Events\CancelCourse;
use App\Events\PreClassEvent;
use App\Events\RejectCourse;
use App\Events\StudentAcceptCourse;
use App\Events\TeacherReminderEvent;
use App\Events\TestEvent;
use App\Jobs\AcceptCourseJob;
use App\Jobs\PreClassJob;
use App\Jobs\TeacherReminderJob;
use App\Jobs\testJob;
use App\Models\AcademicClass;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\CanceledClass;
use App\Models\CanceledCourse;
use App\Models\ClassRoom;
use App\Models\ClassTopic;
use App\Models\Course;
use App\Models\Notification;
use App\Models\RejectedCourse;
use App\Models\Resource;
use App\Models\Topic;

use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserFeedback;
use JWTAuth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Location\Facades\Location;


use Illuminate\Http\Request;

class TestController extends Controller
{

    public function courses()
    {

        $courses = Course::whereIn('status', ['pending', 'payment_pending'])->get();
        $notifications = Notification::where('notifiable_id', '1149')
            ->where('read_at', null)
            ->count();

        $details['email'] = 'your_email@gmail.com';
        event(new TestEvent($details));
        dispatch(new testJob($details));

        return view('courses', compact('notifications', 'courses'));
    }

    public function acceptCourse($course_id)
    {
        // $token_1 = JWTAuth::getToken();
        // $token_user = JWTAuth::toUser($token_1);

        $course = Course::find($course_id);
        $user = User::find($course->student_id);
        $teacher = User::find($course->teacher_id);

        // $class = AcademicClass::where('course_id', $course->id)->get();
        $classes = AcademicClass::where('course_id', $course->id)->get();
        $counter = 1;
        foreach ($classes as $class) {

            if ($counter == 1) {
                $class->title = "Introduction|class1";
                $class->lesson_name = "introduction";
            } else {
                $class->title = "class" . $counter;
                $class->lesson_name = "lesson" . $counter;
            }



            /// Curl Implementation
            $apiURL = 'https://api.braincert.com/v2/schedule';
            $postInput = [
                'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
                'title' =>  'Introduction|class1',
                'timezone' => 90,
                'start_time' => $class->start_time,
                'end_time' => $class->end_time,
                'date' => $class->start_date,
                'currency' => "USD",
                'ispaid' => null,
                'is_recurring' => 0,
                'repeat' => 0,
                'weekdays' => null,
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
                $course->status = "active";
                $class->class_id = $responseBody['class_id'];
                $class->status = "scheduled";
                $course->status = "active";
                $course->teacher_status = "available";
                $course->update();
                $class->update();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $responseBody['error'],
                ], 400);
            }
            $counter++;
        }

        $clasroom = ClassRoom::where('course_id', $course_id)->get();
        foreach ($clasroom as $room) {
            $room->status = 'active';
            $room->update();
        }

        $teacher_message = 'Course Accepted Successfully!';
        $student_message = 'Teacher Accepted Your Course!';

        event(new AcceptCourse($course, $course->teacher_id, $teacher_message, $teacher));
        event(new AcceptCourse($course, $course->student_id, $student_message, $user));

        // dispatching jobs
        dispatch(new AcceptCourseJob($course, $course->teacher_id, $teacher_message, $teacher));
        dispatch(new AcceptCourseJob($course, $course->student_id, $student_message, $user));

        // return redirect()->back();
        return response()->json([
            'status' => true,
            'message' => 'Course Accepted!',
            'course' => $course,
        ]);
    }

    public function notifications()
    {
        $notifications = Notification::where('notifiable_id', '1149')
            ->where('read_at', null)
            ->get();
        return view('notifications', compact('notifications'));
    }

    public function mark_as_read($notification_id)
    {
        $notification = Notification::findOrFail($notification_id);
        $notification->read_at = Carbon::now();
        $notification->update();
        return redirect()->back();
    }

    public function get_country(Request $request)
    {
        $ip = '39.46.57.189';
        $geo_user = \Location::get($request->ip());
        return response()->json([
            'status' => true,
            'message' => "User details!",
            'user' => $geo_user,
        ]);
    }

    public function update_class(Request $request, $id)
    {
        $rules = [
            'start_time' =>  'required',
            'end_time' =>  'required',
            'start_date' =>  'required',
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

        // $class = AcademicClass::findOrFail($id);
        // $class->start_time = $request->start_time;
        // $class->end_time = $request->end_time;
        // $class->start_date = Carbon::parse($request->start_date);

        $apiURL = 'https://api.braincert.com/v2/updateclass';
        $postInput = [
            'apikey' => 'xKUyaLJHtbvBUtl3otJc',
            'id' => $id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'date' => $request->start_date,
            'end_date' => $request->start_date,
        ];
        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);
        $responseBody = json_decode($response->getBody(), true);
        if ($responseBody['status'] == 'error') {
            return response()->json([
                'status' => false,
                'message' => "Class Updation Failed!",
                'message' => $responseBody['error'],
            ], 400);
        } else {
            // $class->class_id = $responseBody['class_id'];
            // $class->update();
            return response()->json([
                'status' => true,
                'message' => "Class SuccessFully Updated!",
                'class' => $responseBody,
            ], 400);
        }
    }

    public function test()
    {
        // $course = Course::findOrFail($id);
        $iso_date = Carbon::now()->addHours(12)->toISOString();
        $current_date = Carbon::now();
        $current_date = Carbon::parse('2022-08-24T18:34:39.915206Z')->format('H:i');
       
        return response()->json([
            'status' => true,
            'message' => 'Action done successfully!',
            'iso_date' => $iso_date,
            'current_date' => $current_date,
        ]);
    }
}
