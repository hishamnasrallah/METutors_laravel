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

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $programs=Program::all();

       return response()->json([

                'status' => true,
                'programs' => $programs,
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

            'name' => 'required',
            'description' => 'required',
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

        $program=new Program();
        $program->name=$request->name;
        $program->description=$request->description;
        $program->save();

        $program=Program::find($program->id);

        return response()->json([
            'success' => true,
            'message' => "Program stored successfully",
            'program' => $program,
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
        
         $program = Program::find($id);
        if (is_null($program)) {
            return response()->json('Data not found', 404); 
        }
         return response()->json([
            'success' => true,
            'message' => "Program data  retrieved successfully",
            'program' => $program,
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
        $program=Program::find($id);
        if (is_null($program)) {
            return response()->json(['message'=>'Data not found'], 404); 
        }
        $program->update($request->all());

          $program=Program::find($program->id);
         return response()->json([
            'success' => true,
            'message' => "Program data updated successfully",
            'program' => $program,
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
           
         $program = Program::find($id);
        if (is_null($program)) {
            return response()->json('Data not found', 404); 
        }
        $program->delete();
        
         return response()->json([
            'success' => true,
            'message' => "Program deleted successfully",
        ]);
    }
}
