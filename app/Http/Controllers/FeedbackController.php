<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Feedback;
use App\Models\Testimonial;
use App\Models\UserFeedback;
use App\Models\UserTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class FeedbackController extends Controller
{
    //**************** User: feedback on course ****************
    public function feedback(Request $request)
    {


        $rules = [
            'review' => "required|string",
            'feedbacks' => "required",
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
            ], 400);
        }



        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $count = UserFeedback::where('course_id', $request->course_id)->where('sender_id', $token_user->id)->count();
        // return $request->feedbacks;


        if ($count == 0) {
            $decoded_feedbacks = json_decode($request->feedbacks);

            foreach ($decoded_feedbacks as $feed) {
                // print_r($feedback);
                $feedback = new UserFeedback();
                $feedback->reciever_id = $request->reciever_id;
                $feedback->sender_id = $token_user->id;
                $user = User::find($token_user->id);
                $user->kudos_points = $user->kudos_points + ($feed->rating * 20);

                $feedback->review = $request->review;
                $feedback->course_id = $request->course_id;
                $feedback->rating = $feed->rating;
                $feedback->feedback_id = $feed->feedback_id;
                $feedback->kudos_points = $feed->rating * 20;
                $feedback->save();
                $user->update();
            }

            return response()->json([
                'status' => true,
                'message' => "Your Feedback has Successfully Submitted",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Your have already submitted Feedback on this Course!",
            ]);
        }
    }

    //**************** User: feedback Params for feedback on course ****************
    public function feedback_params()
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($token_user->role_name == 'teacher') {
            $params = Feedback::select('id', 'name')->where('role_name', 'teacher')->get();
        }
        if ($token_user->role_name == 'student') {
            $params = Feedback::select('id', 'name')->where('role_name', 'student')->get();
        }

        return response()->json([
            'status' => false,
            'message' => "Required params are listed below!",
            'params' => $params,
        ]);
    }

    //**************** Platform feedback ****************
    public function userPlatform(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $count = UserTestimonial::where('sender_id', $token_user->id)->count();

        if ($count == 0) {
            $decoded_feedbacks = json_decode($request->feedbacks);


            foreach ($decoded_feedbacks as $feedback) {
                $platform = new UserTestimonial();
                $platform->sender_id = $token_user->id;
                $platform->review = $request->review;
                $platform->rating = $feedback->rating;
                $platform->testimonial_id = $feedback->testimonial_id;
                $platform->save();
            }
            return response()->json([
                'status' => true,
                'message' => "You Feedback Submitted Successfully!",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "You have already added a feedback!",
            ], 400);
        }
    }

    //**************** User: feedback Params on Platform ****************
    public function PlatformFeedbackParams()
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($token_user->role_name == 'teacher') {
            $params = Testimonial::select('id', 'name')->where('role_name', 'teacher')->get();
        }
        if ($token_user->role_name == 'student') {
            $params = Testimonial::select('id', 'name')->where('role_name', 'student')->get();
        }

        return response()->json([
            'status' => false,
            'message' => "Required Params for Platform Feedback!",
            'params' => $params,
        ]);
    }
}
