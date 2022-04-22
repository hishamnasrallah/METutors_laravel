<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Badge;
use App\Models\BecomeInstructor;
use App\Models\Category;
use App\Models\Course;
use App\Models\Newsletter;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\UserOccupation;
use App\Models\Webinar;
use App\User;
use App\Models\Role;
use App\Models\Follow;
use App\Models\Meeting;
use App\Models\UserFeedback;
use App\Models\UserPrefrence;
use App\TeacherAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    public function dashboard()
    {
        return view(getTemplate() . '.panel.dashboard');
    }


    /********************* Filter Teacher ***************************/

    public function filteredTeacher(Request $request)
    {

        $language_id = $request->language_id;

        // 		$teacher_availability_id = $request->teacher_availability_id;

        $teacher_program_id = $request->teacher_program_id;

        $teacher_qualification_id = $request->teacher_qualification_id;

        $teacher_specification_id = $request->teacher_specification_id;

        $teacher_subject_id = $request->teacher_subject_id;

        $filtered_teacher = User::select('id', 'first_name', 'last_name', 'role_name', 'date_of_birth', 'mobile', 'email',  'verified', 'avatar', 'bio', 'status', 'created_at', 'updated_at')->where('role_name', 'teacher')->where('verified', 1)->get();

        return response()->json([
            'success' => true,
            'filtered_teacher' => $filtered_teacher,
        ]);
    }

    /****End*****/



    public function student_profile($id)
    {
        $user = User::select('id', 'first_name', 'last_name', 'role_name', 'date_of_birth', 'mobile', 'email',  'verified', 'avatar', 'bio', 'status', 'created_at', 'updated_at')->where('id', $id)->where('role_name', '!=', 'admin')->where('role_name', 'student')
            ->first();

        $user->joined_date = $user->created_at;

        //  $user->userMetas;

        if (!$user) {

            return response()->json([
                'status' => false,
                'message' => "User Not found",
            ], 204);
        }


        return response()->json([
            'status' => true,
            'user' => $user,
        ]);
    }


    public function teacher_profile($id)
    {

        $user = User::select('id', 'first_name', 'last_name', 'role_name', 'date_of_birth', 'mobile', 'email',  'verified', 'avatar', 'bio', 'status', 'created_at', 'updated_at')->where('id', $id)->where('role_name', '!=', 'admin')->where('role_name', '!=', 'student')->where('role_name', 'teacher')
            ->first();

        $user->joined_date = $user->created_at;

        if (!$user) {

            return response()->json([
                'status' => false,
                'message' => "User Not found",
            ], 204);
        }

        $user->userMetas;
        $user->teacherSpecifications;
        $user->teacherQualifications;


        return response()->json([
            'status' => true,
            'user' => $user,
        ]);
    }


    public function teacher_public_profile(Request $request)
    {


        $rules = [
            'id' => 'required',
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



        $user = User::select('id', 'first_name', 'last_name', 'role_name', 'date_of_birth', 'mobile', 'email',  'verified', 'avatar', 'bio', 'status', 'created_at', 'updated_at')->where('role_name', '!=', 'admin')->where('role_name', '!=', 'student')->where('role_name', 'teacher')->with('spokenLanguages', 'teacherAvailability', 'teacherProgram', 'teacherSpecifications', 'teacherSubject', 'teacherQualifications', 'teacherSubject.subject', 'teacherSubject.field')->where('id', $request->id)->first();

        if (!$user) {

            return response()->json([
                'status' => false,
                'message' => "User Not found",
            ], 204);
        }

        return response()->json([
            'status' => true,
            'user' => $user,
        ]);
    }


    public function admin_profile($id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $user = User::select('id', 'first_name', 'last_name', 'role_name', 'mobile', 'email',  'verified', 'avatar', 'created_at', 'updated_at')->where('id', $token_user->id)->where('role_name', $token_user->role_name)
            ->first();

        if (!$user) {

            return response()->json([
                'status' => false,
                'message' => "User Not found",
            ], 204);
        }



        return response()->json([
            'status' => true,
            'user' => $user,
        ]);
    }

    public function followToggle($id)
    {
        $authUser = auth()->user();
        $user = User::where('id', $id)->first();

        $followStatus = false;
        $follow = Follow::where('follower', $authUser->id)
            ->where('user_id', $user->id)
            ->first();

        if (empty($follow)) {
            Follow::create([
                'follower' => $authUser->id,
                'user_id' => $user->id,
                'status' => Follow::$accepted,
            ]);

            $followStatus = true;
        } else {
            $follow->delete();
        }

        return response()->json([
            'code' => 200,
            'follow' => $followStatus
        ], 200);
    }

    public function availableTimes(Request $request, $id)
    {
        $timestamp = $request->get('timestamp');

        $user = User::where('id', $id)
            ->whereIn('role_name', [Role::$teacher, Role::$organization])
            ->where('status', 'active')
            ->first();

        if (!$user) {
            abort(404);
        }

        $meeting = Meeting::where('creator_id', $user->id)
            ->with(['meetingTimes'])
            ->first();

        $meetingTimes = [];

        if (!empty($meeting->meetingTimes)) {
            foreach ($meeting->meetingTimes->groupBy('day_label') as $day => $meetingTime) {

                foreach ($meetingTime as $time) {
                    $can_reserve = true;

                    $explodetime = explode('-', $time->time);
                    $secondTime = dateTimeFormat(strtotime($explodetime['0']), 'H') * 3600 + dateTimeFormat(strtotime($explodetime['0']), 'i') * 60;

                    $reserveMeeting = ReserveMeeting::where('meeting_time_id', $time->id)
                        ->where('day', dateTimeFormat($timestamp, 'Y-m-d'))
                        ->where('meeting_time_id', $time->id)
                        ->first();

                    if ($reserveMeeting && ($reserveMeeting->locked_at || $reserveMeeting->reserved_at)) {
                        $can_reserve = false;
                    }

                    if ($timestamp + $secondTime < time()) {

                        $can_reserve = false;
                    }
                    $meetingTimes[$day]["times"][] = ["id" => $time->id, "time" => $time->time, "can_reserve" => $can_reserve];
                }
            }
        }

        return response()->json($meetingTimes[strtolower(dateTimeFormat($timestamp, 'l'))], 200);
    }

    public function instructors(Request $request)
    {
        $seoSettings = getSeoMetas('instructors');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('home.instructors');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.instructors');
        $pageRobot = getPageRobot('instructors');

        $data = $this->handleInstructorsOrOrganizationsPage($request, Role::$teacher);

        $data['title'] = trans('home.instructors');
        $data['page'] = 'instructors';
        $data['pageTitle'] = $pageTitle;
        $data['pageDescription'] = $pageDescription;
        $data['pageRobot'] = $pageRobot;

        return view('web.default.pages.instructors', $data);
    }

    public function organizations(Request $request)
    {
        $seoSettings = getSeoMetas('organizations');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('home.organizations');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.organizations');
        $pageRobot = getPageRobot('organizations');

        $data = $this->handleInstructorsOrOrganizationsPage($request, Role::$organization);

        $data['title'] = trans('home.organizations');
        $data['page'] = 'organizations';
        $data['pageTitle'] = $pageTitle;
        $data['pageDescription'] = $pageDescription;
        $data['pageRobot'] = $pageRobot;

        return view('web.default.pages.instructors', $data);
    }

    public function handleInstructorsOrOrganizationsPage(Request $request, $role)
    {
        $query = User::where('role_name', $role)
            //->where('verified', true)
            ->where('users.status', 'active')
            ->where(function ($query) {
                $query->where('users.ban', false)
                    ->orWhere(function ($query) {
                        $query->whereNotNull('users.ban_end_at')
                            ->orWhere('users.ban_end_at', '<', time());
                    });
            })
            ->with(['meeting' => function ($query) {
                $query->with('meetingTimes');
                $query->withCount('meetingTimes');
            }]);

        $instructors = $this->filterInstructors($request, deepClone($query), $role)
            ->paginate(6);

        if ($request->ajax()) {
            $html = null;

            foreach ($instructors as $instructor) {
                $html .= '<div class="col-12 col-lg-4">';
                $html .= (string)view()->make('web.default.pages.instructor_card', ['instructor' => $instructor]);
                $html .= '</div>';
            }

            return response()->json([
                'html' => $html,
                'last_page' => $instructors->lastPage(),
            ], 200);
        }

        if (empty($request->get('sort')) or !in_array($request->get('sort'), ['top_rate', 'top_sale'])) {
            $bestRateInstructorsQuery = $this->getBestRateUsers(deepClone($query), $role);

            $bestSalesInstructorsQuery = $this->getTopSalesUsers(deepClone($query), $role);

            $bestRateInstructors = $bestRateInstructorsQuery
                ->limit(8)
                ->get();

            $bestSalesInstructors = $bestSalesInstructorsQuery
                ->limit(8)
                ->get();
        }

        $categories = Category::where('parent_id', null)
            ->with('subCategories')
            ->get();

        $data = [
            'pageTitle' => trans('home.instructors'),
            'instructors' => $instructors,
            'instructorsCount' => deepClone($query)->count(),
            'bestRateInstructors' => $bestRateInstructors ?? null,
            'bestSalesInstructors' => $bestSalesInstructors ?? null,
            'categories' => $categories,
        ];

        return $data;
    }

    private function filterInstructors($request, $query, $role)
    {
        $categories = $request->get('categories', null);
        $sort = $request->get('sort', null);
        $availableForMeetings = $request->get('available_for_meetings', null);
        $hasFreeMeetings = $request->get('free_meetings', null);
        $withDiscount = $request->get('discount', null);
        $search = $request->get('search', null);


        if (!empty($categories) and is_array($categories)) {
            $userIds = UserOccupation::whereIn('category_id', $categories)->pluck('user_id')->toArray();

            $query->whereIn('users.id', $userIds);
        }

        if (!empty($sort) and $sort == 'top_rate') {
            $query = $this->getBestRateUsers($query, $role);
        }

        if (!empty($sort) and $sort == 'top_sale') {
            $query = $this->getTopSalesUsers($query, $role);
        }

        if (!empty($availableForMeetings) and $availableForMeetings == 'on') {
            $hasMeetings = DB::table('meetings')
                ->where('meetings.disabled', 0)
                ->join('meeting_times', 'meetings.id', '=', 'meeting_times.meeting_id')
                ->select('meetings.creator_id', DB::raw('count(meeting_id) as counts'))
                ->groupBy('creator_id')
                ->orderBy('counts', 'desc')
                ->get();

            $hasMeetingsInstructorsIds = [];
            if (!empty($hasMeetings)) {
                $hasMeetingsInstructorsIds = $hasMeetings->pluck('creator_id')->toArray();
            }

            $query->whereIn('users.id', $hasMeetingsInstructorsIds);
        }

        if (!empty($hasFreeMeetings) and $hasFreeMeetings == 'on') {
            $freeMeetingsIds = Meeting::where('disabled', 0)
                ->where(function ($query) {
                    $query->whereNull('amount')->orWhere('amount', '0');
                })->groupBy('creator_id')
                ->pluck('creator_id')
                ->toArray();

            $query->whereIn('users.id', $freeMeetingsIds);
        }

        if (!empty($withDiscount) and $withDiscount == 'on') {
            $withDiscountMeetingsIds = Meeting::where('disabled', 0)
                ->whereNotNull('discount')
                ->groupBy('creator_id')
                ->pluck('creator_id')
                ->toArray();

            $query->whereIn('users.id', $withDiscountMeetingsIds);
        }

        if (!empty($search)) {
            $query->where(function ($qu) use ($search) {
                $qu->where('users.full_name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%")
                    ->orWhere('users.mobile', 'like', "%$search%");
            });
        }

        return $query;
    }

    private function getBestRateUsers($query, $role)
    {
        $query->leftJoin('webinars', function ($join) use ($role) {
            if ($role == Role::$organization) {
                $join->on('users.id', '=', 'webinars.creator_id');
            } else {
                $join->on('users.id', '=', 'webinars.teacher_id');
            }

            $join->where('webinars.status', 'active');
        })->leftJoin('webinar_reviews', function ($join) {
            $join->on('webinars.id', '=', 'webinar_reviews.webinar_id');
            $join->where('webinar_reviews.status', 'active');
        })
            ->whereNotNull('rates')
            ->select('users.*', DB::raw('avg(rates) as rates'))
            ->orderBy('rates', 'desc');

        if ($role == Role::$organization) {
            $query->groupBy('webinars.creator_id');
        } else {
            $query->groupBy('webinars.teacher_id');
        }

        return $query;
    }

    private function getTopSalesUsers($query, $role)
    {
        $query->leftJoin('sales', function ($join) {
            $join->on('users.id', '=', 'sales.seller_id')
                ->whereNull('refund_at');
        })
            ->whereNotNull('sales.seller_id')
            ->select('users.*', 'sales.seller_id', DB::raw('count(sales.seller_id) as counts'))
            ->groupBy('sales.seller_id')
            ->orderBy('counts', 'desc');

        return $query;
    }

    public function becomeInstructors()
    {
        $user = auth()->user();

        if ($user->isUser()) {
            $categories = Category::where('parent_id', null)
                ->with('subCategories')
                ->get();

            $occupations = $user->occupations->pluck('category_id')->toArray();

            $lastRequest = BecomeInstructor::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            $data = [
                'pageTitle' => trans('site.become_instructor'),
                'user' => $user,
                'lastRequest' => $lastRequest,
                'categories' => $categories,
                'occupations' => $occupations
            ];

            return view('web.default.user.become_instructor', $data);
        }

        abort(404);
    }

    public function becomeInstructorsStore(Request $request)
    {
        $user = auth()->user();

        if ($user->isUser()) {
            $lastRequest = BecomeInstructor::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'accept'])
                ->first();

            if (empty($lastRequest)) {
                $this->validate($request, [
                    'occupations' => 'required',
                    'certificate' => 'nullable|string',
                    'account_type' => 'required',
                    'iban' => 'required',
                    'account_id' => 'required',
                    'identity_scan' => 'required',
                    'description' => 'nullable|string',
                ]);

                $data = $request->all();

                BecomeInstructor::create([
                    'user_id' => $user->id,
                    'certificate' => $data['certificate'],
                    'description' => $data['description'],
                    'created_at' => time()
                ]);

                $user->update([
                    'account_type' => $data['account_type'],
                    'iban' => $data['iban'],
                    'account_id' => $data['account_id'],
                    'identity_scan' => $data['identity_scan'],
                    'certificate' => $data['certificate'],
                ]);

                if (!empty($data['occupations'])) {
                    UserOccupation::where('user_id', $user->id)->delete();

                    foreach ($data['occupations'] as $category_id) {
                        UserOccupation::create([
                            'user_id' => $user->id,
                            'category_id' => $category_id
                        ]);
                    }
                }
            }


            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('site.become_instructor_success_request'),
                'status' => 'success'
            ];
            return back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function makeNewsletter(Request $request)
    {
        $this->validate($request, [
            'newsletter_email' => 'required|string|email|max:255|unique:newsletters,email'
        ]);

        $data = $request->all();
        $user_id = null;
        $email = $data['newsletter_email'];

        if (auth()->check()) {
            $user = auth()->user();

            if ($user->email == $email) {
                $user_id = $user->id;

                $user->update([
                    'newsletter' => true,
                ]);
            }
        }

        Newsletter::create([
            'user_id' => $user_id,
            'email' => $data['newsletter_email'],
            'created_at' => time()
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('site.create_newsletter_success'),
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }

    public function sendMessage(Request $request, $id)
    {
        if (!empty($id)) {
            $user = User::select('id', 'email')
                ->where('id', $id)
                ->first();

            if (!empty($user) and !empty($user->email)) {
                $data = $request->all();

                $validator = Validator::make($data, [
                    'title' => 'required|string',
                    'email' => 'required|email',
                    'description' => 'required|string',
                    'captcha' => 'required|captcha',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'code' => 422,
                        'errors' => $validator->errors()
                    ], 422);
                }

                $mail = [
                    'title' => $data['title'],
                    'message' => trans('site.you_have_message_from', ['email' => $data['email']]) . "\n" . $data['description'],
                ];

                try {
                    \Mail::to($user->email)->send(new \App\Mail\SendNotifications($mail));

                    return response()->json([
                        'code' => 200
                    ]);
                } catch (Exception $e) {
                    return response()->json([
                        'code' => 500,
                        'message' => trans('site.server_error_try_again')
                    ]);
                }
            }

            return response()->json([
                'code' => 403,
                'message' => trans('site.user_disabled_public_message')
            ]);
        }
    }

    //*********/ Vistor: Search Teacher *********
    public function search_teacher(Request $request, $name)
    {
        $result = User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'created_at')
            ->with('teacher_subjects', 'teacher_subjects.subject', 'feedbacks')
            // ->whereHas('feedbacks',function($query){
            //     $query->
            // })
            ->where('role_name', '=', 'teacher')
            ->where('first_name', 'LIKE', "%$name%")
            ->orWhere('last_name', 'LIKE', "%$name%")
            ->get();


        return response()->json([
            'success' => true,
            'teachers' => $result,
        ]);
    }

    public function security_setting(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([

                'status' => false,
                'errors' => $errors,
            ], 400);
        }



        $user = User::find($token_user->id);

        $check = Hash::check($request->current_password, $user->password);

        if ($check) {
            $user->password = Hash::make($request->new_password);
            $user->update();
            return response()->json([
                'status' => true,
                'message' => "Security Settings updated",
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Current Password did't matched!",
            ]);
        }
    }

    public function user_preference(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $rules = [
            'preferred_language' => 'required|integer',
            'preferred_gender' => 'required|string',
            // 'teacher_language' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([

                'status' => false,
                'errors' => $errors,
            ], 400);
        }
        $count = UserPrefrence::where('user_id', $token_user->id)->count();
        if ($count == 0) {
            $prefrence = new UserPrefrence();
        } else {

            $prefrence = UserPrefrence::where('user_id', $token_user->id)->first();
        }

        $prefrence->user_id = $token_user->id;
        $prefrence->preferred_language = $request->preferred_language;
        $prefrence->preferred_gender = $request->preferred_gender;
        if($request->teacher_language){
             $prefrence->teacher_language = $request->teacher_language;
        }else{
              $prefrence->teacher_language = null;
        }
       
        $prefrence->save();

        return response()->json([
            'status' => true,
            'message' => "User Prefrences Added Successfully",
        ]);
    }


    public function get_user_preference(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

       
        $pref = UserPrefrence::where('user_id', $token_user->id)->first();
       
        return response()->json([
            'status' => true,
            'message' => "User Prefrences",
            'preference' => $pref,
        ]);
    }

    //************* Progress of all Courses *************
    public function coursesProgress(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $student = User::find($token_user->id);
        $courses = Course::with('classes')->where('student_id', $student->id)->get();
        $lastarray = [];

        foreach ($courses as $course) {
            $percentage = 0;
            $classes = $course['classes'];

            $completed_classes = 0;
            $remaining_classes = 0;

            foreach ($classes as $class) {
                if ($class->status == 'completed') {
                    $completed_classes = $completed_classes + 1;
                }
                if ($class->status != 'completed') {
                    $remaining_classes = $remaining_classes + 1;
                }
            }
            $total_classes = count($course['classes']);
            if ($total_classes > 0) {
                $percentage = ($completed_classes / $total_classes) * 100;
            }

            array_push(
                $lastarray,
                [
                    'course' => $course,
                    'percentage' => $percentage
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Progress of all courses of Student',
            'courses_progress' => $lastarray
        ]);
    }

    //************* Progress of Specific Course *************
    public function courseProgress($course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $student = User::find($token_user->id);
        $course = Course::with('classes')->where('student_id', $student->id)->where('id', $course_id)->first();

        $percentage = 0;
        $classes = $course['classes'];

        $completed_classes = 0;
        $remaining_classes = 0;

        $total_classes = count($course['classes']);

        foreach ($classes as $class) {
            if ($class->status == 'completed') {
                $completed_classes = $completed_classes + 1;
            }
            if ($class->status != 'completed') {
                $remaining_classes = $remaining_classes + 1;
            }
        }

        if ($total_classes > 0) {
            $percentage = ($completed_classes / $total_classes) * 100;
        }

        return response()->json([
            'status' => true,
            'message' => 'Course Progress',
            'course' => $course,
            'percentage' => $percentage
        ]);
    }

    public function filterTeacher(Request $request)
    {

        $weekdays = json_decode($request->weekdays);
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        // $start_date = $request->start_date;
        // $end_date = $request->end_date;

        $teachers = User::where('role_name', 'teacher')->get();
        $teachers_before = User::select('id')->where('role_name', 'teacher')->get();
        $available_teachers = [];

        foreach ($teachers as $teacher) {
            $availabilities = TeacherAvailability::whereIn('day', $weekdays)->where('user_id', $teacher->id)->get();
            $counter = 0;
            foreach ($availabilities as $availability) {

                $request_from = Carbon::parse($request->start_time)->format('G:i');
                $db_from = Carbon::parse($availability->time_from)->format('G:i');

                $request_to = Carbon::parse($request->end_time)->format('G:i');
                $db_to = Carbon::parse($availability->time_to)->format('G:i');
                if (($request_from >= $db_from) && ($request_from <= $db_to) && ($request_to >= $db_from) && ($request_to <= $db_to)) {
                    $counter++;
                    if ($counter == count($weekdays)) {
                        array_push($available_teachers, $availability->user_id);
                        break;
                    }
                }
            }
        }


        $after_teachers = array_unique($available_teachers);


        return response()->json([
            'status' => true,
            'message' => 'all teachers',
            'teachers_before' => $teachers_before,
            'teachers_after' => $after_teachers,
        ]);
    }

    public function availableTeachers(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        // return $request->language_id;
        $filtered_teacher = User::whereHas('spokenLanguages', function ($q) use ($request) {
            $q->where('language', $request->language_id);
        })
            ->whereHas('teacherProgram', function ($q) use ($request) {
                $q->where('program_id', $request->program_id);
            })
            ->whereHas('teacherSubject', function ($q) use ($request) {
                $q->where(['subject_id' => $request->subject_id, 'field_id' => $request->field_id]);
            })
            ->where('role_name', 'teacher')
            ->get();

        $requestedClasses = json_decode($request->classes);
        $weekdays = [];
        foreach ($requestedClasses as $requestedClass) {
            array_push($weekdays, $requestedClass->day);
        }

        $uniqueWeekdays = array_unique($weekdays);

        //************ Checking Teacher Availabilites ************
        $available_teachers = [];
        $classCounter = 0;
        foreach ($requestedClasses as $requestedClass) {
            $classCounter++;
            foreach ($filtered_teacher as $teacher) {
                $availabilities = TeacherAvailability::whereIn('day', $uniqueWeekdays)->where('user_id', $teacher->id)->get();
                // if (isEmpty($availabilities)) {
                //     array_push($available_teachers, $teacher);
                // } else {
                $counter = 0;
                foreach ($availabilities as $availability) {

                    $request_from = Carbon::parse($requestedClass->start_time)->format('G:i');
                    $db_from = Carbon::parse($availability->time_from)->format('G:i');

                    $request_to = Carbon::parse($requestedClass->end_time)->format('G:i');
                    $db_to = Carbon::parse($availability->time_to)->format('G:i');
                    if (($request_from >= $db_from) && ($request_from <= $db_to) && ($request_to >= $db_from) && ($request_to <= $db_to)) {
                        $counter++;
                        if (($counter == count($uniqueWeekdays)) &&  ($classCounter == count($requestedClasses))) {
                            // echo $classCounter;
                            array_push($available_teachers, $availability->user_id);
                            break;
                        }
                    }
                }
                // }
            }
        }

        $available_teachers = array_unique($available_teachers);

        //************ Checking If Teacher is ALREADY Booked ***********
        $final_teachers = [];
        foreach ($available_teachers as $available_teacher) {
            $flag = 0;
            $nullCounter = 0;
            foreach ($requestedClasses as $requestedClass) {

                $start_time = Carbon::parse($requestedClass->start_time)->format('G:i');
                $end_time = Carbon::parse($requestedClass->end_time)->format('G:i');
                $teacherClasses = AcademicClass::where('teacher_id', $available_teacher)->where('day', $requestedClass->day)->where('start_date', $requestedClass->date)->get();
                //************ If teacher has no Classes In classes Table ***********
                if (count($teacherClasses) == 0) {
                    $nullCounter++;
                    if ($nullCounter == count($requestedClasses)) {
                        array_push($final_teachers, $available_teacher);
                    }
                } else {
                    //************ If teacher has Classes In classes Table ***********
                    $counter1 = 0;
                    foreach ($teacherClasses as $class) {

                        $classStartTime = Carbon::parse($class->start_time)->format('G:i');
                        $classEndTime = Carbon::parse($class->end_time)->format('G:i');

                        if (($start_time >= $classStartTime) && ($start_time <= $classEndTime) || ($end_time >= $classStartTime) && ($end_time <= $classEndTime)) {
                            //    echo 'condition'; 
                        } else {
                            $counter1++;

                            if ($counter1 >= count($teacherClasses)) {
                                $flag++;
                                // echo $flag;
                                if ($flag == count($requestedClasses)) {
                                    array_push($final_teachers, $class->teacher_id);
                                }
                            }
                        }
                    }
                }
            }
        }


        $selectedTeachers = User::whereIn('id', $final_teachers)->get();
        return response()->json([
            'success' => true,
            'filtered_teacher' => $selectedTeachers,
        ]);
    }

    public function account_setting(Request $request)
    {
       

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $user = User::find($token_user->id);
        $user->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Account setting updated Successfully!',
            'user' => $user,
        ]);
    }
    public function student_get_profile(Request $request)
    {
        
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $user = User::find($token_user->id);
      
        return response()->json([
            'success' => true,
            'message' => 'student profile',
            'profile' => $user,
        ]);
    }
}
