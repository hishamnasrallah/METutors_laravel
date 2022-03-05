<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Feedback;
use App\Models\UserFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class FeedbackController extends Controller
{
    public function feedback(Request $request)
    {

        $rules = [
            'review' => "required|string",
            'rating' => "required|json",
            'course_id' => "required|integer",
            'reciever_id' => "required|integer"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ],400);
        }



        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $count = UserFeedback::where('course_id', $request->course_id)->where('sender_id', $token_user->id)->count();
        
        if ($count == 0) {
            $decoded_rating = json_decode($request->rating);
            $decoded_feedbackId = json_decode($request->feedback_id);

            for ($i = 0; $i < 4; $i++) {
                $feedback = new UserFeedback();
                $feedback->reciever_id = $request->reciever_id;
                $feedback->sender_id = $token_user->id;
                $user = User::find($token_user->id);
                $user->kudos_points = $user->kudos_points + ($decoded_rating[$i] * 20);

                $feedback->review = $request->review;
                $feedback->course_id = $request->course_id;
                $feedback->rating = $decoded_rating[$i];
                $feedback->feedback_id = $decoded_feedbackId[$i];
                $feedback->kudos_points = $decoded_rating[$i] * 20;
                $feedback->save();
                $user->update();
            }

            return response()->json([
                'status' => true,
                'message' => "Your Feedback has Successfully Submitted",
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => "Your have already submitted Feedback on this Course!",
            ]);
        }
    }
}
