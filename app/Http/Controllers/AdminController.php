<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\Models\Course;
use App\Models\User;
use App\Models\UserFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class AdminController extends Controller
{
    //********/ Change the teacher for some Course ********
    public function change_teacher(Request $request){

        $rules = [
            'teacher_id' =>  'required|integer',
            'course_id' =>  'required|integer',
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

        $course = Course::find($request->course_id);
        $course->teacher_id = $request->teacher_id;
        $classes = AcademicClass::where("course_id",$course->id)->where('status','=',null)->get();

        foreach($classes as $class){
            $cls=AcademicClass::find($class->id);
            $cls->teacher_id = $request->teacher_id;
            $cls->update();
        }
        $course->update();

        return response()->json([
            'status' => true,
            'message' =>  "Teacher Assigned to course Successfully",
        ]); 

    }

    public function warn_teacher(Request $request){

        $rules = [
            'teacher_id' =>  'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => false,
                'errors' => $errors,
            ]);
        }

        $teacher = User::find($request->teacher_id);
        if($teacher->ban <= 2){
            $teacher->ban = $teacher->ban + 1;
            $teacher->update();

            return response()->json([
                'status' => true,
                'message' => "Warning has been given to teacher!",
            ]);
        }
        else{
            $teacher->status = "disabled";
            return response()->json([
                'status' => false,
                'message' => "Warning Limit Exceeded! Account has been banned",
            ]);
        }

    }

    public function teacher_performance(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required'
        ]);

        $user = User::find($request->teacher_id);
        $rating_sum = UserFeedback::where('reciever_id', $user->id)->sum('rating');
        $total_reviews = UserFeedback::where('reciever_id', $user->id)->count();
        $average_rating = $rating_sum / $total_reviews;

        return response()->json([
            'status' => true,
            'message' => "Teacher Performance",
            'average_rating' => $average_rating,
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

    public function testApi(Request $request){
         $token_1 = $request->bearerToken();
        // $token_user = JWTAuth::toUser($token_1);
        return $decoded = JWT::decode($token_1, '', array('HS256'));
    }
}
