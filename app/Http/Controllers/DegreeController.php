<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\DegreeField;
use App\Models\DegreeLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;
use Str;

class DegreeController extends Controller
{
    public function degree_levels(){
        $levels = DegreeLevel::all();

        return response()->json([
            'status' => true,
            'message' => trans('api_messages.DEGREE_LEVELS'),
            'levels' => $levels,
        ]);
    }

    public function degree_fields($level_id){
        $fields = DegreeField::where('degree_level_id',$level_id)->get();

        return response()->json([
            'status' => true,
            'message' => trans('api_messages.DEGREE_FIELDS'),
            'fields' => $fields,
        ]);
    }

    public function test(){

            $test = Course::with('assignments')->find(39);
              return response()->json([
                'status' => true,
                'test' => $test,
              ]);
    }
}
