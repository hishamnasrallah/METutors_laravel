<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Newsletter;
use App\Models\Role;
use App\Models\UserMeta;
use App\TeacherDocument;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $subjects = Subject::with('program', 'country', 'field')->get();
        // $subjects=Subject::all();

        if (isset($request->field_id)) {
            $subjects = Subject::with('program', 'country', 'field')->where('field_id', $request->field_id)->get();
        }

        if ($request->has('search')) {

            if ($request->has('field_id')) {
                $subjects = Subject::with('program', 'country', 'field')
                    ->where('field_id', $request->field_id)
                    ->where(function ($q) use ($request) {
                        $q->where('name', 'LIKE', "%$request->search%")
                            ->orWhere('name_ar', 'LIKE', "%$request->search%")
                            ->orWhere('description', 'LIKE', "%$request->search%")
                            ->orWhere('description_ar', 'LIKE', "%$request->search%");
                    })
                    ->get();
            } else {
                $subjects = Subject::with('program', 'country', 'field')
                    ->where('name', 'LIKE', "%$request->search%")
                    ->orWhere('name_ar', 'LIKE', "%$request->search%")
                    ->orWhere('description', 'LIKE', "%$request->search%")
                    ->orWhere('description_ar', 'LIKE', "%$request->search%")
                    ->get();
            }
        }


        return response()->json([

            'status' => true,
            'subject' => $this->paginate($subjects, $request->per_page ?? 10),
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
            'name_ar' => 'required',
            'description' => 'required',
            'description_ar' => 'required',
            'price_per_hour' => 'required',
        ];

        if ($request->program_id == 3) {

            $rules['country_id'] = 'required';
            $rules['grade'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([

                'status' => 'false',
                'errors' => $errors,
            ], 400);
        }

        $subject = new subject();
        $subject->program_id = $request->program_id;
        $subject->field_id = $request->field_id;
        if ($request->program_id == 3) {

            $subject->country_id = $request->country_id;
            $subject->grade = $request->grade;
        }
        $subject->name = $request->name;
        $subject->name_ar = $request->name_ar;
        $subject->description = $request->description;
        $subject->description_ar = $request->description_ar;
        $subject->price_per_hour = $request->price_per_hour;
        $subject->save();

        $subject = Subject::with('program', 'country', 'field')->find($subject->id);

        return response()->json([
            'success' => true,
            'message' => "Subject stored successfully",
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

        $subject = Subject::with('program', 'country', 'field')->find($id);
        if (is_null($subject)) {
            return response()->json('Data not found', 404);
        }
        return response()->json([
            'success' => true,
            'message' => "Subject data retrieved successfully",
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
        $rules = [];





        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([

                'status' => 'false',
                'errors' => $errors,
            ], 400);
        }


        $subject = Subject::find($id);
        if (is_null($subject)) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $subject->update($request->all());

        $subject = Subject::with('program', 'country', 'field')->find($subject->id);

        return response()->json([
            'success' => true,
            'message' => "Subject data updated successfully",
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
            'message' => "Subject deleted successfully",
        ]);
    }


    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }
}
