<?php

namespace App\Http\Controllers;

use App\Events\CourseFeedbackEvent;
use App\Jobs\CourseFeedbackJob;
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
use stdClass;

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
        // if user have no feedback on this course by sender 
        if (count($feedbk) == 0) {

            foreach ($decoded_feedbacks as $feed) {
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
            $feedback_obj = new stdClass;
            $feedback_array = [];

            //Making Api Response presentable
            $feedback_obj->sender_id = $feedbacks[0]->sender_id;
            $feedback_obj->receiver_id = $feedbacks[0]->receiver_id;
            $feedback_obj->course_id = $feedbacks[0]->course_id;
            $feedback_obj->review = $feedbacks[0]->review;
            foreach ($feedbacks as $feedback) {
                $obj =  new stdClass;
                $obj->feedback_id = $feedback->feedback_id;
                $obj->rating = $feedback->rating;
                $obj->kudos_points = $feedback->kudos_points;
                array_push($feedback_array, $obj);
            }
            $feedback_obj->feedback = $feedback_array;

            $sender = User::findOrFail($token_user->id);
            $reciever = User::findOrFail($feedbacks[0]->receiver_id);
            // notifications and emails
            event(new CourseFeedbackEvent($reciever->id, "You have recieved a feedback", $reciever, $feedback));
            event(new CourseFeedbackEvent($sender->id, "Feedback sent Successfully", $sender, $feedback));
            dispatch(new CourseFeedbackJob($reciever->id, "You have recieved a feedback", $reciever, $feedback));
            dispatch(new CourseFeedbackJob($sender->id, "Feedback sent Successfully", $sender, $feedback));

            return response()->json([
                'status' => true,
                'message' => "Your feedback has successfully submitted",
                'feedback' => $feedback_obj,
            ]);
        }
        // if user already have feedback on this course by sender 
        else {
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
                'message' => "Your feedback has successfully updated!",
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
                'message' => "You feedback submitted successfully!",
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
                'message' => "Your feedback updated successfully!",
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
            'message' => "Required params for platform feedback!",
            'params' => $params,
        ]);
    }

    public function classroom_params()
    {
        $params = Feedback::where('role_name', 'course')->get();

        return response()->json([
            'status' => true,
            'message' => "Required params for classroom feedback!",
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
                'message' => "Your feedback has successfully submitted",
                'feedback' => $feedbacks,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "You have already submitted feedback on this classroom!",
            ], 400);
        }
    }
}
