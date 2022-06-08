<?php

namespace App\Http\Controllers;

use App\Models\ClassroomFeedback;
use App\Models\Course;
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
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $rules = [
            'review' => "required|string",
            'feedbacks' => "required",
            'course_id' => "required|integer",

        ];

        if ($token_user->role_name == 'teacher') {
            $rules = [
                'receiver_id' => "required|integer"
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ], 400);
        }


        $feedbk = UserFeedback::where('course_id', $request->course_id)->where('sender_id', $token_user->id)->get();
        $course = Course::find($request->course_id);
        $decoded_feedbacks = json_decode(json_encode($request->feedbacks));
        if (count($feedbk) == 0) {

            foreach ($decoded_feedbacks as $feed) {
                // print_r($feedback);
                $feedback = new UserFeedback();
                if ($token_user->role_name == 'student') {
                    $feedback->receiver_id = $course->teacher_id;
                } else {
                    $feedback->receiver_id = $request->receiver_id;
                }
                $feedback->sender_id = $token_user->id;
                $user = User::find($feedback->receiver_id);
                $user->kudos_points = $user->kudos_points + ($feed->rating * 20);

                $feedback->review = $request->review;
                $feedback->course_id = $request->course_id;
                $feedback->rating = $feed->rating;
                $feedback->feedback_id = $feed->feedback_id;
                $feedback->kudos_points = $feed->rating * 20;
                $feedback->save();
                $user->update();
            }
            $feedbacks = UserFeedback::where('course_id', $request->course_id)->where('sender_id', $token_user->id)->get();
            return response()->json([
                'status' => true,
                'message' => "Your Feedback has Successfully Submitted",
                'feedback' => $feedbacks,
            ]);
        } else {
            if ($token_user->role_name == 'student') {
                $points_id = $course->teacher_id;
            } else {
                $points_id = $request->receiver_id;
            }

            $user = User::find($points_id);
            $user->kudos_points = $user->kudos_points - ($feedbk->sum('kudos_points'));

            foreach ($decoded_feedbacks as $feed) {
                $feedback = UserFeedback::where('feedback_id', $feed->feedback_id)->first();
                if ($token_user->role_name == 'student') {
                    $feedback->receiver_id = $course->teacher_id;
                } else {
                    $feedback->receiver_id = $request->receiver_id;
                }

                $feedback->sender_id = $token_user->id;
                $user->kudos_points = $user->kudos_points + ($feed->rating * 20);

                $feedback->review = $request->review;
                $feedback->course_id = $request->course_id;
                $feedback->rating = $feed->rating;
                $feedback->feedback_id = $feed->feedback_id;
                $feedback->kudos_points = $feed->rating * 20;
                $feedback->save();
                $user->update();
            }

            $feedbacks = UserFeedback::where('course_id', $request->course_id)->where('sender_id', $token_user->id)->get();
            return response()->json([
                'status' => true,
                'message' => "Your Feedback has Successfully Updated!",
                'feedback' => $feedbacks,
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

        $testimonials = UserTestimonial::where('sender_id', $token_user->id)->get();
        $decoded_feedbacks = json_decode(json_encode($request->feedbacks));

        if (count($testimonials) == 0) {

            foreach ($decoded_feedbacks as $feedback) {
                $platform = new UserTestimonial();
                $platform->sender_id = $token_user->id;
                $platform->review = $request->review;
                $platform->rating = $feedback->rating;
                $platform->testimonial_id = $feedback->testimonial_id;
                $platform->save();
            }
            $feedbacks = UserTestimonial::where('sender_id', $token_user->id)->get();
            return response()->json([
                'status' => true,
                'message' => "You Feedback Submitted Successfully!",
                'feedbacks' => $feedbacks,
            ]);
        } else {
            foreach ($decoded_feedbacks as $feedback) {
                $platform = UserTestimonial::where('sender_id', $token_user->id)->where('testimonial_id', $feedback->testimonial_id)->first();
                $platform->review = $request->review;
                $platform->rating = $feedback->rating;
                $platform->update();
            }
            return response()->json([
                'status' => true,
                'message' => "You Feedback Updated Successfully!",
                'feedbacks' => $testimonials,
            ]);
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
            'status' => true,
            'message' => "Required Params for Platform Feedback!",
            'params' => $params,
        ]);
    }

    public function classroom_params()
    {
        $params = Feedback::where('role_name', 'course')->get();

        return response()->json([
            'status' => true,
            'message' => "Required Params for Classroom Feedback!",
            'params' => $params,
        ]);
    }

    public function classroom_feedback(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $rules = [
            'review' => "required|string",
            'feedbacks' => "required",
            'course_id' => "required|integer",

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

        $count = ClassroomFeedback::where('course_id', $request->course_id)->where('user_id', $token_user->id)->count();
        // return $request->feedbacks;
        $course = Course::find($request->course_id);

        if ($count == 0) {
            $decoded_feedbacks = json_decode(json_encode($request->feedbacks));

            foreach ($decoded_feedbacks as $feed) {
                $feedback = new ClassroomFeedback();
                $feedback->user_id = $token_user->id;
                $feedback->review = $request->review;
                $feedback->course_id = $request->course_id;
                $feedback->rating = $feed->rating;
                $feedback->feedback_id = $feed->feedback_id;
                $feedback->save();
            }
            $feedbacks = ClassroomFeedback::where('course_id', $request->course_id)->where('user_id', $token_user->id)->get();
            return response()->json([
                'status' => true,
                'message' => "Your Feedback has Successfully Submitted",
                'feedback' => $feedbacks,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Your have already submitted Feedback on this Classroom!",
            ], 400);
        }
    }
}
