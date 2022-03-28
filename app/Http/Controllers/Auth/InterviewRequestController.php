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
use JWTAuth;

class InterviewRequestController extends Controller
{
    public function interview_requests_details(Request $request,$id)
    {
        
          $interviewRequests=null;
          
          if(isset($id)){
              
              $interviewRequests=TeacherInterviewRequest::with('user','user.userMetas','user.teacherSpecifications','user.teacherQualifications','user.spokenLanguages','user.teacher_subjects','user.teacher_subjects.program','user.teacher_subjects.field','user.teacher_subjects.subject')->where('id',$id)->get();
          
          }
        
            return response()->json([
                'status'=>true,
                'interview_requests'=> $interviewRequests,
               
                ]);
          
          
    }
    public function interview_requests(Request $request)
    {
        
        
          $interviewRequests=TeacherInterviewRequest::all();
          
          
        //   return $_GET['teacher_name'];
          
          if(isset($_GET['teacher_name']) && isset($_GET['request_date'])){
              
              
              
          }
          
          foreach($interviewRequests as $interviewRequest){
              
              
                $users=User::find($interviewRequest->user_id);
              
             $user_meta= $users->userMetas;
             $user_spec= $users->teacherSpecifications;
             $user_quali= $users->teacherQualifications;
             $user_spoken_language= $users->spokenLanguages;
             
             
             
              
              $interviewRequest->user=$users;
              // $interviewRequest->teacherSpecifications=$user_spec;
              // $interviewRequest->teacherQualifications=$user_quali;
              // $interviewRequest->spokenLanguages=$user_spoken_language;
              
              
              
                $interviewRequest->user=$interviewRequest->user;
              
          }
          
            return response()->json([
                'status'=>true,
                'interview_requests'=> $interviewRequests
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
            'addtional_comments' => 'required|max:60',
        ];

      
        
        $validator=Validator::make($request->all(),$rules);
        
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            
            return response()->json([
                
                'status' => 'false',
                'errors' => $errors,
                ],400) ;
            // return $this->respondWithError($errors,500);
        }
      
      $user=User::find($token_user->id);
      
      if($user->isTeacher()){

        if($user->profile_completed_step != 4){
             return response()->json([
                'status'=>false,
                'message'=>'Please complete your account details first'
                ],400);
        }
          
          $interviewRequests=TeacherInterviewRequest::where('user_id',$user->id)->first();
          
          if($interviewRequests != null){
              
               return response()->json([
                'status'=>false,
                'message'=>'Your have already submitted the interview request'
                ],400);
              
          }
          
          $interviewRequest=new TeacherInterviewRequest();
          $interviewRequest->user_id=$user->id;
          $interviewRequest->date_for_interview=$request->date_for_interview;
          $interviewRequest->time_for_interview=$request->time_for_interview;
          $interviewRequest->addtional_comments=$request->addtional_comments;
          $interviewRequest->save();
          
            return response()->json([
                'status'=>true,
                'message'=>'Your interview request has been submitted to the admin'
                ]);
          
          
      }else{
          
            return response()->json([
                'status'=>false,
                'message'=>'Only Teachers can submit request to admin for interview'
                ],401);
      }
        
       


    }
}
