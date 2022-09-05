<?php

namespace App\Http\Controllers\Panel;

use App\Events\AcceptTeacherEvent;
use App\Events\RejectTeacherEvent;
use App\Events\UpdateAvatarEvent;
use App\Events\UpdateCoverEvent;
use App\Events\UserProfileEvent;
use App\Events\UserRegisterEvent;
use App\Http\Controllers\Controller;
use App\Jobs\AcceptTeacherJob;
use App\Jobs\RejectCourseJob;
use App\Jobs\RejectTeacherJob;
use App\Jobs\UpdateAvatarJob;
use App\Jobs\UpdateCoverJob;
use App\Jobs\UserProfileJob;
use App\Jobs\UserRegisterJob;
use App\Models\Category;
use App\Models\Newsletter;
use App\Models\Role;
use App\Models\UserMeta;
use App\Models\UserOccupation;
use App\Models\UserZoomApi;

use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Feedback;

use App\Models\UserFeedback;
use App\Models\UserPrefrence;

use App\User;
use App\TeacherAvailability;
use App\TeacherSubject;
use App\TeacherProgram;
use App\SpokenLanguage;
use App\TeacherInterviewRequest;
use App\TeachingSpecification;
use App\TeachingQualification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;
use \App\Mail\SendMailInvite;
use Illuminate\Support\Facades\Mail;
use JWTAuth;
use stdClass;

class UserController extends Controller
{



    public function change_avatar(Request $request)
    {
        $rules = [

            'avatar' => 'required',
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

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($request->has('avatar')) {

            $imageName = $request->avatar;
            $user = User::find($token_user->id);
            $user->avatar = $imageName;
            $user->update();


            $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')
                ->where('id', $token_user->id)
                ->first();

            $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);

            //Email and notifiaction
            // event(new UpdateAvatarEvent($user->id, $user, "Avatar updated successfully!"));
            // dispatch(new UpdateAvatarJob($user->id, $user, "Avatar updated successfully!"));

            return response()->json([
                'status' => true,
                'message' => 'avatar changes successfully',
                'avatar' => $imageName,
                'token' => $token


            ]);


            //************* Resource files ends **********\\
        } else {

            return response()->json([
                'status' => true,
                'message' => 'file not found',


            ]);
        }
    }


