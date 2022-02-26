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

class InterviewRequestController extends Controller
{
    public function interview_requests_details(Request $request)
    {
        
          $interviewRequests=TeacherInterviewRequest::all();
          
        //   return $_GET['teacher_name'];
          
          if(isset($_GET['id'])){
              
              $interviewRequests=TeacherInterviewRequest::find($_GET['id']);
              
              $users=User::find($interviewRequests->user_id);
              
             $user_meta= $users->userMetas;
             $user_spec= $users->teacherSpecifications;
             $user_quali= $users->teacherQualifications;
             $user_spoken_language= $users->spokenLanguages;
             
             
             
              
              $interviewRequests->user=$users;
              $interviewRequests->teacherSpecifications=$user_spec;
              $interviewRequests->teacherQualifications=$user_quali;
              $interviewRequests->spokenLanguages=$user_spoken_language;
              
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
              $interviewRequest->teacherSpecifications=$user_spec;
              $interviewRequest->teacherQualifications=$user_quali;
              $interviewRequest->spokenLanguages=$user_spoken_language;
              
              
              
                $interviewRequest->user=$interviewRequest->user;
              
          }
          
            return response()->json([
                'status'=>true,
                'interview_requests'=> $interviewRequests
                ]);
          
          
    }
    public function interview_request(Request $request)
    {
        
        
   
        
      $user = auth('sanctum')->user();
      
       $rules = [
            'level_of_education' => 'required',
            'level_to_teach' => 'required',
            'subject' => 'required',
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
                ]) ;
            // return $this->respondWithError($errors,500);
        }
      
      
      
      if($user->isTeacher()){
          
          $interviewRequests=TeacherInterviewRequest::where('user_id',$user->id)->first();
          
          if($interviewRequests != null){
              
               return response()->json([
                'status'=>false,
                'message'=>'Your have already submitted the interview request'
                ]);
              
          }
          
          $interviewRequest=new TeacherInterviewRequest();
          $interviewRequest->user_id=$user->id;
          $interviewRequest->level_of_education=$request->level_of_education;
          $interviewRequest->level_to_teach=$request->level_to_teach;
          $interviewRequest->subject=$request->subject;
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
                ]);
      }
        
       


    }
}
