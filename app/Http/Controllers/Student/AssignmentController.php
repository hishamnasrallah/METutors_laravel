<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\AcademicClass;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\CanceledCourse;
use App\Models\ClassTopic;
use App\Models\Course;
use App\Models\RejectedCourse;
use App\Models\Resource;
use App\Models\Topic;

use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserFeedback;
use Illuminate\Http\Request;
use JWTAuth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class AssignmentController extends Controller
{


    public function assignmentDashboard($course_id, Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $course = Course::with('assignments')->find($course_id);
        $user_assignments = [];
        foreach ($course['assignments'] as $assignmnet) {
            $userAssignment = UserAssignment::where('assignment_id', $assignmnet->id)->where('user_id', $token_user->id)->pluck('assignment_id');
            if (count($userAssignment) > 0)
                array_push($user_assignments, $userAssignment);
        }

        $assignments = Assignment::with('assignees', 'assignees.user')->whereIn('id', $user_assignments)->get();
        $course = Course::with('participants', 'participants.user')->find($course_id);

        $total_assinments = 0;
        $completed_assignments = 0;
        $active_assignments = 0;
        foreach ($assignments as $assignment) {
            if ($assignment->status == 'active') {
                $active_assignments = $active_assignments + 1;
            }
            if ($assignment->status == 'completed') {
                $completed_assignments = $completed_assignments + 1;
            }
            $total_assinments++;
        }

        if (count($request->all()) >= 1) {

            if ($request->status == 'active') {
                $assignments = Assignment::with('assignees', 'assignees.user')->whereIn('id', $user_assignments)->whereIn('status', ['active'])->get();
                $course->assignments = $assignments;
            }
            if ($request->status == 'completed') {

                $assignments = Assignment::with('assignees', 'assignees.user')->whereIn('id', $user_assignments)->whereIn('status', ['completed'])->get();
                $course->assignments = $assignments;
            }

            return response()->json([
                'status' => true,
                'message' => 'Course Assignment Dashboard!',
                'course' => $course,
                'total_assignments' => $total_assinments,
                'active_assignments' => $active_assignments,
                'completed_assignments' => $completed_assignments,

            ]);
        }

        $course->assignments = $assignments;
        return response()->json([
            'status' => true,
            'message' => 'Course Assignment Dashboard!',
            'course' =>  $course,
            'total_assignments' => $total_assinments,
            'active_assignments' => $active_assignments,
            'completed_assignments' => $completed_assignments,

        ]);
    }

    public function assignmentDetail($assignment_id)
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $assignment = Assignment::with('assignees', 'assignees.user')->find($assignment_id);
        $user_assignment = UserAssignment::where('user_id', $token_user->id)->where('assignment_id', $assignment_id)->first();
        $assignment->user_assignment_status = $user_assignment->status;
        // return $assignment->course_id;

        return response()->json([
            'status' => true,
            'message' => 'Assignment details',
            'assignment' => $assignment,
            // 'isSubmitted' => $user_assignment->status,
        ]);
    }

    public function submitAssignment($assignment_id, Request $request)
    {
        $rules = [
            'description' =>  'required',
            'file' =>  'required', //|mimes:pdf|max:20000
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

        $userAssignment = UserAssignment::where('assignment_id', $assignment_id)->where('user_id', $token_user->id)->first();

        $userAssignment->description = $request->description;

        if ($request->has('file')) {
            
            $userAssignment->file = $request->file;
        }
        $userAssignment->status = 'submitted';
        $userAssignment->update();

        $userAssignment->user_assignment_status = 'submitted';

        return response()->json([
            'status' => true,
            'message' => 'Assignment Submitted SuccessFully',
            'assignment' =>  $userAssignment,
        ]);
    }

    public function userAssignment($assignment_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $assignmnet = UserAssignment::with(['user','feedback' => function ($q) use ($assignment_id, $token_user) {
            $q->where(['student_id' =>  $token_user->id, 'assignment_id' => $assignment_id]);
        }])->where(['user_id' =>  $token_user->id, 'assignment_id' => $assignment_id])->first();

        return response()->json([
            'status' => true,
            'message' => 'Student Assignment Detail!',
            'assignment' => $assignmnet,
        ]);
    }
}
