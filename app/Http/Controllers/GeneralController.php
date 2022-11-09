<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Newsletter;
use App\Models\Role;
use App\TeacherDocument;
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
use App\Models\AcademicClass;
use App\Models\UserFeedback;
use App\TeacherSubject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use stdClass;
use Str;

class GeneralController extends Controller
{


    public function testing_verify()
    {

        return 'very good';
    }

    public function field_of_studies()
    {

        $field_of_study = FieldOfStudy::all();

        return response()->json([

            'status' => true,
            'field_of_study' => $field_of_study,
        ]);
    }

    public function level_of_education()
    {

        $level_of_education = LevelOfEducation::all();

        return response()->json([

            'status' => true,
            'level_of_education' => $level_of_education,
        ]);
    }
    public function languages()
    {

        $languages = Language::all();

        return response()->json([

            'status' => true,
            'languages' => $languages,
        ]);
    }
    public function course_levels()
    {

        $course_levels = CourseLevel::all();

        return response()->json([

            'status' => true,
            'course_levels' => $course_levels,
        ]);
    }
    public function timezones()
    {

        $timezones = TimeZone::all();

        return response()->json([

            'status' => true,
            'timezones' => $timezones,
        ]);
    }
    public function programs()
    {

        $programs = Program::where('status', 1)->orderBy('order', 'ASC')->get();

        return response()->json([

            'status' => true,
            'programs' => $programs,
        ]);
    }
    public function countries()
    {

        $countries = Country::whereHas('cities')->get();

        return response()->json([

            'status' => true,
            'countries' => $countries,
        ]);
    }

    public function cities(Request $request)
    {

        $rules = [
            'country_id' => 'required',
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

        $cities = City::where('country_id', $request->country_id)->get();

        return response()->json([
            'status' => true,
            'cities' => $cities,
        ]);
    }

    public function subjects()
    {

        $subjects = Subject::with('field', 'country')->get();

        return response()->json([

            'status' => true,
            'subjects' => $subjects,
        ]);
    }
    public function field_of_study(Request $request)
    {

        $rules = [

            'program_id' => 'required',
        ];

        if ($request->program_id == 3) {

            $rules['country_id'] = 'required';
            // $rules['grade'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([

                'status' => 'false',
                'errors' => $errors,
            ], 400);
            // return $this->respondWithError($errors,500);
        }


        $field_of_study = FieldOfStudy::where('program_id', $request->program_id)->get();


        if ($request->program_id == 3) {

            $field_of_study = FieldOfStudy::where('program_id', $request->program_id)->where('country_id', $request->country_id)
                // ->where('grade', $request->grade)
                ->get();
        }


