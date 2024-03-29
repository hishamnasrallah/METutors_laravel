<?php

namespace App\Http\Controllers\Student;

use App\Events\SubmitAssignmentEvent;
use App\Http\Controllers\Controller;
use App\Jobs\SubmittAssignmentJob;
use App\Models\AcademicClass;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\CanceledCourse;
use App\Models\ClassTopic;
use App\Models\Course;
use App\Models\Feedback;
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

        // $corse = Course::find($course_id);

        $course = Course::with('participants', 'participants.user','assignments')
            ->with(['assignments.assignees.user' => function ($q) {
                $q->latest();
            }])
            ->findOrFail($course_id);

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

            $users = [];
            $assignees = $assignment->assignees;
            foreach ($assignees as $assignee) {
                // $assignment->status =  $assignee->status;
                $user = $assignees->whereIn('user_id', $users)->first();
                if ($user == null) {
                    $assignment->assignees = $user;
                }
            }
        }


        if (count($request->all()) >= 1) {

            if ($request->status == 'active') {
                $course = Course::with('participants', 'participants.user', 'assignments.assignees.user')
                    ->with('assignments', function ($query) {
                        $query->where('status', 'active');
                    })
                    ->with(['assignments.assignees' => function ($q) {
                        $q->latest();
                    }])
                    ->findOrFail($course_id);

                foreach ($course->assignments as $assignment) {
                    $users = [];
                    $assignees = $assignment->assignees;
                    // $assignment->status =  $assignees[0]->status;
                }
            }
            if ($request->status == 'completed') {

                $course = Course::with('participants', 'participants.user', 'assignments.assignees.user')
                    ->with('assignments', function ($query) {
                        $query->where('status', 'completed');
                    })
                    ->with(['assignments.assignees' => function ($q) {
                        $q->latest();
                    }])
                    ->findOrFail($course_id);

                foreach ($course->assignments as $assignment) {
                    $users = [];
                    $assignees = $assignment->assignees;
                    // $assignment->status =  $assignees[0]->status;
                }
            }



            return response()->json([
                'status' => true,
                'message' => 'Course assignment dashboard!',
                'course' => $course,
                'total_assignments' => $total_assinments,
                'active_assignments' => $active_assignments,
                'completed_assignments' => $completed_assignments,

            ]);
        }

        // $course->assignments = $assignments;
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
        $user_assignment = UserAssignment::where('user_id', $token_user->id)->where('assignment_id', $assignment_id)->latest()->first();
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
        $assignment = Assignment::findOrFail($assignment_id);
        $course = Course::findOrFail($assignment->course_id);

        $userAssignment = UserAssignment::where('assignment_id', $assignment_id)->where('user_id', $token_user->id)->latest()->first();

        if ($userAssignment && $userAssignment->status == 'rejected') {
            $user_assignment = new UserAssignment();
            $user_assignment->user_id = $userAssignment->user_id;
            $user_assignment->assignment_id = $userAssignment->assignment_id;
            $user_assignment->description = $request->description;
            if ($request->has('file')) {
                $user_assignment->file = $request->file;
            }
            $user_assignment->status = 'resubmitted';
            $user_assignment->updated_at = Carbon::now();
            $user_assignment->save();

            // Event notification
            $teacher_message = 'User ReSubmitted Assignment!';
            $student_message = 'Assignment ReSubmitted Successfully!';
            $student = User::find($token_user->id);
            $teacher = User::find($course->teacher_id);
            //Sending emails and notifications
            event(new SubmitAssignmentEvent($teacher->id, $teacher, $teacher_message, $assignment));
            event(new SubmitAssignmentEvent($student->id, $student, $student_message, $assignment));
            dispatch(new SubmittAssignmentJob($teacher->id, $teacher, $teacher_message, $assignment));
            dispatch(new SubmittAssignmentJob($student->id, $student, $student_message, $assignment));


            return response()->json([
                'status' => true,
                'message' => trans('api_messages.ASSIGNMENT_RESUBMITTED_SUCCESSFULLY'),
                'assignment' =>  $user_assignment,
            ]);
        } else {

            $userAssignment->description = $request->description;
        }
        if ($request->has('file')) {

            $userAssignment->file = $request->file;
        }
        $userAssignment->status = 'submitted';
        $userAssignment->updated_at = Carbon::now();
        $userAssignment->update();

        $userAssignment->user_assignment_status = 'submitted';

        // Event notification
        $teacher_message = 'User Submitted Assignment!';
        $student_message = 'Assignment Submitted Successfully!';
        $student = User::find($token_user->id);
        $teacher = User::find($course->teacher_id);
        $assignment = Assignment::findOrFail($assignment_id);

        // Sending emails and notifications
        event(new SubmitAssignmentEvent($teacher->id, $teacher, $teacher_message, $assignment));
        event(new SubmitAssignmentEvent($student->id, $student, $student_message, $assignment));
        dispatch(new SubmittAssignmentJob($teacher->id, $teacher, $teacher_message, $assignment));
        dispatch(new SubmittAssignmentJob($student->id, $student, $student_message, $assignment));

        return response()->json([
            'status' => true,
            'message' => 'Assignment submitted successfully',
            'assignment' =>  $userAssignment,
        ]);
    }

    public function userAssignment($user_assignment_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        // $assignmnet = UserAssignment::with(['user', 'feedback' => function ($q) use ($assignment_id, $token_user) {
        //     $q->where(['student_id' =>  $token_user->id, 'assignment_id' => $assignment_id]);
        // }])->where(['user_id' =>  $token_user->id, 'assignment_id' => $assignment_id])->latest()->first();

        $assignmnet = UserAssignment::with(['user', 'feedback' => function ($q) use ($user_assignment_id) {
            $q->where('user_assignment_id', $user_assignment_id);
        }])->where('id', $user_assignment_id)->first();

        return response()->json([
            'status' => true,
            'message' => 'Student assignment detail!',
            'assignment' => $assignmnet,
        ]);
    }
}
