<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\UserCode;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;
use Twilio\Rest\Client;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use \App\Mail\SendMailOtp;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Verification;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/panel';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }




    public function request_otp()
    {

        return 'request otp';
    }


    public function logout(Request $request)
    {

        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'status' => 'success',
                'msg' => 'You have successfully logged out.'
            ]);
        } catch (JWTException $e) {
            JWTAuth::unsetToken();
            // something went wrong tries to validate a invalid token
            return response()->json([
                'status' => 'error',
                'msg' => 'Failed to logout, please try again.'
            ], 400);
        }
    }

    public function resendOtp(Request $request)
    {
        // return 'hello';

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $id = $token_user->id;

        //   return $id;

        $user = User::find($id);

        if ($user->role_name == 'admin') {

            $new = $user->resendOtp();

            return $new;
        }
    }
    public function verifyOtp(Request $request)
    {

        $rules = [
            'otp' => 'required',
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
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        // return 'hello';
        $id = $token_user->id;

        // return $request;
        $find = UserCode::where('id', $id)
            ->where('code', $request->otp)
            ->where('updated_at', '>=', Carbon::now()->subMinutes(2))
            ->first();


        if ($find != null) {

            $find = UserCode::where('id', $id)->where('code', $request->otp)->where('updated_at', '>=', Carbon::now()->subMinutes(2))->first();

            if ($find != null) {

                return response()->json([
                    'status' => true,
                    'message' => "OTP Verified",
                ]);
            } else {
                return response()->json([
                    'status' => 'false',
                    'message' => "OTP Expired",
                ], 400);
            }
        } else {

            $find = UserCode::where('id', $id)->where('updated_at', '>=', Carbon::now()->subMinutes(2))->first();
            //Otp Attempts
            if ($find->otp_attempts >= 3) {
                $find->update_at = Carbon::now()->addMinutes(2);
                return response()->json([
                    'status' => false,
                    'message' => "OTP Expired",
                ], 400);
            } else {
                $find->otp_attempts = $find->otp_attempts + 1;
            }

            $find->update();

            return response()->json([
                'status' => false,
                'message' => "Invalid OTP",
            ], 400);
        }

        return $array = array(
            'id' => $id,
            'find' => $find,
        );
    }
    public function login(Request $request)
    {


        $rules = [
            'username' => 'required|max:100',
            'password' => 'required|min:5|max:100',
        ];

        if ($this->username() == 'email') {
            $rules['username'] = 'required|string|email|max:100';
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

        if ($this->attemptLogin($request)) {

            $user = User::select('id', 'first_name', 'last_name', 'role_id', 'role_name', 'mobile', 'email',  'verified', 'avatar')->where('email', $request->username)->first();

            $credentials = array();
            $credentials['email'] = $request->username;
            $credentials['password'] = $request->password;

            $token = JWTAuth::customClaims(['user' => $user])->attempt($credentials);


            if ($user->role_name == 'admin') {

                $new = $user->generateOtp($token);

                return $new;
            }
            if ($user->role_name == 'teacher') {

                $user = User::select('id', 'first_name', 'last_name', 'role_name', 'role_id', 'mobile', 'email',  'verified', 'avatar', 'profile_completed_step')->where('email', $request->username)->first();

                $token = JWTAuth::customClaims(['user' => $user])->attempt($credentials);
            }

            $token_result = $user->createToken('METutor')->plainTextToken;





            $username = $this->username();

            $value = $request->get($username);

            $verificationId = $request->username;

            $verifications = Verification::where('email', $verificationId)
                ->where('verified_at', null)
                ->first();

            if (!empty($verifications)) {


                $value = $verificationId;
                $username = 'email';

                $data = [];
                $time = time();

                $data['email'] = $verificationId;
                $data['code'] = $this->getNewCode();
                $data['user_id'] = !empty($user) ? $user->id : (auth()->check() ? auth()->id() : null);
                $data['created_at'] = $time;
                $data['expired_at'] = $time + Verification::EXPIRE_TIME;
                $data['verified_at'] = null;

                $verification = Verification::updateOrCreate([$username => $value], $data);

                $verification->sendEmailCode();

                return response()->json([
                    'status' => true,
                    'message' => 'Verification Code Has Been Sent. Please verify your email first!!',
                ],400);
            }

            return response()->json([
                'status' => true,
                'message' => 'User Logged in Successfully!!',
                'user' => $user,
                'token' => $token
            ]);
        }



        return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);

        // return $this->sendFailedLoginResponse($request);
    }

    public function username()
    {
        $email_regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

        if (empty($this->username)) {
            $this->username = 'mobile';
            if (preg_match($email_regex, request('username', null))) {
                $this->username = 'email';
            }
        }
        return $this->username;
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = [
            $this->username() => $request->get('username'),
            'password' => $request->get('password')
        ];
        $remember = true;

        /*if (!empty($request->get('remember')) and $request->get('remember') == true) {
            $remember = true;
        }*/

        return $this->guard()->attempt($credentials, $remember);
    }

    public function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'username' => [trans('validation.password_or_username')],
        ]);
    }

    protected function sendBanResponse($user)
    {
        throw ValidationException::withMessages([
            'username' => [trans('auth.ban_msg', ['date' => dateTimeFormat($user->ban_end_at, 'Y M j')])],
        ]);
    }

    protected function sendNotActiveResponse($user)
    {
        $toastData = [
            'title' => trans('public.request_failed'),
            'msg' => trans('auth.login_failed_your_account_is_not_verified'),
            'status' => 'error'
        ];

        return redirect('/login')->with(['toast' => $toastData]);
    }

    public function afterLogged(Request $request, $verify = false)
    {
        $user = auth()->user();

        if ($user->ban) {
            $time = time();
            $endBan = $user->ban_end_at;
            if (!empty($endBan) and $endBan > $time) {
                $this->guard()->logout();
                $request->session()->flush();
                $request->session()->regenerate();

                return $this->sendBanResponse($user);
            } elseif (!empty($endBan) and $endBan < $time) {
                $user->update([
                    'ban' => false,
                    'ban_start_at' => null,
                    'ban_end_at' => null,
                ]);
            }
        }

        if (!$user->verified and !$verify) {
            $this->guard()->logout();
            $request->session()->flush();
            $request->session()->regenerate();

            $verificationController = new VerificationController();
            $checkConfirmed = $verificationController->checkConfirmed($user, $this->username(), $request->get('username'));

            if ($checkConfirmed['status'] == 'send') {
                return redirect('/verification');
            }
        } elseif ($verify) {
            session()->forget('verificationId');

            $user->update([
                'verified' => true,
                'status' => User::$active,
            ]);
        }

        if ($user->status != User::$active) {
            $this->guard()->logout();
            $request->session()->flush();
            $request->session()->regenerate();

            return $this->sendNotActiveResponse($user);
        }

        if ($user->isAdmin()) {
            return redirect('/admin');
        } else {
            return redirect('/panel');
        }
    }
    private function getNewCode()
    {
        return rand(10000, 99999);
    }
}
