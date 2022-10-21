<?php

namespace App\Http\Controllers\Auth;

use App\Events\InterviewRequestEvent;
use App\Http\Controllers\Controller;
use App\Jobs\InterviewRequestJob;
use App\Models\Verification;
use App\User;
use App\TeacherInterviewRequest;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use DB;
use GuzzleHttp\Client;
use JWTAuth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class InterviewRequestController extends Controller
{
  public function interview_requests_details(Request $request, $id)
  {

    $interviewRequest = null;        

    if (isset($id)) {
      $interviewRequest = TeacherInterviewRequest::with('user', 'user.country','user.userSignature', 'user.userResume','user.userDegrees','user.userCertificates', 'user.teacherSpecifications', 'user.teacherQualifications', 'user.spokenLanguages', 'user.spokenLanguages.language', 'user.teacherAvailability', 'user.teacher_subjects', 'user.teacher_subjects.program', 'user.teacher_subjects.field', 'user.teacher_subjects.subject.country')
        ->where('id', $id)
        ->first();

      $availability_days = [];
      foreach ($interviewRequest['user']['teacherAvailability'] as $availability)
        if (!(in_array($availability->day, $availability_days))) {
          array_push($availability_days, $availability->day);
        }
     
      $interviewRequest->availability_days = $availability_days;
    }

    return response()->json([
      'status' => true,
      'interview_request' => $interviewRequest,

    ]);
  }
  public function interview_requests(Request $request)
  {


    if (isset($request->status) && $request->status == "approved") {

      $interviewRequests = TeacherInterviewRequest::with('user', 'user.country', 'user.teacherSpecifications', 'user.teacherQualifications')->orderBy('id', 'DESC')->where("status", "approved")->paginate($request->per_page ?? 10);
    } elseif (isset($request->status) && $request->status == "rejected") {

      $interviewRequests = TeacherInterviewRequest::with('user', 'user.country', 'user.teacherSpecifications', 'user.teacherQualifications')->orderBy('id', 'DESC')->where("status", "rejected")->paginate($request->per_page ?? 10);
    } else {

      $interviewRequests = TeacherInterviewRequest::with('user', 'user.country', 'user.teacherSpecifications', 'user.teacherQualifications')->orderBy('id', 'DESC')->whereIn("status", ['pending', 'scheduled'])->paginate($request->per_page ?? 10);
    }


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

    // $date = Carbon::parse($request->date_for_interview)->format('Y-m-d');
    // $time = Carbon::parse($request->time_for_interview)->format('H:i:s');

    // $time_object = DateTime::createFromFormat("Y-m-d H:i:s", ($date . ' ' . $time));
    // $time_string = Carbon::parse($time_object);
    
    // $current_time = Carbon::now();
    // $diff= $time_string->diff($current_time);
    // return $diff;

    // if ($current_time->diff($current_time) < 24) {
    //   return response()->json([
    //     'status' => false,
    //     'errors' => 'You can schedule your interview after 24 hours from now',
    //   ], 400);
    // } 
    // return Carbon::parse($time_object);

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

      $interviewRequests = TeacherInterviewRequest::with('user')->where('user_id', $user->id)->first();

      if ($interviewRequests != null) {

        return response()->json([
          'status' => false,
          'message' => 'You have already submitted the interview request'
        ], 400);
      }

      $interviewRequest = new TeacherInterviewRequest();
      $interviewRequest->user_id = $user->id;
      $interviewRequest->date_for_interview = $request->date_for_interview;
      $interviewRequest->time_for_interview = $request->time_for_interview;
      if ($request->addtional_comments) {
        $interviewRequest->addtional_comments = $request->addtional_comments;
      }

      $interviewRequest->save();

      $interviewRequest = TeacherInterviewRequest::with('user')->find($interviewRequest->id);
      $admin = User::where('role_name', 'admin')->first();
      $admin_message = "New Request for interview has been Submitted!";
      $teacher_message = "Your Request has been submitted! we will contact you soon!";


      //*********** Sending Email to teacher  ************\\
      $user_email = $user->email;
      $custom_message = "Your Request has been submitted! we will contact you soon!";
      $to_email = $user_email;

      $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'interview_request' => $interviewRequest, 'user' => $user);

      Mail::send('email.interview', $data, function ($message) use ($to_email) {

        $message->to($to_email)->subject('MEtutors interview request');
        $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
      });


      // //******** Email ends **********//

      //*********** Sending Email to admin  ************\\
      $user_email = $admin->email;
      $custom_message = "New Request for interview has been Submitted!";
      $to_email = $user_email;
      $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'interview_request' => $interviewRequest, 'user' => $admin);

      Mail::send('email.interview', $data, function ($message) use ($to_email) {

        $message->to($to_email)->subject('New interview request');
        $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
      });
      // //******** Email ends **********//

      // //Emails and Notifications
      // event(new InterviewRequestEvent($user->id, $user, $teacher_message, $interviewRequest));
      // event(new InterviewRequestEvent($admin->id, $admin, $admin_message, $interviewRequest));
      // dispatch(new InterviewRequestJob($user->id, $user, $teacher_message, $interviewRequest));
      // dispatch(new InterviewRequestJob($admin->id, $admin, $admin_message, $interviewRequest));

      return response()->json([
        'status' => true,
        'message' => 'Your interview request has submitted successfully',
        'interview_request' => $interviewRequest
      ]);
    } else {

      return response()->json([
        'status' => false,
        'message' => 'Only teachers can submit request to HR for interview'
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
      'apikey' =>  "xKUyaLJHtbvBUtl3otJc",
      'title' =>  $request->title,
      'timezone' => 90,
      'start_time' => $class->start_time,
      'end_time' => $class->end_time,
      'date' => $class->start_date,
      'currency' => "USD",
      'ispaid' => null,
      'is_recurring' => 0,
      'repeat' => 0,
      'weekdays' => null,
      'end_date' => $class->end_date,
      'seat_attendees' => null,
      'record' => 0,
      'isRecordingLayout ' => 1,
      'isVideo  ' => 1,
      'isBoard ' => 1,
      'isLang ' => null,
      'isRegion ' => null,
      'isCorporate ' => null,
      'isScreenshare ' => 1,
      'isPrivateChat  ' => 0,
      'description ' => null,
      'keyword ' => null,
      'format ' => "json",
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
        'message' => "Class scheduled successFully",
        'class' => $class,
      ]);
    } else {
      return $responseBody;
    }
  }



  public function paginate($items, $perPage, $page = null, $options = [])
  {
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
  }
}
