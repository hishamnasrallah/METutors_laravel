<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use App\User;
use App\TeacherInterviewRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use DB;
use GuzzleHttp\Client;
use JWTAuth;

class InterviewRequestController extends Controller
{
  public function interview_requests_details(Request $request, $id)
  {

    $interviewRequests = null;

    if (isset($id)) {

      $interviewRequests = TeacherInterviewRequest::with('user', 'user.country','user.userMetas', 'user.teacherSpecifications', 'user.teacherQualifications', 'user.spokenLanguages', 'user.spokenLanguages.language', 'user.teacher_subjects', 'user.teacher_subjects.program', 'user.teacher_subjects.field', 'user.teacher_subjects.subject')->where('id', $id)->first();
    }

    return response()->json([
      'status' => true,
      'interview_request' => $interviewRequests,

    ]);
  }
  public function interview_requests(Request $request)
  {


    $interviewRequests = TeacherInterviewRequest::with('user', 'user.country', 'user.teacherSpecifications', 'user.teacherQualifications')->orderBy('id', 'DESC')->get();


    //   return $_GET['teacher_name'];



    return response()->json([
      'status' => true,
      'interview_requests' => $interviewRequests
    ]);
  }
  public function interview_request(Request $request)
  {


    $token_1 = JWTAuth::getToken();
    $token_user = JWTAuth::toUser($token_1);


    $user = $token_user;

    $rules = [

      'date_for_interview' => 'required',
      'time_for_interview' => 'required',
     
    ];



    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      $messages = $validator->messages();
      $errors = $messages->all();

      return response()->json([

        'status' => 'false',
        'errors' => $errors,
      ], 400);
      // return $this->respondWithError($errors,500);
    }

    $user = User::find($token_user->id);

    if ($user->isTeacher()) {

      if ($user->profile_completed_step != 5) {
        return response()->json([
          'status' => false,
          'message' => 'Please complete your account details first'
        ], 400);
      }

      $interviewRequests = TeacherInterviewRequest::where('user_id', $user->id)->first();

      if ($interviewRequests != null) {

        return response()->json([
          'status' => false,
          'message' => 'Your have already submitted the interview request'
        ], 400);
      }

      $interviewRequest = new TeacherInterviewRequest();
      $interviewRequest->user_id = $user->id;
      $interviewRequest->date_for_interview = $request->date_for_interview;
      $interviewRequest->time_for_interview = $request->time_for_interview;
      if($request->addtional_comments){
         $interviewRequest->addtional_comments = $request->addtional_comments;
      }
     
      $interviewRequest->save();

      $interviewRequest=TeacherInterviewRequest::find($interviewRequest->id);

      return response()->json([
        'status' => true,
        'message' => 'Your interview request has been submitted to the admin',
        'interview_request' => $interviewRequest
      ]);
    } else {

      return response()->json([
        'status' => false,
        'message' => 'Only Teachers can submit request to admin for interview'
      ], 401);
    }
  }

  public function meeting(Request $request)
  {
    $rules = [
      "title" => "required",
      'start_time' => "required",
      'end_time' => "required",
      'start_date' => "required",
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
    /// Curl Implementation

    $apiURL = 'https://api.braincert.com/v2/schedule';
    $postInput = [
      'apikey' =>  "PU0MLbUZrGbmonA3PHny",
      'title' =>  $request->title,
      'timezone' => 90,
      'start_time' => $class->start_time,
      'end_time' => $class->end_time,
      'date' => $class->start_date,
    ];

    $client = new Client();
    $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

    $statusCode = $response->getStatusCode();
    $responseBody = json_decode($response->getBody(), true);
    if ($responseBody['status'] == "ok") {
      // $class->title = $request->title;
      // $class->lesson_name = $request->lesson_name;
      // $class->class_id = $responseBody['class_id'];
      // $class->status = "scheduled";
      // $course->status = "active";
      // $course->update();
      // $class->save();
      return response()->json([
        'success' => true,
        'message' => "Class Scheduled SuccessFully",
        'class' => $class,
      ]);
    } else {
      return $responseBody;
    }
  }
}
