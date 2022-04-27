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
use App\FieldOfStudy;
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

class FieldOfStudyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $FieldOfStudys=FieldOfStudy::with('program','country')->get();

         if(isset($request->country_id) && isset($request->grade)){

             $FieldOfStudys=FieldOfStudy::where('country_id',$request->country_id)->whereIn('grade',$request->grade)->get();
         }

        if(isset($request->program_id)){

             $FieldOfStudys=FieldOfStudy::where('program_id',$request->program_id)->get();
         }

         return response()->json([

                'status' => true,
                'FieldOfStudy' => $FieldOfStudys,
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
            'name' => 'required',
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

        $FieldOfStudy=new FieldOfStudy();
        $FieldOfStudy->program_id=$request->program_id;
         if($request->program_id == 3){

                 $FieldOfStudy->country_id=$request->country_id;
                 $FieldOfStudy->grade=$request->grade;
            }
        $FieldOfStudy->name=$request->name;
        $FieldOfStudy->save();

        $FieldOfStudy=FieldOfStudy::with('program','country')->find($FieldOfStudy->id);

        return response()->json([
            'success' => true,
            'message' => "FieldOfStudy stored successfully",
            'FieldOfStudy' => $FieldOfStudy,
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
        
         $FieldOfStudy = FieldOfStudy::find($id);
        if (is_null($FieldOfStudy)) {
            return response()->json('Data not found', 404); 
        }
         return response()->json([
            'success' => true,
            'message' => "FieldOfStudy data  retrieved successfully",
            'FieldOfStudy' => $FieldOfStudy,
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

         if($request->program_id == 3){

                  $rules['country_id'] = 'required';
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


        $FieldOfStudy=FieldOfStudy::find($id);
        if (is_null($FieldOfStudy)) {
            return response()->json(['message'=>'Data not found'], 404); 
        }
        $FieldOfStudy->update($request->all());

           $FieldOfStudy=FieldOfStudy::with('program','country')->find($FieldOfStudy->id);
         return response()->json([
            'success' => true,
            'message' => "FieldOfStudy data updated successfully",
            'FieldOfStudy' => $FieldOfStudy,
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
           
         $FieldOfStudy = FieldOfStudy::find($id);
        if (is_null($FieldOfStudy)) {
            return response()->json('Data not found', 404); 
        }
        $FieldOfStudy->delete();
         return response()->json([
            'success' => true,
            'message' => "FieldOfStudy deleted successfully",
        ]);
    }
}