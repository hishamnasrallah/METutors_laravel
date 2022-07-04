<?php

namespace App\Http\Controllers\Teacher;

use App\Events\AcceptAssignment;
use App\Events\AcceptAssignmentEvent;
use App\Events\RejectAssignment;
use App\Events\RejectAssignmentEvent;
use App\Http\Controllers\Controller;
use App\Jobs\AcceptAssignmentJob;
use App\Jobs\RejectAssignmentJob;
use App\Models\AcademicClass;
use App\Models\Assignment;
use App\Models\AssignmentFeedback;
use App\Models\AssignmetFeedback;
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

class AssignmentController extends Controller
{


    public function addAssignment(Request $request)
    {
        $rules = [
            'course_id' =>  'required|integer',
            'title' =>  'required',
            'description' =>  'required',
            'start_date' =>  'required',
            'deadline' =>  'required',
            'assignee' =>  'required',
            'urls' =>  'required',
            'files' =>  'required',
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

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $teacher = User::find($token_user->id);

        $assignment = new Assignment();
        $assignment->title = $request->title;
        $assignment->description = $request->description;
        $assignment->start_date = $request->start_date;
        $assignment->deadline = $request->deadline;
        $assignment->urls = $request->urls;
        $assignment->course_id = $request->course_id;

        $assignments = array();




        $assignment->files = $request['files'];
        $assignment->status = 'active';
        $assignment->created_by = $teacher->id;
        $assignment->save();

        $assignees = $request->assignee;
        foreach ($assignees as $assignee) {
            $user_assignment = new UserAssignment();
            $user_assignment->user_id =  $assignee;
            $user_assignment->assignment_id = $assignment->id;
            $user_assignment->status = 'pending';
            // $user_assignment->course_id = $request->course_id;
            $user_assignment->save();
        }

        $assignment = Assignment::with('assignees', 'assignees.user')->find($assignment->id);

        return response()->json([
            'status' => true,
            'message' => 'Course Assignment added Successfully!',
            'assignment' => $assignment,
        ]);
    }

    public function assignmentDashboard($course_id, Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $teacher = User::find($token_user->id);

        $corse = Course::find($course_id);

        $course = Course::with('participants', 'participants.user',  'assignments')
            ->with(['assignments.assignees.user' => function ($q) {
                $q->latest();
            }])
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

            $users = [];
            $assignees = $assignment->assignees;
            foreach ($assignees as $assignee) {
                $user = $assignees->whereIn('user_id', $users)->first();
                if ($user == null) {
                    $assignment->assignees = $user;
                }
            }
        }


        if (count($request->all()) >= 1) {

            if ($request->status == 'active') {

                $course = Course::with('participants', 'participants.user', 'assignments.assignees', 'assignments.assignees.user')
                    ->with('assignments', function ($query) {
                        $query->where('status', 'active');
                    })
                    ->where('id', $course_id)->get();
            }
            if ($request->status == 'completed') {

                $course = Course::with('participants', 'participants.user', 'assignments.assignees', 'assignments.assignees.user')
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

        return response()->json([
            'status' => true,
            'message' => 'Assignment details',
            'assignment' => $assignment,
        ]);
    }

    public function updateAssignment($assignment_id, Request $request)
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $teacher = User::find($token_user->id);

        $assignment = Assignment::find($assignment_id);
        $assignment->title = $request->title;
        $assignment->description = $request->description;
        $assignment->start_date = $request->start_date;
        $assignment->deadline = $request->deadline;
        $assignment->urls = $request->urls;
        $assignment->files = $request['files'];
        $assignment->course_id = $assignment->course_id;

        $assignments = array();


        $assignment->status = 'active';
        $assignment->created_by = $teacher->id;
        $assignment->update();

        $assignees = $request->assignee;

        $assignment = Assignment::with('assignees')->find($assignment_id);

        $db_assignees = [];
        foreach ($assignment['assignees'] as $assignee) {
            array_push($db_assignees, $assignee->user->id);
        }

        //******** Requested Array loop ********
        foreach ($assignees as $assignee) {
            if (in_array($assignee, $db_assignees)) {
            } else {
                $user_assignment = new UserAssignment();
                $user_assignment->user_id =  $assignee;
                $user_assignment->assignment_id = $assignment->id;
                $user_assignment->status = 'pending';
                $user_assignment->save();
            }
        }

        //******** Database Array loop ********
        foreach ($assignment['assignees'] as $assignee) {
            if (in_array($assignee->user_id, $assignees)) {
            } else {
                $user_assignment = UserAssignment::find($assignee->id);
                $user_assignment->delete();
            }
        }

        //******** returning response ********
        $assignment = Assignment::with('assignees')->find($assignment_id);

        return response()->json([
            'status' => true,
            'message' => 'Course Assignment updated Successfully!',
            'assignment' => $assignment,
        ]);
    }


    public function deleteAssignment($assignment_id)
    {
        $assignment = Assignment::find($assignment_id);
        // $assignment->assignees->delete();
        foreach ($assignment->assignees as $assignee) {
            $assignee->delete();
        }
        $assignment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Course Assignment updated Successfully!',
            'deleted_assignment' => $assignment,

        ]);
    }

    public function assignees($course_id)
    {
        $course = Course::with('participants.user')->find($course_id);

        return response()->json([
            'status' => true,
            'message' => 'Course Participants!',
            'participants' => $course->participants ?? '',

        ]);
    }

    public function acceptAssignment(Request $request, $assignment_id)
    {
        $rules = [
            'review' =>  'required|string',
            'rating' =>  'required|integer',
            // 'file' =>  'required',
            'student_id' =>  'required',
            'user_assignment_id' =>  'required',
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

        $assignment_feedback = AssignmentFeedback::where('user_assignment_id', $request->user_assignment_id)->where('student_id', $request->student_id)->where('assignment_id', $assignment_id)->count();
        if ($assignment_feedback == 0) {
            $feedback = new AssignmentFeedback();
        } else {
            $feedback = AssignmentFeedback::where('user_assignment_id', $request->user_assignment_id)->where('student_id', $request->student_id)->where('assignment_id', $assignment_id)->orderBy('id', 'desc')->first();
        }
        $user_assignment = UserAssignment::where('id', $request->user_assignment_id)->where('user_id', $request->student_id)->where('assignment_id', $assignment_id)->first();
        $user_assignment->status = 'completed';


        $feedback->review = $request->review;
        $feedback->assignment_id = $assignment_id;
        $feedback->student_id = $request->student_id;
        $feedback->feedback_by = $token_user->id;
        $feedback->rating = $request->rating;
        $feedback->user_assignment_id = $request->user_assignment_id;
        $feedback->status = 'completed';

        if ($request->has('file')) {
            $feedback->file = $request->file;
        }
        if ($assignment_feedback == 0) {
            $feedback->save();
        } else {
            $feedback->update();
        }

        $user_assignment->update();

        // Event notification
        $teacher_message = 'Assignment Accepted!';
        $student_message = 'Assignment Accepted Successfully!';
        $teacher = User::find($token_user->id);
        $student = User::find($request->student_id);
        $assignment = Assignment::findOrFail($assignment_id);

        //Sending emails and notifications
        // event(new AcceptAssignmentEvent($teacher, $teacher_message, $assignment));
        // event(new AcceptAssignmentEvent($student, $student_message, $assignment));
        // dispatch(new AcceptAssignmentJob($teacher, $teacher_message, $assignment));
        // dispatch(new AcceptAssignmentJob($student, $student_message, $assignment));

        return response()->json([
            'status' => true,
            'message' => 'Assignment Accepted!',
            'feedback' => $feedback,
        ]);
    }

    public function rejectAssignment(Request $request, $assignment_id)
    {
        $rules = [
            'review' =>  'required|string',
            'rating' =>  'required|integer',
            // 'file' =>  'required',
            'student_id' =>  'required',
            'user_assignment_id' =>  'required',
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


        $assignment_feedback = AssignmentFeedback::where('user_assignment_id', $request->user_assignment_id)->where('student_id', $request->student_id)->where('assignment_id', $assignment_id)->count();
        if ($assignment_feedback == 0) {
            $feedback = new AssignmentFeedback();
        } else {
            $feedback = AssignmentFeedback::where('user_assignment_id', $request->user_assignment_id)->where('student_id', $request->student_id)->where('assignment_id', $assignment_id)->orderBy('id', 'desc')->first();
        }
        $user_assignment = UserAssignment::where('id', $request->user_assignment_id)->where('user_id', $request->student_id)->where('assignment_id', $assignment_id)->first();
        $user_assignment->status = 'rejected';


        $feedback->review = $request->review;
        $feedback->assignment_id = $assignment_id;
        $feedback->student_id = $request->student_id;
        $feedback->feedback_by = $token_user->id;
        $feedback->rating = $request->rating;
        $feedback->user_assignment_id = $request->user_assignment_id;
        $feedback->status = 'rejected';

        if ($request->has('file')) {
            $feedback->file = $request->file;
        }
        if ($assignment_feedback == 0) {
            $feedback->save();
        } else {
            $feedback->update();
        }

        $user_assignment->update();

        // Event notification
        $teacher_message = 'Assignment Rejected!';
        $student_message = 'Your Assignment has been Rejected!';
        $teacher = User::find($token_user->id);
        $student = User::find($request->student_id);
        $assignment = User::find($assignment_id);

        //sending emails and notifications
        // event(new RejectAssignmentEvent($teacher, $teacher_message, $feedback));
        // event(new RejectAssignmentEvent($student, $student_message, $feedback));
        // dispatch(new RejectAssignmentJob($teacher, $teacher_message, $feedback));
        // dispatch(new RejectAssignmentJob($student, $student_message, $feedback));

        return response()->json([
            'status' => true,
            'message' => 'Assignment Rejected!',
            'feedback' => $feedback,
        ]);
    }

    public function userAssignment($assignment_id, $user_id)
    {
        $ass_array = [];
        $assignmnet = UserAssignment::with('user')->where(['user_id' => $user_id, 'assignment_id' => $assignment_id])->get();
        foreach ($assignmnet as $ass) {
            $feedback = AssignmentFeedback::where('user_assignment_id', $ass->id)->get();
            $ass->feedback = $feedback;
        }

        return response()->json([
            'status' => true,
            'message' => 'Student Assignment Detail!',
            'assignment' => $assignmnet,
        ]);
    }

    public function extendDate($assignment_id, Request $request)
    {
        $rules = [
            'deadline' =>  'required',
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

        $assignment = Assignment::find($assignment_id);
        $assignment->deadline = $request->deadline;
        $assignment->update();

        return response()->json([
            'status' => true,
            'message' => 'Assignment Date Exteneded Successfully!',
            'assignment' => $assignment,
        ]);
    }
}