    public function change_cover(Request $request)
    {
        $rules = [

            'cover_img' => 'required',
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

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($request->has('cover_img')) {

            $imageName = $request->cover_img;

            $user = User::find($token_user->id);
            $user->cover_img = $imageName;
            $user->update();


            //Email and notifiaction
            event(new UpdateCoverEvent($user->id, $user, "Profile Cover updated successfully!"));
            dispatch(new UpdateCoverJob($user->id, $user, "Profile Cover updated successfully!"));

            return response()->json([
                'status' => true,
                'message' => 'cover image changes successfully',
                'cover_img' => $imageName,


            ]);


            //************* Resource files ends **********\\
        } else {

            return response()->json([
                'status' => true,
                'message' => 'file not found',


            ]);
        }
    }


    public function invite_friends(Request $request)
    {
        $rules = [
            'email' => 'required',
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
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        $user = User::find($token_user->id);


        // $inv=User::where('email',$request->email)->first();
        $inv = null;
        if ($inv == null) {


            $details  = [
                'title' => 'Invitation to join MEtutors',
                'link' => 'https://frontend.eshapsough.com/',
                'message' => $user->first_name . " " . $user->last_name . ' has invited you yo join MEtutors',

                'ignoremessage' => "If you don't want to join, please ignore it.",
            ];
            \Mail::to($request->email)->send(new SendMailInvite($details));

            return response()->json([

                'status' => 'true',
                'message' => 'invite sent successfully ',
            ]);
        } else {

            return response()->json([

                'status' => 'false',
                'message' => 'Unauthorized',
            ], 401);
        }
    }
    public function teacher_reject(Request $request, $id)
    {

        $rules = [
            'admin_comments' => 'required',
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


        $interview_req = TeacherInterviewRequest::with('user')->find($id);

        if ($interview_req != null) {


            $interview_req->status = 'rejected';
            $interview_req->admin_comments = $request->admin_comments;
            $interview_req->update();

            $user = User::find($interview_req->user_id);
            if ($user != null) {

                $user->status = 'rejected';
                $user->admin_approval = 'rejected';
                $user->update();

                $admin = User::where('role_name', 'admin')->first();
                $teacher_message = "You have been Rejected!";
                $admin_message = "Teacher Rejected Successfully!";


                //*********** Sending Email to admin  ************\\
                $user_email = $admin->email;
                $custom_message = "Teacher Rejected Successfully!";
                $to_email = $user_email;

                $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'interview' => $interview_req, 'user' => $admin);

                Mail::send('email.reject_teacher', $data, function ($message) use ($to_email) {

                    $message->to($to_email)->subject('Teacher Hiring Application Status.');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
                // //******** Email ends **********//

                //*********** Sending Email to teacher  ************\\
                $user_email = $user->email;
                $custom_message = "You have been Rejected!!";
                $to_email = $user_email;

                $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'interview' => $interview_req, 'user' => $user);

                Mail::send('email.reject_teacher', $data, function ($message) use ($to_email) {

                    $message->to($to_email)->subject('MEtutors Hiring Application Status.');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
                // //******** Email ends **********//

                // Emails and notifications
                // event(new RejectTeacherEvent($admin->id, $admin, $admin_message, $interview_req));
                // event(new RejectTeacherEvent($user->id, $user, $teacher_message, $interview_req));
                // dispatch(new RejectTeacherJob($admin->id, $admin, $admin_message, $interview_req));
                // dispatch(new RejectTeacherJob($user->id, $user, $teacher_message, $interview_req));

                return response()->json([
                    'status' => 'true',
                    'message' =>  'Tutor is rejected successfully'
                ]);
            }
        } else {

            return response()->json([

                'status' => 'false',
                'message' =>  'request does not exist'
            ], 204);
        }
    }
    public function teacher_approve(Request $request, $id)
    {
        $rules = [

            'subjects' => 'required',
            'admin_comments' => 'required',
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


        $interview_req = TeacherInterviewRequest::with('user')->find($id);

        if ($interview_req != null) {
            $teacher_subjects = json_decode(json_encode($request->subjects));
            foreach ($teacher_subjects as $subj) {
                $sub = TeacherSubject::find($subj->id);
                if ($sub != null) {
                    $sub->hourly_price = $subj->hourly_price;
                    $sub->update();
                }
            }
            $interview_req->status = 'approved';
            $interview_req->admin_comments = $request->admin_comments;
            $interview_req->update();

            $user = User::find($interview_req->user_id);
            if ($user != null) {

                $user->status = 'active';
                $user->admin_approval = 'approved';
                $user->update();

                $admin = User::where('role_name', 'admin')->first();
                $teacher_message = "You have been Approved for Metutors!";
                $admin_message = "Teacher Approved Successfully!";


                //*********** Sending Email to teacher  ************\\
                $user_email = $user->email;
                $custom_message = "You have been Approved for Metutors!";
                $to_email = $user_email;

                $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'interview' => $interview_req, 'user' => $user);

                Mail::send('email.accept_teacher', $data, function ($message) use ($to_email) {

                    $message->to($to_email)->subject('Your Application on MEtutors is Approved');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
                // //******** Email ends **********//

                //*********** Sending Email to admin  ************\\
                $user_email = $admin->email;
                $custom_message = "Teacher Approved Successfully!";
                $to_email = $user_email;

                $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'interview' => $interview_req, 'user' => $admin);

                Mail::send('email.accept_teacher', $data, function ($message) use ($to_email) {

                    $message->to($to_email)->subject('New Teacher Approved on MEtutors');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
                // //******** Email ends **********//


                // // Emails and notifications
                // event(new AcceptTeacherEvent($admin->id, $admin, $admin_message, $interview_req));
                // event(new AcceptTeacherEvent($user->id, $user, $teacher_message, $interview_req));
                // dispatch(new AcceptTeacherJob($admin->id, $admin, $admin_message, $interview_req));
                // dispatch(new AcceptTeacherJob($user->id, $user, $teacher_message, $interview_req));

                return response()->json([

                    'status' => 'true',
                    'message' =>  'Tutor is approved successfully'
                ]);
            }
        } else {

            return response()->json([

                'status' => 'false',
                'message' =>  'request does not exist'
            ], 204);
        }
    }
    public function add_availability(Request $request)
    {

        $rules = [
            'day' => 'required',
            'time_from' => 'required',
            'time_to' => 'required',
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
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        $avail = TeacherAvailability::where('user_id', $token_user->id)->where('day', $request->day)->where('time_from', $request->time_from)->where('time_to', $request->time_to)->first();

        if ($avail == null) {

            $teacher_avail = new TeacherAvailability();
            $teacher_avail->user_id = $token_user->id;
            $teacher_avail->day = $request->day;
            $teacher_avail->time_from = $request->time_from;
            $teacher_avail->time_to = $request->time_to;
            $teacher_avail->save();


            $teacher_availability = TeacherAvailability::where('user_id', $token_user->id)->get();

            return response()->json([

                'status' => 'true',
                'message' => 'Data is added successfully',
                'teacher_availability' => $teacher_availability,

            ]);
        } else {

            return response()->json([

                'status' => 'false',
                'message' => 'The same data added already',

            ], 400);
        }


        $lings = TeacherAvailability::where('user_id', $token_user->id)->get();

        return response()->json([

            'status' => 'true',
            'message' => 'language added successfully',
            'language' => $lings,
        ]);
    }
    public function remove_availability(Request $request, $id)
    {



        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        $avail = TeacherAvailability::find($id);

        if ($avail != null) {

            $avail->delete();

            $teacher_availability = TeacherAvailability::where('user_id', $token_user->id)->get();
            return response()->json([

                'status' => 'true',
                'message' => 'Data is deleted successfully',
                'teacher_availability' => $teacher_availability,

            ]);
        } else {

            return response()->json([

                'status' => 'false',
                'message' => 'Not found',

            ], 204);
        }


        $lings = TeacherAvailability::where('user_id', $token_user->id)->get();

        return response()->json([

            'status' => 'true',
            'message' => 'language added successfully',
            'language' => $lings,
        ]);
    }
    public function add_subjects(Request $request)
    {

        $rules = [

            'field_id' => 'required',
            'subject_id' => 'required|json',

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
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        $sub = TeacherSubject::where('user_id', $token_user->id)->where('field_id', $request->field_id)->first();

        if ($sub == null) {

            $subjectss = json_decode($request->subject_id);

            foreach ($subjectss as $subj) {

                $teacher_sub = new TeacherSubject();
                $teacher_sub->user_id = $token_user->id;
                $teacher_sub->field_id = $request->field_id;
                $teacher_sub->subject_id = $subj;
                $teacher_sub->save();
            }

            $subjects = TeacherSubject::where('user_id', $token_user->id)->get();

            return response()->json([

                'status' => 'true',
                'message' => 'Data is added successfully',
                'subjects' => $subjects,

            ]);
        } else {

            return response()->json([

                'status' => 'false',
                'message' => 'This field is already added',

            ], 400);
        }


        $lings = TeacherSubject::where('user_id', $token_user->id)->get();

        return response()->json([

            'status' => 'true',
            'message' => 'language added successfully',
            'language' => $lings,
        ]);
    }
    public function remove_subjects(Request $request)
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
        }
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        $sub = TeacherSubject::where('user_id', $token_user->id)->where('field_id', $request->field_id)->first();

        if ($sub != null) {

            $subs = TeacherSubject::where('user_id', $token_user->id)->where('field_id', $request->field_id)->delete();

            $subjects = TeacherSubject::where('user_id', $token_user->id)->get();

            return response()->json([

                'status' => 'true',
                'message' => 'removed successfully',
                'subjects' => $subjects,

            ]);
        } else {

            return response()->json([

                'status' => 'false',
                'message' => 'This field of study not found',

            ], 404);
        }


        $lings = TeacherSubject::where('user_id', $token_user->id)->get();

        return response()->json([

            'status' => 'true',
            'message' => 'language added successfully',
            'language' => $lings,
        ]);
    }
    public function add_language(Request $request)
    {

        $rules = [

            'language' => 'required',
            'level' => 'required',

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
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $ling = SpokenLanguage::where('user_id', $token_user->id)->where('language', $request->language)->first();

        if ($ling == null) {
            $lang = new SpokenLanguage();
            $lang->user_id = $token_user->id;
            $lang->language = $request->language;
            $lang->level = $request->level;
            $lang->save();
        } else {
            $ling->user_id = $token_user->id;
            $ling->language = $request->language;
            $ling->level = $request->level;
            $ling->update();
        }


        $lings = SpokenLanguage::where('user_id', $token_user->id)->get();

        return response()->json([

            'status' => 'true',
            'message' => 'language added successfully',
            'language' => $lings,
        ]);
    }
    public function remove_language(Request $request)
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

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        $ling = SpokenLanguage::where('user_id', $token_user->id)->where('id', $request->id)->first();

        if ($ling != null) {
            $lang = SpokenLanguage::find($request->id);
            $lang->delete();
        } else {

            return response()->json([

                'status' => 'false',
                'message' => 'language not found',

            ], 404);
        }


        $lings = SpokenLanguage::where('user_id', $token_user->id)->get();

        return response()->json([

            'status' => 'true',
            'message' => 'language removed successfully',
            'language' => $lings,
        ]);
    }
    public function teacher_complete_account(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        $rules = [

            'step' => 'required|min:1|max:4',

        ];
        if ($request->step == 1) {

            $rules['gender'] = 'required|string';
            $rules['nationality'] = 'required';
            $rules['date_of_birth'] = 'required|string';
            $rules['postal_code'] = 'required';
            $rules['country'] = 'required';
            $rules['city'] = 'required';
            $rules['address'] = 'required|string';
            $rules['bio'] = 'required|string';
        }
        if ($request->step == 2) {

            $rules['avatar'] = 'required';
        }
        if ($request->step == 3) {


            $rules['name_of_university'] = 'required|string';
            $rules['degree_level'] = 'required|string';
            $rules['degree_field'] = 'required|string';
            $rules['spoken_languages'] = 'required';
            $rules['computer_skills'] = 'required|string';
            $rules['teaching_experience'] = 'required|string';
            $rules['teaching_experience_online'] = 'required|string';
            $rules['video'] = 'required';
            // $rules['current_title'] = 'required|string';

        }


        if ($request->step == 4) {


            $rules['type_of_tutoring'] = 'required|string';

            $rules['availability_start_date'] = 'required|string';
            $rules['availability_end_date'] = 'required|string';


            $rules['availability'] = 'required';
        }


        if ($request->step == 5) {

            $rules['subjects'] = 'required';
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


        if ($request->step == 1) {

            $user = User::find($token_user->id);

            if ($user->gender == null && $user->address == null && $user->country == null && $user->city == null) {

                $user->profile_completed_step = 1;
            }

            $user->gender = $request->gender;
            $user->nationality = $request->nationality;
            $user->date_of_birth = $request->date_of_birth;
            $user->address = $request->address;
            $user->bio = $request->bio;
            $user->postal_code = $request->postal_code;
            $user->country = $request->country;
            $user->city = $request->city;


            if ($request->middle_name) {

                $user->middle_name = $request->middle_name;
            }
            if ($request->address2) {

                $user->address2 = $request->address2;
            }

            $user->update();



            $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')->where('id', $token_user->id)->first();

            $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);


            $token_1 = JWTAuth::getToken();
            $token_user = JWTAuth::toUser($token_1);

            $user = \App\User::with('country', 'userMetas', 'teacherSpecifications', 'teacherQualifications', 'teacherAvailability', 'spokenLanguages', 'spokenLanguages.language', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject.country', 'teacher_interview_request')
                ->find($token_user->id);

            $prefrences = UserPrefrence::select('id', 'user_id', 'role_name', 'preferred_gender', 'teacher_language', 'efficiency')
                ->with('spoken_language')
                ->where('user_id', $token_user->id)
                ->get();

            $spoken_languages = [];
            $final_prefrences = new stdClass();
            if (count($prefrences) > 0) {
                $final_prefrences->preferred_gender = $prefrences[0]->preferred_gender;
                foreach ($prefrences as $key => $prefrence) {
                    $language = new stdClass();
                    $object = new stdClass();
                    $language->id = $prefrence->spoken_language->id;
                    $language->name = $prefrence->spoken_language->name;
                    $object->efficiency =   $prefrence->efficiency;
                    $object->language =  $language;
                    array_push($spoken_languages, $object);
                }

                $final_prefrences->spoken_languages = $spoken_languages;
                $user->preferences = $final_prefrences;
            }



            return response()->json([

                'status' => true,
                'message' => 'data updated succesfully',
                'token' => $token,
                'user' => $user,
            ]);
        }
        if ($request->step == 2) {


            $user = User::find($token_user->id);


            if ($user->avatar == null && $user->cover_img == null) {

                $user->profile_completed_step = 2;
            }

            if ($request->has('avatar')) {

                $imageName = $request->avatar;
                $user->avatar = $imageName;
            }
            if ($request->has('cover_img')) {
                $imageName = $request->cover_img;
                $user->cover_img = $imageName;
            }

            //  return $user;
            $user->update();




            $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')->where('id', $token_user->id)->first();

            $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);


            $token_1 = JWTAuth::getToken();
            $token_user = JWTAuth::toUser($token_1);

            $user = \App\User::with('country', 'userMetas', 'teacherSpecifications', 'teacherQualifications', 'teacherAvailability', 'spokenLanguages', 'spokenLanguages.language', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject.country', 'teacher_interview_request')
                ->find($token_user->id);

            $prefrences = UserPrefrence::select('id', 'user_id', 'role_name', 'preferred_gender', 'teacher_language', 'efficiency')
                ->with('spoken_language')
                ->where('user_id', $token_user->id)
                ->get();

            $spoken_languages = [];
            $final_prefrences = new stdClass();
            if (count($prefrences) > 0) {
                $final_prefrences->preferred_gender = $prefrences[0]->preferred_gender;
                foreach ($prefrences as $key => $prefrence) {
                    $language = new stdClass();
                    $object = new stdClass();
                    $language->id = $prefrence->spoken_language->id;
                    $language->name = $prefrence->spoken_language->name;
                    $object->efficiency =   $prefrence->efficiency;
                    $object->language =  $language;
                    array_push($spoken_languages, $object);
                }

                $final_prefrences->spoken_languages = $spoken_languages;
                $user->preferences = $final_prefrences;
            }

            return response()->json([

                'status' => true,
                'message' => 'data updated succesfully',
                'token' => $token,
                'user' => $user,
            ]);
        }
        if ($request->step == 3) {


            $avail = SpokenLanguage::where('user_id', $token_user->id)->delete();

            $languages = json_decode($request->spoken_languages);

            foreach ($languages as $language) {


                $lang = new SpokenLanguage();
                $lang->user_id = $token_user->id;
                $lang->language = $language->language_id;
                $lang->level = $language->level;
                $lang->save();
            }



            $ling = SpokenLanguage::where('user_id', $token_user->id)->first();
            if ($ling == null) {

                return response()->json([

                    'status' => false,
                    'message' => 'please add language first',
                ], 400);
            }

            $teaching_quali = TeachingQualification::where('user_id', $token_user->id)->first();

            if ($teaching_quali == null) {
                $teaching_quali = new TeachingQualification();
                $teaching_quali->user_id = $token_user->id;
                $teaching_quali->name_of_university = $request->name_of_university;
                $teaching_quali->degree_level = $request->degree_level;
                $teaching_quali->degree_field = $request->degree_field;

                $teaching_quali->computer_skills = $request->computer_skills;
                $teaching_quali->teaching_experience = $request->teaching_experience;
                $teaching_quali->teaching_experience_online = $request->teaching_experience_online;
                $teaching_quali->current_employer = $request->current_employer ?? Null;
                $teaching_quali->current_title = $request->current_title ?? Null;

                if ($request->has('video')) {

                    $imageName = $request->video;

                    $teaching_quali->video = $imageName;
                }
                $teaching_quali->save();




                $user = User::find($token_user->id);
                $user->profile_completed_step = 3;
                $user->update();




                $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')->where('id', $token_user->id)->first();

                $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);

                $token_1 = JWTAuth::getToken();
                $token_user = JWTAuth::toUser($token_1);

                $user = \App\User::with('country', 'userMetas', 'teacherSpecifications', 'teacherQualifications', 'teacherAvailability', 'spokenLanguages', 'spokenLanguages.language', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject.country', 'teacher_interview_request')
                    ->find($token_user->id);

                $prefrences = UserPrefrence::select('id', 'user_id', 'role_name', 'preferred_gender', 'teacher_language', 'efficiency')
                    ->with('spoken_language')
                    ->where('user_id', $token_user->id)
                    ->get();

                $spoken_languages = [];
                $final_prefrences = new stdClass();
                if (count($prefrences) > 0) {
                    $final_prefrences->preferred_gender = $prefrences[0]->preferred_gender;
                    foreach ($prefrences as $key => $prefrence) {
                        $language = new stdClass();
                        $object = new stdClass();
                        $language->id = $prefrence->spoken_language->id;
                        $language->name = $prefrence->spoken_language->name;
                        $object->efficiency =   $prefrence->efficiency;
                        $object->language =  $language;
                        array_push($spoken_languages, $object);
                    }

                    $final_prefrences->spoken_languages = $spoken_languages;
                    $user->preferences = $final_prefrences;
                }

                return response()->json([

                    'status' => true,
                    'message' => 'data updated succesfully',
                    'token' => $token,
                    'user' => $user,
                ]);
            } else {




                $teaching_quali->user_id = $token_user->id;
                $teaching_quali->name_of_university = $request->name_of_university;
                $teaching_quali->degree_level = $request->degree_level;
                $teaching_quali->degree_field = $request->degree_field;

                $teaching_quali->computer_skills = $request->computer_skills;
                $teaching_quali->teaching_experience = $request->teaching_experience;
                $teaching_quali->current_employer = $request->current_employer ?? Null;
                $teaching_quali->current_title = $request->current_title ?? Null;
                if ($request->has('video')) {

                    $imageName = $request->video;
                    $teaching_quali->video = $imageName;
                }
                $teaching_quali->update();




                $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')->where('id', $token_user->id)->first();


                $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);

                $token_1 = JWTAuth::getToken();
                $token_user = JWTAuth::toUser($token_1);

                $user = \App\User::with('country', 'userMetas', 'teacherSpecifications', 'teacherQualifications', 'teacherAvailability', 'spokenLanguages', 'spokenLanguages.language', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject.country', 'teacher_interview_request')
                    ->find($token_user->id);

                $prefrences = UserPrefrence::select('id', 'user_id', 'role_name', 'preferred_gender', 'teacher_language', 'efficiency')
                    ->with('spoken_language')
                    ->where('user_id', $token_user->id)
                    ->get();

                $spoken_languages = [];
                $final_prefrences = new stdClass();
                if (count($prefrences) > 0) {
                    $final_prefrences->preferred_gender = $prefrences[0]->preferred_gender;
                    foreach ($prefrences as $key => $prefrence) {
                        $language = new stdClass();
                        $object = new stdClass();
                        $language->id = $prefrence->spoken_language->id;
                        $language->name = $prefrence->spoken_language->name;
                        $object->efficiency =   $prefrence->efficiency;
                        $object->language =  $language;
                        array_push($spoken_languages, $object);
                    }

                    $final_prefrences->spoken_languages = $spoken_languages;
                    $user->preferences = $final_prefrences;
                }

                return response()->json([

                    'status' => true,
                    'message' => 'data updated succesfully',
                    'token' => $token,
                    'user' => $user,
                ]);
            }
        }

        if ($request->step == 4) {


            $teaching_specs = TeachingSpecification::where('user_id', $token_user->id)->first();

            if ($teaching_specs == null) {
                $teaching_spec = new TeachingSpecification();
                $teaching_spec->user_id = $token_user->id;

                $teaching_spec->type_of_tutoring = $request->type_of_tutoring;

                $teaching_spec->availability_start_date = $request->availability_start_date;
                $teaching_spec->availability_end_date = $request->availability_end_date;
                $teaching_spec->save();


                $availabilities = json_decode(json_encode($request->availability));

                $avail = TeacherAvailability::where('user_id', $token_user->id)->delete();

                foreach ($availabilities as $availability) {

                    // print_r($availability->time_slots);die;

                    $availability_slots = $availability->time_slots;

                   

                    foreach ($availability_slots as $slot) {

                        $teacher_sub = new TeacherAvailability();
                        $teacher_sub->user_id = $token_user->id;
                        $teacher_sub->day = $availability->day;
                        $teacher_sub->time_from = $slot->start_time;
                        $teacher_sub->time_to = $slot->end_time;
                        $teacher_sub->save();
                    }
                }


                $user = User::find($token_user->id);
                $user->profile_completed_step = 4;
                $user->update();

                $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')->where('id', $token_user->id)->first();

                $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);

                $token_1 = JWTAuth::getToken();
                $token_user = JWTAuth::toUser($token_1);

                $user = \App\User::with('country', 'userMetas', 'teacherSpecifications', 'teacherQualifications', 'teacherAvailability', 'spokenLanguages', 'spokenLanguages.language', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject.country', 'teacher_interview_request')
                    ->find($token_user->id);

                $prefrences = UserPrefrence::select('id', 'user_id', 'role_name', 'preferred_gender', 'teacher_language', 'efficiency')
                    ->with('spoken_language')
                    ->where('user_id', $token_user->id)
                    ->get();

                $spoken_languages = [];
                $final_prefrences = new stdClass();
                if (count($prefrences) > 0) {
                    $final_prefrences->preferred_gender = $prefrences[0]->preferred_gender;
                    foreach ($prefrences as $key => $prefrence) {
                        $language = new stdClass();
                        $object = new stdClass();
                        $language->id = $prefrence->spoken_language->id;
                        $language->name = $prefrence->spoken_language->name;
                        $object->efficiency =   $prefrence->efficiency;
                        $object->language =  $language;
                        array_push($spoken_languages, $object);
                    }

                    $final_prefrences->spoken_languages = $spoken_languages;
                    $user->preferences = $final_prefrences;
                }


                return response()->json([

                    'status' => true,
                    'message' => 'data updated succesfully',
                    'token' => $token,
                    'user' => $user,
                ]);
            } else {



                $teaching_specs->type_of_tutoring = $request->type_of_tutoring;

                $teaching_specs->availability_start_date = $request->availability_start_date;
                $teaching_specs->availability_end_date = $request->availability_end_date;


                $teaching_specs->update();




                $availabilities = json_decode(json_encode($request->availability));

                $avail = TeacherAvailability::where('user_id', $token_user->id)->delete();


                foreach ($availabilities as $availability) {

                    // print_r($availability->time_slots);die;

                    $availability_slots = $availability->time_slots;



                    foreach ($availability_slots as $slot) {

                        $teacher_sub = new TeacherAvailability();
                        $teacher_sub->user_id = $token_user->id;
                        $teacher_sub->day = $availability->day;
                        $teacher_sub->time_from = $slot->start_time;
                        $teacher_sub->time_to = $slot->end_time;
                        $teacher_sub->save();
                    }
                }


                $user = User::find($token_user->id);
                $user->profile_completed_step = 4;
                $user->update();



                $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')->where('id', $token_user->id)->first();

                $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);

                $token_1 = JWTAuth::getToken();
                $token_user = JWTAuth::toUser($token_1);

                $user = \App\User::with('country', 'userMetas', 'teacherSpecifications', 'teacherQualifications', 'teacherAvailability', 'spokenLanguages', 'spokenLanguages.language', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject.country', 'teacher_interview_request')
                    ->find($token_user->id);

                $prefrences = UserPrefrence::select('id', 'user_id', 'role_name', 'preferred_gender', 'teacher_language', 'efficiency')
                    ->with('spoken_language')
                    ->where('user_id', $token_user->id)
                    ->get();

                $spoken_languages = [];
                $final_prefrences = new stdClass();
                if (count($prefrences) > 0) {
                    $final_prefrences->preferred_gender = $prefrences[0]->preferred_gender;
                    foreach ($prefrences as $key => $prefrence) {
                        $language = new stdClass();
                        $object = new stdClass();
                        $language->id = $prefrence->spoken_language->id;
                        $language->name = $prefrence->spoken_language->name;
                        $object->efficiency =   $prefrence->efficiency;
                        $object->language =  $language;
                        array_push($spoken_languages, $object);
                    }

                    $final_prefrences->spoken_languages = $spoken_languages;
                    $user->preferences = $final_prefrences;
                }

                return response()->json([

                    'status' => true,
                    'message' => 'data updated succesfully',
                    'token' => $token,
                    'user' => $user,
                ]);
            }
        }
        if ($request->step == 5) {


            $teacher_subjects = json_decode(json_encode($request->subjects));



            foreach ($teacher_subjects as $subj) {



                $sub = TeacherSubject::where('user_id', $token_user->id)->where('program_id', $subj->program_id)->where('field_id', $subj->field_id)->where('subject_id', $subj->subject_id)->first();


                if (isset($subj->program_id) && $subj->program_id == 3) {

                    $sub = TeacherSubject::where('user_id', $token_user->id)->where('program_id', $subj->program_id)->where('country_id', $subj->country_id)->where('grade', $subj->grade)->where('field_id', $subj->field_id)->where('subject_id', $subj->subject_id)->first();
                }


                if ($sub == null) {

                    $teacher_sub = new TeacherSubject();
                    $teacher_sub->user_id = $token_user->id;
                    $teacher_sub->program_id = $subj->program_id;
                    $teacher_sub->field_id = $subj->field_id;
                    if ($subj->program_id == 3) {
                        $teacher_sub->country_id = $subj->country_id;
                        $teacher_sub->grade = $subj->grade;
                    }
                    $teacher_sub->subject_id = $subj->subject_id;
                    $teacher_sub->hourly_price = $subj->hourly_price;
                    $teacher_sub->save();
                } else {

                    $sub->user_id = $token_user->id;
                    $sub->program_id = $subj->program_id;
                    $sub->field_id = $subj->field_id;
                    if ($subj->program_id == 3) {
                        $sub->country_id = $subj->country_id;
                        $sub->grade = $subj->grade;
                    }
                    $sub->subject_id = $subj->subject_id;
                    $sub->hourly_price = $subj->hourly_price;
                    $sub->update();
                }
            }



            $user = User::find($token_user->id);
            $user->profile_completed_step = 5;
            $user->update();



            $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')->where('id', $token_user->id)->first();

            $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);

            $token_1 = JWTAuth::getToken();
            $token_user = JWTAuth::toUser($token_1);

            $user = \App\User::with('country', 'userMetas', 'teacherSpecifications', 'teacherQualifications', 'teacherAvailability', 'spokenLanguages', 'spokenLanguages.language', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject.country', 'teacher_interview_request')
                ->find($token_user->id);

            $prefrences = UserPrefrence::select('id', 'user_id', 'role_name', 'preferred_gender', 'teacher_language', 'efficiency')
                ->with('spoken_language')
                ->where('user_id', $token_user->id)
                ->get();

            $spoken_languages = [];
            $final_prefrences = new stdClass();
            if (count($prefrences) > 0) {
                $final_prefrences->preferred_gender = $prefrences[0]->preferred_gender;
                foreach ($prefrences as $key => $prefrence) {
                    $language = new stdClass();
                    $object = new stdClass();
                    $language->id = $prefrence->spoken_language->id;
                    $language->name = $prefrence->spoken_language->name;
                    $object->efficiency =   $prefrence->efficiency;
                    $object->language =  $language;
                    array_push($spoken_languages, $object);
                }

                $final_prefrences->spoken_languages = $spoken_languages;
                $user->preferences = $final_prefrences;
            }

            return response()->json([

                'status' => true,
                'message' => 'data updated succesfully',
                'token' => $token,
                'user' => $user,
            ]);
        }
    }



    public function upload_documents2(Request $request)
    {

        // print_r($request->all());die;

        $rules = [

            'email' => 'required|email|min:3|max:100',
            'documents' => 'required',
            // 'documents.*' => 'required|file|mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,doc,docx,pdf,tif,tiff'


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
        if (!$request->hasFile('documents')) {
            return response()->json(['upload_file_not_found']);
        }

        // $request->documents

        $user = User::where('email', 'mabdulrehman14713@gmail.com')->first();

        if ($user != null) {




            $imgs = array();
            $files = $request->file('documents');
            if ($request->hasfile('documents')) {
                // foreach($files as $file)
                // {
                $fileName = time() . rand(0, 1000) . pathinfo($request->documents->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $fileName . '.' . $request->documents->getClientOriginalExtension();
                $request->documents->move(public_path('uploads/teacher_documents'), $fileName);
                $imgs[] = $fileName;

                $userMeta = new UserMeta();
                $userMeta->user_id = $user->id;
                $userMeta->name = 'documents';
                $userMeta->value = $fileName;
                $userMeta->save();
                // }

                return response()->json([

                    'status' => 'true',
                    'message' =>  'documents uploaded successfully'

                ]);
            }
        } else {

            return response()->json([

                'status' => 'false',
                'message' =>  'user does not exist'
            ], 404);
        }
    }

    public function upload_documents(Request $request)
    {
        $rules = [
            'email' => 'required|email|min:3|max:100',
            'documents' => 'required',
            // 'documents.*' => 'required||file|mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,doc,docx,pdf,tif,tiff|max:5120'


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
        if (!$request->hasFile('documents')) {
            return response()->json(['upload_file_not_found'], 400);
        }

        // $request->documents

        $user = User::where('email', $request->email)->first();

        if ($user != null) {

            // $imgs=array();
            // $files= $request->file('documents');
            // if($files){
            //     foreach($files as $file){
            //         $fileName = date('YmdHis').random_int(0,1000).'.'.$file->getClientOriginalExtension();
            //         $file->move(public_path('uploads/teacher_documents'), $fileName);
            //         $imgs[]=$fileName;
            //     }

            // }
            // $driver->license_images=implode("|",$imgs);

            $imgs = array();
            $files = $request->file('documents');
            if ($request->hasFile('documents')) {
                foreach ($files as $file) {
                    $fileName = date('YmdHis') . random_int(0, 1000) . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/teacher_documents'), $fileName);
                    $imgs[] = $fileName;

                    $userMeta = new UserMeta;
                    $userMeta->user_id = $user->id;
                    $userMeta->name = 'documents';
                    $userMeta->value = $fileName;
                    $userMeta->save();
                }

                $admin = User::where('role_name', 'admin')->first();
                // $admin->teacher = $user;
                // $admin_data = $admin;

                $admin_data = new stdClass();
                $admin_data = $admin;
                $admin_data->teacher = $user;
                // return $admin_data;


                //********* Sending Email to admin **********
                $user_email = $admin->email;
                $custom_message = "A New User Registerd Successfully";
                $to_email = $user_email;

                $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'user' => $admin_data);

                Mail::send('email.registeration', $data, function ($message) use ($to_email) {

                    $message->to($to_email)->subject('New teacher registration');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });
                //********* Sending Email ends **********

                //********* Sending Email to teacher **********
                $user_email = $user->email;
                $custom_message = "Teacher Registerd Successfully";
                $to_email = $user_email;

                $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'user' => $user);

                Mail::send('email.registeration', $data, function ($message) use ($to_email) {

                    $message->to($to_email)->subject('Welcome to MEtutors');
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                });



                //********* Sending Email ends **********


                // // Emails and notifications for registeration
                // event(new UserRegisterEvent($user->id, $user, "Teacher Registerd Successfully"));
                // event(new UserRegisterEvent($admin->id, $admin_data, "A New User Registerd Successfully"));
                // dispatch(new UserRegisterJob($user->id, $user, "teacher Registerd Successfully"));
                // dispatch(new UserRegisterJob($admin->id, $admin_data, "A New User Registerd Successfully"));

                return response()->json([
                    'status' => 'true',
                    'message' =>  'Documents uploaded successfully! Please login to continue'

                ]);
            }
        } else {

            return response()->json([

                'status' => 'false',
                'message' =>  'user does not exist'
            ], 404);
        }
    }
    public function setting($step = 1)
    {
        $user = auth()->user();
        $categories = Category::where('parent_id', null)
            ->with('subCategories')
            ->get();
        $userMetas = $user->userMetas;

        $occupations = $user->occupations->pluck('category_id')->toArray();


        $userLanguages = getGeneralSettings('user_languages');
        if (!empty($userLanguages) and is_array($userLanguages)) {
            $userLanguages = getLanguages($userLanguages);
        }

        $data = [
            'pageTitle' => trans('panel.settings'),
            'user' => $user,
            'categories' => $categories,
            'educations' => $userMetas->where('name', 'education'),
            'experiences' => $userMetas->where('name', 'experience'),
            'occupations' => $occupations,
            'userLanguages' => $userLanguages,
            'currentStep' => $step,
        ];

        return view(getTemplate() . '.panel.setting.index', $data);
    }

    public function teacher_update(Request $request)
    {
        $rules = [

            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'country_code' => 'required',
            'mobile' => 'required|min:5|max:15',
            'email' => 'required|string|email|max:255',

        ];
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        if ($token_user->email != $request->email) {

            $rules['email'] = 'required|string|email|max:255|unique:users';
        }

        if ($request->hasFile('avatar')) {

            $rules['avatar'] = 'required|image|mimes:jpeg,jpg,png|required|max:20000,dimensions:min_width=100,min_height=100';
        }
        if ($request->hasFile('cover_img')) {

            $rules['cover_img'] = 'required|image|mimes:jpeg,jpg,png|required|max:20000,dimensions:min_width=100,min_height=100,dimensions:ratio=3/2';
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

        // return 'hello';
        $id = $token_user->id;

        $user = User::find($id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile = $request->country_code . $request->mobile;

        if ($request->hasFile('avatar')) {
            $imageName = rand(10, 100) . time() . '.' . $request->avatar->getClientOriginalExtension();
            $request->avatar->move(public_path('uploads/images'), $imageName);
            $user->avatar = $imageName;
        }
        if ($request->hasFile('cover_img')) {
            $imageName = rand(10, 100) . time() . '.' . $request->cover_img->getClientOriginalExtension();
            $request->cover_img->move(public_path('uploads/images'), $imageName);
            $user->cover_img = $imageName;
        }

        $user->update();

        $user = User::select('id', 'first_name', 'last_name', 'role_name', 'mobile', 'email',  'verified', 'avatar', 'cover_img')->where('id', $id)->first();

        return response()->json([

            'status' => true,
            'message' => 'Profile updated successfully',
            'user' => $user,

        ]);
    }

    public function teacher_documents(Request $request)
    {
        // return $request->user_id;
        $id = $request->user_id;

        $user = User::find($id);

        $user_meta = UserMeta::where('user_id', $id)->where('name', 'documents')->get();

        $user->documents = $user_meta;


        return response()->json([

            'status' => true,
            'message' => 'user documents',
            'user_documents' => $user_meta,

        ]);
    }
    public function teacher_update1(Request $request)
    {
        $rules = [

            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'country_code' => 'required',
            'mobile' => 'required|min:5|max:15',
            'email' => 'required|string|email|max:255',

        ];
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        if ($token_user->email != $request->email) {

            $rules['email'] = 'required|string|email|max:255|unique:users';
        }

        if ($request->hasFile('avatar')) {

            $rules['avatar'] = 'required|image|mimes:jpeg,jpg,png|required|max:20000,dimensions:min_width=100,min_height=100';
        }
        if ($request->hasFile('cover_img')) {

            $rules['cover_img'] = 'required|image|mimes:jpeg,jpg,png|required|max:20000,dimensions:min_width=100,min_height=100,dimensions:ratio=3/2';
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

        // return 'hello';
        $id = $token_user->id;

        $user = User::find($id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile = $request->country_code . $request->mobile;

        if ($request->hasFile('avatar')) {
            $imageName = rand(10, 100) . time() . '.' . $request->avatar->getClientOriginalExtension();
            $request->avatar->move(public_path('uploads/images'), $imageName);
            $user->avatar = $imageName;
        }
        if ($request->hasFile('cover_img')) {
            $imageName = rand(10, 100) . time() . '.' . $request->cover_img->getClientOriginalExtension();
            $request->cover_img->move(public_path('uploads/images'), $imageName);
            $user->cover_img = $imageName;
        }

        $user->update();

        $user = User::select('id', 'first_name', 'last_name', 'role_name', 'mobile', 'email',  'verified', 'avatar', 'cover_img')->where('id', $id)->first();

        return response()->json([

            'status' => true,
            'message' => 'Profile updated successfully',
            'user' => $user,

        ]);
    }



    public function student_update(Request $request)
    {
        $rules = [

            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'country_code' => 'required',
            'mobile' => 'required|min:5|max:15',
            'email' => 'required|string|email|max:255',

        ];
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        if ($token_user->email != $request->email) {

            $rules['email'] = 'required|string|email|max:255|unique:users';
        }

        if ($request->hasFile('avatar')) {

            $rules['avatar'] = 'required|image|mimes:jpeg,jpg,png|required|max:20000,dimensions:min_width=100,min_height=100';
        }
        if ($request->hasFile('cover_img')) {

            $rules['cover_img'] = 'required|image|mimes:jpeg,jpg,png|required|max:20000,dimensions:min_width=100,min_height=100,dimensions:ratio=3/2';
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

        // return 'hello';
        $id = $token_user->id;

        $user = User::find($id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile = $request->country_code . $request->mobile;

        if ($request->hasFile('avatar')) {
            $imageName = rand(10, 100) . time() . '.' . $request->avatar->getClientOriginalExtension();
            $request->avatar->move(public_path('uploads/images'), $imageName);
            $user->avatar = $imageName;
        }
        if ($request->hasFile('cover_img')) {
            $imageName = rand(10, 100) . time() . '.' . $request->cover_img->getClientOriginalExtension();
            $request->cover_img->move(public_path('uploads/images'), $imageName);
            $user->cover_img = $imageName;
        }

        $user->update();

        $user = User::select('id', 'first_name', 'last_name', 'role_name', 'mobile', 'email',  'verified', 'avatar', 'cover_img')->where('id', $id)->first();

        return response()->json([

            'status' => true,
            'message' => 'Profile updated successfully',
            'user' => $user,

        ]);
    }

    private function handleNewsletter($email, $user_id, $joinNewsletter)
    {
        $check = Newsletter::where('email', $email)->first();

        if ($joinNewsletter) {
            if (empty($check)) {
                Newsletter::create([
                    'user_id' => $user_id,
                    'email' => $email,
                    'created_at' => time()
                ]);
            } else {
                $check->update([
                    'user_id' => $user_id,
                ]);
            }
        } elseif (!empty($check)) {
            $check->delete();
        }
    }

    public function createImage($user, $img)
    {
        $folderPath = "/" . $user->id . '/';

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = uniqid() . '.' . $image_type;

        Storage::disk('public')->put($folderPath . $file, $image_base64);

        return $file;
    }

    public function storeMetas(Request $request)
    {
        $data = $request->all();

        if (!empty($data['name']) and !empty($data['value'])) {

            if (!empty($data['user_id'])) {
                $organization = auth()->user();
                $user = User::where('id', $data['user_id'])
                    ->where('organ_id', $organization->id)
                    ->first();
            } else {
                $user = auth()->user();
            }

            UserMeta::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'value' => $data['value'],
            ]);

            return response()->json([
                'code' => 200
            ], 200);
        }

        return response()->json([], 422);
    }

    public function updateMeta(Request $request, $meta_id)
    {
        $data = $request->all();
        $user = auth()->user();

        if (!empty($data['user_id'])) {
            $checkUser = User::find($data['user_id']);

            if ((!empty($checkUser) and ($data['user_id'] == $user->id) or $checkUser->organ_id == $user->id)) {
                $meta = UserMeta::where('id', $meta_id)
                    ->where('user_id', $data['user_id'])
                    ->where('name', $data['name'])
                    ->first();

                if (!empty($meta)) {
                    $meta->update([
                        'value' => $data['value']
                    ]);

                    return response()->json([
                        'code' => 200
                    ], 200);
                }

                return response()->json([
                    'code' => 403
                ], 200);
            }
        }

        return response()->json([], 422);
    }

    public function deleteMeta(Request $request, $meta_id)
    {
        $data = $request->all();
        $user = auth()->user();

        if (!empty($data['user_id'])) {
            $checkUser = User::find($data['user_id']);

            if (!empty($checkUser) and ($data['user_id'] == $user->id or $checkUser->organ_id == $user->id)) {
                $meta = UserMeta::where('id', $meta_id)
                    ->where('user_id', $data['user_id'])
                    ->first();

                $meta->delete();

                return response()->json([
                    'code' => 200
                ], 200);
            }
        }

        return response()->json([], 422);
    }

    public function manageUsers(Request $request, $user_type)
    {
        $valid_type = ['instructors', 'students'];
        $organization = auth()->user();

        if ($organization->isOrganization() and in_array($user_type, $valid_type)) {
            if ($user_type == 'instructors') {
                $query = $organization->getOrganizationTeachers();
            } else {
                $query = $organization->getOrganizationStudents();
            }

            $activeCount = deepClone($query)->where('status', 'active')->count();
            $verifiedCount = deepClone($query)->where('verified', true)->count();
            $inActiveCount = deepClone($query)->where('status', 'inactive')->count();

            $from = $request->get('from', null);
            $to = $request->get('to', null);
            $name = $request->get('name', null);
            $email = $request->get('email', null);
            $type = request()->get('type', null);

            if (!empty($from) and !empty($to)) {
                $from = strtotime($from);
                $to = strtotime($to);

                $query->whereBetween('created_at', [$from, $to]);
            } else {
                if (!empty($from)) {
                    $from = strtotime($from);

                    $query->where('created_at', '>=', $from);
                }

                if (!empty($to)) {
                    $to = strtotime($to);

                    $query->where('created_at', '<', $to);
                }
            }

            if (!empty($name)) {
                $query->where('full_name', 'like', "%$name%");
            }

            if (!empty($email)) {
                $query->where('email', $email);
            }

            if (!empty($type)) {
                if (in_array($type, ['active', 'inactive'])) {
                    $query->where('status', $type);
                } elseif ($type == 'verified') {
                    $query->where('verified', true);
                }
            }

            $users = $query->orderBy('created_at', 'desc')
                ->paginate(10);

            $data = [
                'pageTitle' => trans('public.' . $user_type),
                'user_type' => $user_type,
                'organization' => $organization,
                'users' => $users,
                'activeCount' => $activeCount,
                'inActiveCount' => $inActiveCount,
                'verifiedCount' => $verifiedCount,
            ];

            return view(getTemplate() . '.panel.manage.' . $user_type, $data);
        }

        abort(404);
    }

    public function createUser($user_type)
    {
        $valid_type = ['instructors', 'students'];
        $organization = auth()->user();
        if ($organization->isOrganization() and in_array($user_type, $valid_type)) {
            $categories = Category::where('parent_id', null)
                ->with('subCategories')
                ->get();

            $userLanguages = getGeneralSettings('user_languages');
            if (!empty($userLanguages) and is_array($userLanguages)) {
                $userLanguages = getLanguages($userLanguages);
            }

            $data = [
                'pageTitle' => trans('public.new') . ' ' . trans('quiz.' . $user_type),
                'new_user' => true,
                'user_type' => $user_type,
                'user' => $organization,
                'categories' => $categories,
                'organization_id' => $organization->id,
                'userLanguages' => $userLanguages,
                'currentStep' => 1,
            ];

            return view(getTemplate() . '.panel.setting.index', $data);
        }

        abort(404);
    }

    public function storeUser(Request $request, $user_type)
    {
        $valid_type = ['instructors', 'students'];
        $organization = auth()->user();

        if ($organization->isOrganization() and in_array($user_type, $valid_type)) {
            $this->validate($request, [
                'email' => 'required|string|email|max:255|unique:users',
                'full_name' => 'required|string',
                'mobile' => 'required|numeric',
                'password' => 'required|confirmed|min:6',
            ]);

            $data = $request->all();
            $role_name = ($user_type == 'instructors') ? Role::$teacher : Role::$user;
            $role_id = ($user_type == 'instructors') ? 4 : 1;

            $user = User::create([
                'role_name' => $role_name,
                'role_id' => $role_id,
                'email' => $data['email'],
                'organ_id' => $organization->id,
                'password' => Hash::make($data['password']),
                'full_name' => $data['full_name'],
                'mobile' => $data['mobile'],
                'language' => $data['language'],
                'newsletter' => (!empty($data['join_newsletter']) and $data['join_newsletter'] == 'on'),
                'public_message' => (!empty($data['public_messages']) and $data['public_messages'] == 'on'),
                'created_at' => time()
            ]);

            return redirect('/panel/manage/' . $user_type . '/' . $user->id . '/edit');
        }

        abort(404);
    }

    public function editUser($user_type, $user_id, $step = 1)
    {
        $valid_type = ['instructors', 'students'];
        $organization = auth()->user();

        if ($organization->isOrganization() and in_array($user_type, $valid_type)) {
            $user = User::where('id', $user_id)
                ->where('organ_id', $organization->id)
                ->first();

            if (!empty($user)) {
                $categories = Category::where('parent_id', null)
                    ->with('subCategories')
                    ->get();
                $userMetas = $user->userMetas;

                $occupations = $user->occupations->pluck('category_id')->toArray();

                $userLanguages = getGeneralSettings('user_languages');
                if (!empty($userLanguages) and is_array($userLanguages)) {
                    $userLanguages = getLanguages($userLanguages);
                }

                $data = [
                    'organization_id' => $organization->id,
                    'user' => $user,
                    'user_type' => $user_type,
                    'categories' => $categories,
                    'educations' => $userMetas->where('name', 'education'),
                    'experiences' => $userMetas->where('name', 'experience'),
                    'pageTitle' => trans('panel.settings'),
                    'occupations' => $occupations,
                    'userLanguages' => $userLanguages,
                    'currentStep' => $step,
                ];

                return view(getTemplate() . '.panel.setting.index', $data);
            }
        }

        abort(404);
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        $option = $request->get('option', null);
        $user = auth()->user();

        if (!empty($term)) {
            $query = User::select('id', 'full_name')
                ->where(function ($query) use ($term) {
                    $query->where('full_name', 'like', '%' . $term . '%');
                    $query->orWhere('email', 'like', '%' . $term . '%');
                    $query->orWhere('mobile', 'like', '%' . $term . '%');
                })
                ->where('id', '<>', $user->id)
                ->whereNotIn('role_name', ['admin']);

            if (!empty($option) and $option == 'just_teachers') {
                $query->where('role_name', 'teacher');
            }

            $users = $query->get();

            return response()->json($users, 200);
        }

        return response('', 422);
    }

    public function contactInfo(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required'
        ]);

        $user = User::find($request->get('user_id'));

        if (!empty($user)) {
            return response()->json([
                'code' => 200,
                'avatar' => $user->getAvatar(),
                'name' => $user->full_name,
                'email' => !empty($user->email) ? $user->email : '-',
                'phone' => !empty($user->mobile) ? $user->mobile : '-'
            ], 200);
        }

        return response()->json([], 422);
    }

    public function offlineToggle(Request $request)
    {
        $user = auth()->user();

        $message = $request->get('message');
        $toggle = $request->get('toggle');
        $toggle = (!empty($toggle) and $toggle == 'true');

        $user->offline = $toggle;
        $user->offline_message = $message;

        $user->save();

        return response()->json([
            'code' => 200
        ], 200);
    }








    public function teacher_update_profile(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        $rules = [
            'step' => 'required|min:1|max:4',
        ];
        if ($request->step == 1) {

            $rules['gender'] = 'required|string';
            $rules['nationality'] = 'required';
            $rules['date_of_birth'] = 'required|string';
            $rules['postal_code'] = 'required';
            $rules['country'] = 'required';
            $rules['city'] = 'required';
            $rules['address'] = 'required|string';
            $rules['bio'] = 'required|string';
        }
        if ($request->step == 2) {

            $rules['avatar'] = 'required|image|mimes:jpeg,jpg,png|required|max:20000,dimensions:min_width=100,min_height=100';


            if ($request->hasFile('cover_img')) {

                $rules['cover_img'] = 'required|image|mimes:jpeg,jpg,png|required|max:20000,dimensions:min_width=100,min_height=100,dimensions:ratio=3/2';
            }
        }
        if ($request->step == 3) {


            $rules['name_of_university'] = 'required|string';
            $rules['degree_level'] = 'required|string';
            $rules['degree_field'] = 'required|string';

            $rules['computer_skills'] = 'required|string';
            $rules['teaching_experience'] = 'required|string';
            $rules['teaching_experience_online'] = 'required|string';
        }


        if ($request->step == 4) {


            $rules['type_of_tutoring'] = 'required|string';

            $rules['availability_start_date'] = 'required|string';
            $rules['availability_end_date'] = 'required|string';
        }


        if ($request->step == 5) {

            $rules['subjects'] = 'required';
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


        if ($request->step == 1) {

            $user = User::find($token_user->id);


            $user->gender = $request->gender;
            $user->nationality = $request->nationality;
            $user->date_of_birth = $request->date_of_birth;
            $user->address = $request->address;
            $user->bio = $request->bio;
            $user->postal_code = $request->postal_code;
            $user->country = $request->country;
            $user->city = $request->city;


            if ($request->middle_name) {

                $user->middle_name = $request->middle_name;
            }
            if ($request->address2) {

                $user->address2 = $request->address2;
            }

            $user->update();

            event(new UserProfileEvent($user->id, $user, "Profile updated Successfully!"));
            dispatch(new UserProfileJob($user->id, $user, "Profile updated Successfully!"));

            return response()->json([
                'status' => true,
                'message' => 'Profile details updated successfully!!',
            ]);
        }
        if ($request->step == 2) {


            $user = User::find($token_user->id);


            if ($user->avatar == null && $user->cover_img == null) {

                $user->profile_completed_step = 2;
            }

            if ($request->hasFile('avatar')) {

                $imageName = rand(10, 100) . time() . '.' . $request->avatar->getClientOriginalExtension();
                $request->avatar->move(public_path('uploads/images'), $imageName);
                $user->avatar = $imageName;
            }
            if ($request->hasFile('cover_img')) {
                $imageName = rand(10, 100) . time() . '.' . $request->cover_img->getClientOriginalExtension();
                $request->cover_img->move(public_path('uploads/images'), $imageName);
                $user->cover_img = $imageName;
            }

            //  return $user;
            $user->update();


            return response()->json([

                'status' => true,
                'message' => 'Profile details updated successfully!'

            ]);
        }
        if ($request->step == 3) {



            $teaching_quali = TeachingQualification::where('user_id', $token_user->id)->first();

            if ($teaching_quali == null) {


                return response()->json([

                    'status' => false,
                    'message' => 'something went wrong',

                ], 400);
            } else {

                $teaching_quali->name_of_university = $request->name_of_university;
                $teaching_quali->degree_level = $request->degree_level;
                $teaching_quali->degree_field = $request->degree_field;

                $teaching_quali->computer_skills = $request->computer_skills;
                $teaching_quali->teaching_experience = $request->teaching_experience;
                $teaching_quali->current_employer = $request->current_employer;
                $teaching_quali->current_title = $request->current_title;

                $teaching_quali->update();



                return response()->json([

                    'status' => true,
                    'message' => 'Profile details updated successfully!'

                ]);
            }
        }

        if ($request->step == 4) {


            $teaching_specs = TeachingSpecification::where('user_id', $token_user->id)->first();

            if ($teaching_specs == null) {


                return response()->json([

                    'status' => false,
                    'message' => 'something went wrong',

                ]);
            } else {

                $teaching_specs->type_of_tutoring = $request->type_of_tutoring;
                $teaching_specs->availability_start_date = $request->availability_start_date;
                $teaching_specs->availability_end_date = $request->availability_end_date;
                $teaching_specs->update();


                return response()->json([

                    'status' => true,
                    'message' => 'Profile details updated successfully!'

                ]);
            }
        }
        if ($request->step == 5) {


            $teacher_subjects = json_decode(json_encode($request->subjects));



            foreach ($teacher_subjects as $subj) {



                $sub = TeacherSubject::where('user_id', $token_user->id)->where('program_id', $subj->program_id)->where('field_id', $subj->field_id)->where('subject_id', $subj->subject_id)->first();


                if (isset($subj->program_id) && $subj->program_id == 3) {

                    $sub = TeacherSubject::where('user_id', $token_user->id)->where('program_id', $subj->program_id)->where('country_id', $subj->country_id)->where('grade', $subj->grade)->where('field_id', $subj->field_id)->where('subject_id', $subj->subject_id)->first();
                }


                if ($sub == null) {

                    $teacher_sub = new TeacherSubject();
                    $teacher_sub->user_id = $token_user->id;
                    $teacher_sub->program_id = $subj->program_id;
                    $teacher_sub->field_id = $subj->field_id;
                    if ($subj->program_id == 3) {
                        $teacher_sub->country_id = $subj->country_id;
                        $teacher_sub->grade = $subj->grade;
                    }
                    $teacher_sub->subject_id = $subj->subject_id;
                    $teacher_sub->hourly_price = $subj->hourly_price;
                    $teacher_sub->save();
                } else {

                    $sub->user_id = $token_user->id;
                    $sub->program_id = $subj->program_id;
                    $sub->field_id = $subj->field_id;
                    if ($subj->program_id == 3) {
                        $sub->country_id = $subj->country_id;
                        $sub->grade = $subj->grade;
                    }
                    $sub->subject_id = $subj->subject_id;
                    $sub->hourly_price = $subj->hourly_price;
                    $sub->update();
                }
            }



            $user = User::find($token_user->id);
            $user->profile_completed_step = 5;
            $user->update();

            return response()->json([

                'status' => true,
                'message' => 'Profile details updated successfully!'

            ]);
        }
    }
}
