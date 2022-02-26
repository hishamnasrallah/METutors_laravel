<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Newsletter;
use App\Models\Role;
use App\Models\UserMeta;
use App\Models\UserOccupation;
use App\Models\UserZoomApi;
use App\User;
use App\Subject;
use App\Country;
use App\City;
use App\Program;
use App\TimeZone;
use App\FieldOfStudy;
use App\TeacherInterviewRequest;
use App\TeachingSpecification;
use App\TeachingQualification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Storage;
use Validator;
use \App\Mail\SendMailInvite;
class GeneralController extends Controller
{
   public function timezones(){
       
       $timezones=TimeZone::all();
       
       return response()->json([
                
                'status' => true,
                'timezones' => $timezones,
                ]);
       
   }
   public function programs(){
       
       $programs=Program::all();
       
       return response()->json([
                
                'status' => true,
                'programs' => $programs,
                ]);
       
   }
   public function countries(){
       
       $countries=Country::all();
       
       return response()->json([
                
                'status' => true,
                'countries' => $countries,
                ]);
       
   }
   
    public function cities(Request $request){
       
        $rules = [
            
            'country_id' => 'required',
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
       
       $cities=City::where('country_id',$request->country_id)->get();
       
       return response()->json([
                
                'status' => true,
                'cities' => $cities,
                ]) ;
       
   }
   
   public function subjects(){
       
       $subjects=Subject::all();
       
       return response()->json([
                
                'status' => true,
                'subjects' => $subjects,
                ]);
       
   }
   public function field_of_study(){
       
       $field_of_study=FieldOfStudy::all();
       
       return response()->json([
                
                'status' => true,
                'field_of_study' => $field_of_study,
                ]) ;
       
   }
   public function field_subjects(Request $request){
       
        $rules = [
            
            'field_id' => 'required',
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
       
       $subjects=Subject::where('field_id',$request->field_id)->get();
       
       return response()->json([
                
                'status' => true,
                'subjects' => $subjects,
                ]) ;
       
   }
}
