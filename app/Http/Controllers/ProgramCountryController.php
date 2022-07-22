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
use App\Models\ProgramCountry;
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
use PragmaRX\Countries\Package\Countries;
use IlluminateAgnostic\Arr\Support\Arr;

class ProgramCountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $program_countries = ProgramCountry::all();


        return response()->json([
            'status' => true,
            'program_countries' => $program_countries,
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

        $countries = Countries::all();
        $country = $countries->where('name.common', $request->name)->first();


        $program_country = new ProgramCountry();
        $program_country->name = $country->name->common;
        $program_country->flag =  $country->flag['flag-icon'];
        $program_country->save();

        return response()->json([
            'success' => true,
            'message' => "Program_country stored successfully",
            'program_country' => $program_country,
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

        $program_country = ProgramCountry::find($id);
        if (is_null($program_country)) {
            return response()->json('Data not found', 404);
        }
        return response()->json([
            'success' => true,
            'message' => "Program data  retrieved successfully",
            'program_country' => $program_country,
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
        $program_country = ProgramCountry::find($id);
        if (is_null($program_country)) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $program_country->update($request->all());
        return response()->json([
            'success' => true,
            'message' => "Program data updated successfully",
            'program_country' => $program_country,
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

        $program_country = ProgramCountry::find($id);
        if (is_null($program_country)) {
            return response()->json('Data not found', 404);
        }
        $program_country->delete();
        return response()->json([
            'success' => true,
            'message' => "Program_country deleted successfully",
        ]);
    }


    public function program_countries(Request $request)
    {

        $program_countries = ProgramCountry::paginate($request->per_page ?? 10);

        if ($request->has('status')) {
            $program_countries = ProgramCountry::where('status', 1)->paginate($request->per_page ?? 10);
        }

        if ($request->has('search')) {
            $program_countries = ProgramCountry::where('name', 'LIKE', "%$request->search%")
                ->whereIn('status', [$request->status ?? 0, 1])
                ->paginate($request->per_page ?? 10);
        }


        return response()->json([
            'status' => true,
            'program_countries' => $program_countries,
        ]);
    }



    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }

    public function countries()
    {
        $countries = Countries::all();
        $Countries = [];
        foreach ($countries as $country) {
            array_push($Countries, [
                'name' => $country->name->common,
                'flag' => $country->flag['flag-icon'],
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => "Country with Flags",
            'countries' => $Countries,
        ]);
    }
}