        return response()->json([

            'status' => true,
            'field_of_study' => $field_of_study,
        ]);
    }
    public function field_subjects(Request $request)
    {

        $rules = [

            'field_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([

                'status' => 'false',
                'errors' => $errors,
            ], 400);
            // return $this->respondWithError($errors,500);
        }

        $subjects = Subject::where('field_id', $request->field_id)->get();

        return response()->json([

            'status' => true,
            'subjects' => $subjects,
        ]);
    }
    public function get_step()
    {




        $filtered_teacher = User::whereHas('spokenLanguages', function ($query) {
        })

            ->whereHas('teacherAvailability', function ($query) {
            })
            ->whereHas('teacherProgram', function ($query) {
            })
            ->whereHas('teacherSubject', function ($query) {
            })
            ->whereHas('teacherQualifications', function ($query) {
            })
            ->whereHas('teacherSpecifications', function ($query) {
            })

            ->where('role_name', 'teacher')->where('id', 1128)->get();

        return response()->json([
            'success' => true,
            'filtered_teacher' => $filtered_teacher,
        ]);
    }

    public function teachers($program_id, Request $request)
    {

        if ($program_id == 3) {
            $rules = [
                'country_id' => 'required',
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
        }


        //if program id is 3
        if ($program_id == 3) {

            $field_of_studies = FieldOfStudy::select('id', 'name', 'program_id')
                ->where('program_id', $program_id)
                ->where('country_id', $request->country_id)
                ->get();
            //if request has field_id
            if ($request->has('field_ids')) {
                $field_ids = json_decode($request->field_ids);
                //if request has search
                if ($request->has('search')) {
                    $teachers = User::where('role_name', 'teacher')
                        ->whereHas('teacher_subjects', function ($q) use ($program_id, $field_ids) {
                            $q->where('program_id', $program_id)
                            ->where('country_id', $request->country_id)
                            ->whereIn('field_id', $field_ids);;
                        })
                        ->with('teacherQualifications')
                        ->with('country')
                        ->where('admin_approval', 'approved')
                        ->where('status', 'active')
                        ->select('id', 'first_name', 'last_name', 'bio', 'country','avatar')
                        ->where(function ($q) use ($request) {
                            $q->where('first_name', 'LIKE', "%$request->search%")
                                ->orWhere('last_name', 'LIKE', "%$request->search%");
                        })
                        ->get();
                } else {
                    $teachers = User::where('role_name', 'teacher')

                        ->whereHas('teacher_subjects', function ($q) use ($program_id, $request) {
                            $q->where('program_id', $program_id)->where('country_id', $request->country_id);
                        })
                        ->with('teacherQualifications')
                        ->with('country')
                        ->where('admin_approval', 'approved')
                        ->where('status', 'active')
                        ->select('id', 'first_name', 'last_name', 'bio', 'country','avatar')
                        ->get();
                }
            } else {
                //if request has search
                if ($request->has('search')) {
                    $teachers = User::where('role_name', 'teacher')
                        ->whereHas('teacher_subjects', function ($q) use ($program_id, $request) {
                            $q->where('program_id', $program_id)->where('country_id', $request->country_id);
                        })
                        ->with('teacherQualifications')
                        ->with('country')
                        ->where('admin_approval', 'approved')
                        ->where('status', 'active')
                        ->select('id', 'first_name', 'last_name', 'bio', 'country','avatar')
                        ->where(function ($q) use ($request) {
                            $q->where('first_name', 'LIKE', "%$request->search%")
                                ->orWhere('last_name', 'LIKE', "%$request->search%");
                        })
                        ->get();
                } else {
                    $teachers = User::where('role_name', 'teacher')

                        ->whereHas('teacher_subjects', function ($q) use ($program_id, $request) {
                            $q->where('program_id', $program_id)->where('country_id', $request->country_id);
                        })
                        ->with('teacherQualifications')
                        ->with('country')
                        ->where('admin_approval', 'approved')
                        ->where('status', 'active')
                        ->select('id', 'first_name', 'last_name', 'bio', 'country','avatar')
                        ->get();
                }
            }
        } else {
            $field_of_studies = FieldOfStudy::select('id', 'name', 'program_id')
            ->where('program_id', $program_id)
            ->get();

            //if request has field_ids
            if ($request->has('field_ids')) {
                $field_ids = json_decode($request->field_ids);
                //if request has search
                if ($request->has('search')) {
                    $teachers = User::where('role_name', 'teacher')

                        ->whereHas('teacher_subjects', function ($q) use ($program_id, $field_ids) {
                            $q->where('program_id', $program_id)
                            ->whereIn('field_id', $field_ids);
                        })
                        ->with('teacherQualifications')
                        ->with('country')
                        ->where('admin_approval', 'approved')
                        ->where('status', 'active')
                        ->select('id', 'first_name', 'last_name', 'bio', 'country','avatar')
                        ->where(function ($q) use ($request) {
                            $q->where('first_name', 'LIKE', "%$request->search%")
                                ->orWhere('last_name', 'LIKE', "%$request->search%");
                        })
                        ->get();
                } else {
                    $teachers = User::where('role_name', 'teacher')

                        ->whereHas('teacher_subjects', function ($q) use ($program_id) {
                            $q->where('program_id', $program_id);
                        })
                        ->with('teacherQualifications')
                        ->with('country')
                        ->where('admin_approval', 'approved')
                        ->where('status', 'active')
                        ->select('id', 'first_name', 'last_name', 'bio', 'country','avatar')
                        ->get();
                }
            } else {
                //if request has search
                if ($request->has('search')) {
                    $teachers = User::where('role_name', 'teacher')

                        ->whereHas('teacher_subjects', function ($q) use ($program_id) {
                            $q->where('program_id', $program_id);
                        })
                        ->with('teacherQualifications')
                        ->with('country')
                        ->where('admin_approval', 'approved')
                        ->where('status', 'active')
                        ->select('id', 'first_name', 'last_name', 'bio', 'country','avatar')
                        ->where(function ($q) use ($request) {
                            $q->where('first_name', 'LIKE', "%$request->search%")
                                ->orWhere('last_name', 'LIKE', "%$request->search%");
                        })
                        ->get();
                } else {
                    $teachers = User::where('role_name', 'teacher')

                        ->whereHas('teacher_subjects', function ($q) use ($program_id) {
                            $q->where('program_id', $program_id);
                        })
                        ->with('teacherQualifications')
                        ->with('country')
                        ->where('admin_approval', 'approved')
                        ->where('status', 'active')
                        ->select('id', 'first_name', 'last_name', 'bio', 'country','avatar')
                        ->get();
                }
            }
        }






        $all_teachers = [];


        foreach ($teachers as $teacher) {
            $classes = AcademicClass::where('teacher_id',  $teacher->id)->where('status', 'completed')->count();
            $programs = TeacherSubject::with('country', 'program')->where('user_id',  $teacher->id)->get();

            $teacher_programs = [];
            $all_programs = [];
            foreach ($programs as $program) {
            
                if(!(in_array($program->program_id,$all_programs))){
                    $all_programs[] = $program->program_id ;
                    $Program = new stdClass();
                    $Program->program_id = $program->program_id;
                    $Program->program_name = $program['program']->name;
                    $Program->program_code = $program['program']->code;
                    $Program->country = $program['country'] != '' ? $program['country']->name : null;
                    $Program->iso_code = $program['country'] != '' ? $program['country']->iso3 : null;
    
                    $teacher_programs[] = $Program;
                   
                }
            }
            $subjects = TeacherSubject::where('user_id',  $teacher->id)->pluck('subject_id')->unique();

            $Teacher = new stdClass();
            $Teacher->id = $teacher->id;
            $Teacher->name = $teacher->first_name . ' ' . Str::limit($teacher->last_name, 1, '') . '.';
            $Teacher->country = Country::where('id', $teacher->country)->select('name')->first();
            $Teacher->bio = $teacher->bio;
            $Teacher->university = $teacher['teacherQualifications']->name_of_university;
            $Teacher->avatar =  $teacher->avatar;
            $Teacher->classes_taught = $classes;



            //-------rating--------
            $average_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id',  $teacher->id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id',  $teacher->id)->count();
            if ($total_reviews > 0) {
                $average_rating = $rating_sum / $total_reviews;
            }
            $reviews = UserFeedback::where('receiver_id',  $teacher->id)->get();
            $reviews_count = $reviews->groupBy('sender_id')->count();

            $Teacher->reviews_count  = $reviews_count;
            $Teacher->average_rating = Str::limit($average_rating, 3, '');
            $Teacher->programs = $teacher_programs;
            $Teacher->subjects = Subject::whereIn('id', $subjects)->select('id', 'name')->get();

            $all_teachers[] =  $Teacher;
        }

        return response()->json([
            'success' => true,
            'teachers' => $this->paginate($all_teachers, $request->per_page ?? 10),
            'field_of_studies' => $field_of_studies,
        ]);
    }

    public function all_teachers(Request $request)
    {
        $field_of_studies = FieldOfStudy::select('id', 'name', 'program_id')
        ->get();

        

        if($request->has('field_ids')){
            $field_ids = json_decode($request->field_ids);
            if ($request->has('search')) {
                $teachers = User::where('role_name', 'teacher')
                    ->with('teacher_subjects', 'teacherQualifications')
                    ->with('country')
                    ->where('admin_approval', 'approved')
                    ->where('status', 'active')
                    ->select('id', 'first_name', 'last_name', 'bio', 'country','avatar')
                    ->where(function ($q) use ($request) {
                        $q->where('first_name', 'LIKE', "%$request->search%")
                            ->orWhere('last_name', 'LIKE', "%$request->search%");
                    })
                    ->whereHas('teacher_subjects', function ($q) use ($field_ids) {
                        $q->whereIn('field_id', $field_ids);
                    })                  
                    ->get();
            } else {
                $teachers = User::where('role_name', 'teacher')
                    ->with('teacher_subjects', 'teacherQualifications')
                    ->with('country')
                    ->where('admin_approval', 'approved')
                    ->where('status', 'active')
                    ->whereHas('teacher_subjects', function ($q) use ($field_ids) {
                        $q->whereIn('field_id', $field_ids);
                    })   
                    ->select('id', 'first_name', 'last_name', 'bio', 'country','avatar')
                    ->get();
            }

        }else{
            if ($request->has('search')) {
                $teachers = User::where('role_name', 'teacher')
                    ->with('teacher_subjects', 'teacherQualifications')
                    ->with('country')
                    ->where('admin_approval', 'approved')
                    ->where('status', 'active')
                    ->select('id', 'first_name', 'last_name', 'bio', 'country','avatar')
                    ->where(function ($q) use ($request) {
                        $q->where('first_name', 'LIKE', "%$request->search%")
                            ->orWhere('last_name', 'LIKE', "%$request->search%");
                    })
                    ->get();
            } else {
                $teachers = User::where('role_name', 'teacher')
                    ->with('teacher_subjects', 'teacherQualifications')
                    ->with('country')
                    ->where('admin_approval', 'approved')
                    ->where('status', 'active')
                    ->select('id', 'first_name', 'last_name', 'bio', 'country','avatar')
                    ->get();
            }
        }
        

        foreach ($teachers as $teacher) {
            $classes = AcademicClass::where('teacher_id',  $teacher->id)->where('status', 'completed')->count();
            $programs = TeacherSubject::with('country', 'program')->where('user_id',  $teacher->id)->get();

            $teacher_programs = [];
            $all_programs = [];
            foreach ($programs as $program) {

                if(!(in_array($program->program_id,$all_programs))){
                    $all_programs[] = $program->program_id ;
                    $Program = new stdClass();
                    $Program->program_id = $program->program_id;
                    $Program->program_name = $program['program']->name;
                    $Program->program_code = $program['program']->code;
                    $Program->country = $program['country'] != '' ? $program['country']->name : null;
                    $Program->iso_code = $program['country'] != '' ? $program['country']->iso3 : null;
    
                    $teacher_programs[] = $Program;
                   
                }

                
                
            }
            $subjects = TeacherSubject::where('user_id',  $teacher->id)->pluck('subject_id')->unique();

            $Teacher = new stdClass();
            $Teacher->id = $teacher->id;
            $Teacher->name = $teacher->first_name . ' ' . Str::limit($teacher->last_name, 1, '') . '.';
            $Teacher->country = Country::where('id', $teacher->country)->select('name')->first();
            $Teacher->bio = $teacher->bio;
            $Teacher->avatar = $teacher->avatar;
            $Teacher->university = $teacher['teacherQualifications']->name_of_university;
            $Teacher->classes_taught = $classes;



            //-------rating--------
            $average_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id',  $teacher->id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id',  $teacher->id)->count();
            if ($total_reviews > 0) {
                $average_rating = $rating_sum / $total_reviews;
            }
            $reviews = UserFeedback::where('receiver_id',  $teacher->id)->get();
            $reviews_count = $reviews->groupBy('sender_id')->count();

            $Teacher->reviews_count  = $reviews_count;
            $Teacher->average_rating = Str::limit($average_rating, 3, '');
            $Teacher->programs = $teacher_programs;
            $Teacher->subjects = Subject::whereIn('id', $subjects)->select('id', 'name')->get();

            $all_teachers[] =  $Teacher;
        }

        return response()->json([
            'success' => true,
            'teachers' => $this->paginate($all_teachers, $request->per_page ?? 10),
            'field_of_studies' => $field_of_studies,
        ]);
    }


    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }
}
