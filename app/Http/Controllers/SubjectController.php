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

use App\TimeZone;

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

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

          $subjects=Subject::with('program','country','field')->get();
         // $subjects=Subject::all();

         if(isset($request->field_id)){

            $subjects=Subject::where('field_id',$request->field_id)->get();
         }

       return response()->json([

                'status' => true,
                'subject' => $subjects,
                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $rules = [

            'program_id' => 'required',
         
            'field_id' => 'required',
            'name' => 'required',
            'price_per_hour' => 'required',
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
           
        }

        $subject=new subject();
        $subject->program_id=$request->program_id;
        $subject->field_id=$request->field_id;
        if($request->program_id == 3){

                 $FieldOfStudy->country_id=$request->country_id;
                 $FieldOfStudy->grade=$request->grade;
            }
        $subject->name=$request->name;
        $subject->price_per_hour=$request->price_per_hour;
        $subject->save();

        $subject=Subject::with('program','country','field')->find($subject->id);

        return response()->json([
            'success' => true,
            'message' => "subject stored successfully",
            'subject' => $subject,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
         $subject = Subject::with('program','country','field')->find($id);
        if (is_null($subject)) {
            return response()->json('Data not found', 404); 
        }
         return response()->json([
            'success' => true,
            'message' => "subject data  retrieved successfully",
            'subject' => $subject,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
          $rules = [

           
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
           
        }


        $subject=Subject::find($id);
        if (is_null($subject)) {
            return response()->json(['message'=>'Data not found'], 404); 
        }
        $subject->update($request->all());

          $subject=Subject::with('program','country','field')->find($subject->id);

         return response()->json([
            'success' => true,
            'message' => "subject data updated successfully",
            'subject' => $subject,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
           
         $subject = Subject::find($id);
        if (is_null($subject)) {
            return response()->json('Data not found', 404); 
        }
        $subject->delete();
         return response()->json([
            'success' => true,
            'message' => "subject deleted successfully",
        ]);
    }
}
