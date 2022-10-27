<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\User;
use Illuminate\Http\Request;
use App\UserCode;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Validator;
use Twilio\Rest\Client;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use \App\Mail\SendMailOtp;
use Illuminate\Support\Facades\Mail;
use JWTAuth;




class SocialiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function facebook_signup(Request $request)
    {

        $rules = [

            'id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'authToken' => 'required',
            'response' => 'required',
            'provider' => 'required',
            'role' => 'required',

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


        try {


            if (isset($request->authToken)) {

                $driver = Socialite::driver('facebook');

                $socialUser = $driver->userFromToken($request->authToken);

                $roles = Role::find($request->role);

                $user = User::where('email', $socialUser->email)->first();

                if ($user == null) {

                    $user = new User();

                    $user->role_name = $roles->name;
                    $user->role_id = $request->role;
                    $user->first_name = $request->firstName;
                    $user->last_name = $request->lastName;
                    $user->email = $request->email;
                    $user->status = 'active';
                    $user->password = null;
                    $user->mobile = null;
                    $user->verified = 1;
                    $user->save();


                    $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar')->where('email', $request->email)->first();

                    if ($user->role_name == 'teacher') {

                        $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')->where('email', $request->email)->first();
                    }



                    $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);

                    //********* Sending Email to User **********
                    $user_email = $user->email;
                    $custom_message = "User Registerd Successfully";
                    $to_email = $user_email;

                    $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'user' => $user);

                    Mail::send('email.registeration', $data, function ($message) use ($to_email) {

                        $message->to($to_email)->subject('Welcome to MEtutors');
                        $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                    });

                    //********* Sending Email ends **********

                    return response()->json([
                        'status' => true,
                        'message' => trans('api_messages.USER_LOGIN_SUCCESSFULLY'),
                        'user' => $user,
                        'token' => $token
                    ]);
                } else {

                    $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar')->where('email', $request->email)->first();

                    if($user->role_id != $request->role){
                        return response()->json([
                            'status' => false,
                            'message' => trans('api_messages.EMAIL_ALREADY_REGISTERED_ROLE'),
                        ],400);
                    }

                    if ($user->role_name == 'teacher') {

                        $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')->where('email', $request->email)->first();
                    }


                    $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);

                    return response()->json([
                        'status' => true,
                        'message' => trans('api_messages.USER_LOGIN_SUCCESSFULLY'),
                        'user' => $user,
                        'token' => $token
                    ]);
                }



                return response()->json([

                    'status' => 'false',
                    'user' => $socialUser,
                ]);
            } else {
                return response()->json([

                    'status' => 'false',
                    'message' => 'Auth token required',
                ], 400);
            }
        } catch (Exception $e) {


            return response()->json([

                'status' => 'false',
                'message' => 'Auth token invalid',
            ], 401);
        }
    }



    public function google_signup(Request $request)
    {

        $rules = [

            'id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'authToken' => 'required',
            'idToken' => 'required',
            'response' => 'required',
            'provider' => 'required',
            'role' => 'required',

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


        try {


            if (isset($request->response['access_token'])) {

                $driver = Socialite::driver('google');

                $socialUser = $driver->userFromToken($request->response['access_token']);

                $roles = Role::find($request->role);

                $user = User::where('email', $socialUser->email)->first();

                if ($user == null) {

                    $user = new User();

                    $user->role_name = $roles->name;
                    $user->role_id = $request->role;
                    $user->first_name = $request->firstName;
                    $user->last_name = $request->lastName;
                    $user->email = $request->email;
                    $user->status = 'active';
                    $user->password = null;
                    $user->mobile = null;
                    $user->verified = 1;
                    $user->save();



                    $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar')->where('email', $request->email)->first();

                    if ($user->role_name == 'teacher') {

                        $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')->where('email', $request->email)->first();
                    }



                    $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);

                    //********* Sending Email to user **********
                    $user_email = $user->email;
                    $custom_message = "User Registerd Successfully";
                    $to_email = $user_email;

                    $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'user' => $user);

                    Mail::send('email.registeration', $data, function ($message) use ($to_email) {

                        $message->to($to_email)->subject('Welcome to MEtutors');
                        $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
                    });

                    //********* Sending Email ends **********

                    return response()->json([
                        'status' => true,
                        'message' => trans('api_messages.USER_LOGIN_SUCCESSFULLY'),
                        'user' => $user,
                        'token' => $token
                    ]);
                } else {

                    $user = User::select('id', 'first_name', 'last_name', 'role_id', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar')->where('email', $request->email)->first();

                    if ($user->role_id != $request->role) {

                        return response()->json([
                            'status' => false,
                            'message' => trans('api_messages.EMAIL_ALREADY_REGISTERED_ROLE'),
                        ], 400);
                    }

                    if ($user->role_name == 'teacher') {

                        $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')->where('email', $request->email)->first();
                    }



                    $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);


                    $token = $token = JWTAuth::customClaims(['user' => $user])->fromUser($user);

                    return response()->json([
                        'status' => true,
                        'message' => trans('api_messages.USER_LOGIN_SUCCESSFULLY'),
                        'user' => $user,
                        'token' => $token
                    ]);
                }



                return response()->json([

                    'status' => 'false',
                    'user' => $socialUser,
                ]);
            } else {
                return response()->json([

                    'status' => 'false',
                    'message' => 'access token required',
                ], 400);
            }
        } catch (Exception $e) {


            return response()->json([

                'status' => 'false',
                'message' => 'access token invalid',
            ], 401);
        }
    }




    public function check_googleId(Request $request)
    {

        $rules = [

            'google_id' => 'required',
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


        $user = User::where('google_id', $request->google_id)->first();

        if ($user != null) {

            return response()->json([

                'status' => true,
                'message' => 'User exists already',
            ], 400);
        } else {


            return response()->json([

                'status' => false,
                'message' => 'User does not exist',
            ], 404);
        }
    }


    public function check_facebookId(Request $request)
    {


        $rules = [

            'facebook_id' => 'required',
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

        $user = User::where('facebook_id', $request->facebook_id)->first();

        if ($user != null) {

            return response()->json([

                'status' => true,
                'message' => 'User exists already',
            ]);
        } else {

            return response()->json([

                'status' => false,
                'message' => 'User does not exist',
            ], 404);
        }
    }



    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // $request = Socialite::driver('google')->user();

            $user = User::where('google_id', $request->id)
                ->orWhere('email', $request->email)
                ->first();

            if (empty($user)) {
                $user = User::create([
                    'full_name' => $request->name,
                    'email' => $request->email,
                    'google_id' => $request->id,
                    'role_id' => 1,
                    'role_name' => Role::$user,
                    'status' => User::$active,
                    'verified' => true,
                    'created_at' => time(),
                    'password' => null
                ]);
            }

            $user->update([
                'google_id' => $request->id,
            ]);

            Auth::login($user);

            return redirect('/');
        } catch (Exception $e) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('auth.fail_login_by_google'),
                'status' => 'error'
            ];
            return ['toast' => $toastData];
        }
    }

    /**
     * Create a redirect method to facebook api.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleFacebookCallback(Request $request)
    {
        try {
            // $request = Socialite::driver('facebook')->user();

            $user = User::where('facebook_id', $request->id)->first();

            if (empty($user)) {
                $user = User::create([
                    'first_name' => $request->name,
                    'email' => $request->email,
                    'facebook_id' => $request->id,
                    'role_id' => 1,
                    'role_name' => Role::$user,
                    'status' => User::$active,
                    'verified' => true,
                    'created_at' => time(),
                    'password' => null
                ]);
            }

            Auth::login($user);
            return redirect('/');
        } catch (Exception $e) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('auth.fail_login_by_facebook'),
                'status' => 'error'
            ];
            return ['toast' => $toastData];
        }
    }
}
