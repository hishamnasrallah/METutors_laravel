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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $programs = Program::paginate($request->per_page ?? 10);

        if ($request->has('status')) {
            $programs = Program::where('status', $request->status)->paginate($request->per_page ?? 10);
        }

        if ($request->has('search')) {
            $programs = Program::where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%$request->search%")
                    ->orwhere('description', 'LIKE', "%$request->search%")
                    ->orwhere('code', $request->search)
                    ->orwhere('status', $request->search);
            })
                ->paginate($request->per_page ?? 10);
        }

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

            'name' => 'required|unique:programs',
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
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

        $program = new Program();
        $program->name = $request->name;
        $program->title = $request->title;
        $program->description = $request->description;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads/program_images'), $imageName);
            $program->image = $imageName;
        }
        $program->save();

        $program = Program::find($program->id);

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
        $program = Program::findOrFail($id);
        $rules = [
            'name' =>  Rule::unique('programs')->ignore($program->id, 'id'),
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


        if (is_null($program)) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $program->name = $request->name;
        $program->title = $request->title;
        $program->description = $request->description;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads/program_images'), $imageName);
            $program->image = $imageName;
        }
        $program->update();

        $program = Program::findOrFail($id);
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


    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }
}
