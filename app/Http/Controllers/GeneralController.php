<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Newsletter;
use App\Models\Role;
use App\Models\UserMeta;
use App\Models\UserOccupation;
use App\Models\UserZoomApi;
use App\Models\LevelOfEducation;
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
use App\CourseLevel;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;
use \App\Mail\SendMailInvite;
class GeneralController extends Controller
{


  public function testing_verify(){

    return 'very good';

  }

  public function field_of_studies(){

      $field_of_study=FieldOfStudy::all();

      return response()->json([

                'status' => true,
                'field_of_study' => $field_of_study,
                ]);

  }

  public function level_of_education(){

      $level_of_education=LevelOfEducation::all();

      return response()->json([

                'status' => true,
                'level_of_education' => $level_of_education,
                ]);

  }
  public function languages(){

      $languages=Language::all();

      return response()->json([

                'status' => true,
                'languages' => $languages,
                ]);

  }
   public function course_levels(){

       $course_levels=CourseLevel::all();

       return response()->json([

                'status' => true,
                'course_levels' => $course_levels,
                ]);

   }
   public function timezones(){

       $timezones=TimeZone::all();

       return response()->json([

                'status' => true,
                'timezones' => $timezones,
                ]);

   }
   public function programs(){

       $programs=Program::where('status',1)->get();

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
                ],400) ;
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
   public function field_of_study(Request $request){

         $rules = [

            'program_id' => 'required',
        ];

          if($request->program_id == 3){

                  $rules['country_id'] = 'required';
                  $rules['grade'] = 'required';
            }

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


       $field_of_study=FieldOfStudy::where('program_id',$request->program_id)->get();


         if($request->program_id == 3){

                  $field_of_study=FieldOfStudy::where('program_id',$request->program_id)->where('country_id',$request->country_id)->where('grade',$request->grade)->get();

            }


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
                ],400) ;
            // return $this->respondWithError($errors,500);
        }

       $subjects=Subject::where('field_id',$request->field_id)->get();

       return response()->json([

                'status' => true,
                'subjects' => $subjects,
                ]) ;

   }
     public function get_step(){




        $filtered_teacher = User::

        whereHas('spokenLanguages', function ($query)  {

        })

        ->  whereHas('teacherAvailability', function ($query)  {

        })
        ->  whereHas('teacherProgram', function ($query)  {

        })
         -> whereHas('teacherSubject', function ($query)  {

        })
         -> whereHas('teacherQualifications', function ($query)  {

        })
         -> whereHas('teacherSpecifications', function ($query)  {

        })

        ->where('role_name', 'teacher')->where('id', 1128)->get();

        return response()->json([
            'success' => true,
            'filtered_teacher' => $filtered_teacher,
        ]);
    }


}
