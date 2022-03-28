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

        $course = Course::with('assignments', 'assignments.assignees', 'assignments.assignees.user')
            ->find($course_id);

        $total_assinments = 0;
        $completed_assignments = 0;
        $active_assignments = 0;
        foreach ($course['assignments'] as $assignment) {

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

                $course = Course::with('assignments.assignees', 'assignments.assignees.user')
                    ->with('assignments', function ($query) {
                        $query->where('status', 'active');
                    })
                    ->where('id', $course_id)->get();
            }
            if ($request->status == 'completed') {

                $course = Course::with('assignments.assignees', 'assignments.assignees.user')
                    ->with('assignments', function ($query) {
                        $query->where('status', 'completed');
                    })
                    ->where('id', $course_id)->get();
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
        return response()->json([
            'status' => true,
            'message' => 'Course Assignment Dashboard!',
            'course' => $course,
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

        $assignment->urls=json_decode($assignment->urls);
        $assignment->files=json_decode($assignment->files);

        return response()->json([
            'status' => true,
            'message' => 'Assignment details',
            'assignment' => $assignment,
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

        if ($request->hasFile('file')) {
            $fileName = rand(10, 100) . time() . '.' . $request->file->getClientOriginalExtension();
            $request->file->move(public_path('assets/submitted_assignments'), $fileName);
            $userAssignment->file = $fileName;
        }
        $userAssignment->status = 'submitted';
        $userAssignment->update();

        return response()->json([
            'status' => true,
            'message' => 'Assignment Submitted SuccessFully',
            'assignment' =>  $userAssignment,
        ]);
    }

    
}
