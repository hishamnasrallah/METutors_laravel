<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    public function feedback(Request $request)
    {

        $rules = [
            'review' => "required|string",
            'rating' => "required|integer|max:5",
            'course_id' => "required|integer"
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

        $feedback = new Feedback();
        if (auth('sanctum')->user()->role_name == "teacher") {
            $request->validate([
                'student_id' => "required|integer"
            ]);
            $feedback->teacher_id = auth('sanctum')->user()->id;
            $feedback->student_id = $request->student_id;
            $user = User::find($request->student_id);
            $user->kudos_points = $user->kudos_points + ($request->rating * 20);
        }

        if (auth('sanctum')->user()->role_name == "student") {
            $request->validate([
                'teacher_id' => "required|integer"
            ]);
            $feedback->teacher_id = $request->teacher_id;
            $feedback->student_id = auth('sanctum')->user()->id;
            $user = User::find($request->teacher_id);
            $user->kudos_points = $user->kudo_points + ($request->rating * 20);
        }

        $feedback->review = $request->review;
        $feedback->course_id = $request->course_id;
        $feedback->rating = $request->rating;
        $feedback->kudos_points = $request->rating * 20;
        $feedback->feedback_by = auth('sanctum')->user()->id;

        $feedback->save();
        $user->update();

        return response()->json([
            'status' => true,
            'message' => "Your Feedback has Successfully Submitted",
        ]);
    }

    public function block_user(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);
        $user->status = 'inactive';
        $user->update();

        return response()->json([
            'status' => true,
            'message' => "User Blocked Successfully",
        ]);
    }

    public function unblock_user(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);
        $user->status = 'active';
        $user->update();

        return response()->json([
            'status' => true,
            'message' => "User UnBlocked Successfully",
        ]);
    }

    public function teacher_performance(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required'
        ]);

        $user = User::find($request->teacher_id);
        $rating_sum = Feedback::where('teacher_id', $user->id)->sum('rating');
        $total_reviews = Feedback::where('teacher_id', $user->id)->count();
        $average_rating = $rating_sum / $total_reviews;

        return response()->json([
            'status' => true,
            'message' => "Teacher Performance",
            'average_rating' => $average_rating,
        ]);
    }

    
}
