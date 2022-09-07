<?php

namespace App\Http\Controllers\Auth;

use App\Events\PasswordResetEvent;
use App\Events\SecuritySettingEvent;
use App\Http\Controllers\Controller;
use App\Jobs\PasswordResetJob;
use App\Jobs\SecuritySettingJob;
use App\Providers\RouteServiceProvider;
use App\User;
use App\UserCode;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use JWTAuth;


class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function getPassword($token)
    {

        return view(getTemplate() . '.auth.reset_password', ['token' => $token]);
    }



    public function change_password(Request $request)
    {


        $data = $request->all();
        $validator = Validator::make($data, [
            // 'id' => 'required',
            'current_password' => 'required',
            'new_password' => 'required|different:current_password|min:5|max:100',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        $user = User::find($token_user->id);

        // print_r($user);die;

        if (Hash::check($request->current_password, $user->password)) {

            $user->password = bcrypt($request->new_password);
            $user->update();

            // //Email and notifiaction
            // event(new SecuritySettingEvent($user->id, $user, "Password updated successfully!"));
            // dispatch(new ($user->id, $user, "Password updated successfully!"));

            //********* Sending Email to User **********
            $user_email = $user->email;
            $to_email = $user_email;

            $data = array('user' => $user);

            Mail::send('email.update_password', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('Password Updated on MEtutors');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
            //********* Sending Email ends **********




            return response()->json([
                'success' => true,
                'message' => 'Password Updated Successfully'
            ]);
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Current password is not correct'
            ], 400);
        }

        return response()->json([
            'success' => false,
            'message' => 'something went wrong'
        ], 500);
    }

    public function change_email(Request $request)
    {


        $data = $request->all();
        $validator = Validator::make($data, [
            // 'id' => 'required',
            'current_password' => 'required',
            'email' => 'required|string|email|max:100|unique:users',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        $user = User::find($token_user->id);


        if (Hash::check($request->current_password, $user->password)) {

            $next = $user->adminOtp();

            return $next;
        }

        // if (Hash::check($request->current_password, $user->password)) { 
        //       $user->email=$request->email;
        //       $user->update();
        //         return response()->json([
        //         'success' => true,
        //         'message' => 'email Updated Successfully'
        //     ]);
        // }
        else {

            return response()->json([
                'success' => false,
                'message' => 'Current password is not correct'
            ], 400);
        }

        return response()->json([
            'success' => false,
            'message' => 'something went wrong'
        ], 500);
    }

    public function submit_email_withOtp(Request $request)
    {


        $data = $request->all();
        $validator = Validator::make($data, [
            'otp' => 'required',
            'current_password' => 'required',
            'email' => 'required|string|email|max:100',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        $user = User::find($token_user->id);

        $find = UserCode::where('user_id', $token_user->id)->where('code', $request->otp)->where('updated_at', '>=', now()->subMinutes(5))->first();

        //   return $find;


        if ($find != null) {

            $find = UserCode::where('user_id', $token_user->id)->where('code', $request->otp)->where('updated_at', '>=', now()->subMinutes(5))->first();

            if ($find != null) {

                if (Hash::check($request->current_password, $user->password)) {
                    $user->email = $request->email;
                    $user->update();
                    return response()->json([
                        'success' => true,
                        'message' => 'email Updated Successfully'
                    ]);
                } else {

                    return response()->json([
                        'success' => false,
                        'message' => 'Current password is not correct'
                    ], 400);
                }
            } else {
                return response()->json([

                    'status' => 'false',
                    'message' => "OTP Expired",
                ], 400);
            }
        } else {

            return response()->json([

                'status' => 'false',
                'message' => "Invalid OTP",
            ], 401);
        }




        return response()->json([
            'success' => false,
            'message' => 'something went wrong'
        ], 500);
    }




    public function validate_password(Request $request)
    {



        $rules = [

            'current_password' => 'required|string|min:4',


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


        $user = $token_user;

        if (Hash::check($request->current_password, $user->password)) {


            return response()->json([
                'success' => true,
                'message' => 'Password Validated'
            ]);
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Current password is not correct'
            ], 401);
        }
    }
    public function updatePassword(Request $request)
    {

        $rules = [
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',

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



        $data = $request->all();

        $updatePassword = DB::table('password_resets')
            ->where(['email' => $data['email'], 'token' => $data['token']])
            ->first();

        if (!empty($updatePassword)) {
            $user = User::where('email', $data['email'])
                ->update(['password' => Hash::make($data['password'])]);

            DB::table('password_resets')->where(['email' => $data['email']])->delete();

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('auth.reset_password_success'),
                'status' => 'success'
            ];

            $user = User::where('email', $request->email)->first();

            //*********** Sending Email to teacher  ************\\
            $user_email = $user->email;
            $custom_message = "Password reset Successsfully";
            $to_email = $user_email;

            $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'user' => $user);

            Mail::send('email.password_reset', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('Password Reset on MEtutors');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
            // //******** Email ends **********//

            // event(new PasswordResetEvent($user->id, $user, "Password reset Successsfully"));
            // dispatch(new PasswordResetJob($user->id, $user, "Password reset Successsfully"));


            return response()->json([

                'status' => 'true',
                'message' => 'Your password successfully changed!',
                // 'toast' => $toastData

            ]);

            return redirect('/login')->with(['toast' => $toastData]);
        }

        $toastData = [

            'status' => false,
            'title' => trans('public.request_failed'),
            'message' => "This link has expired!",
        ];


        return $toastData;
        return back()->withInput()->with(['toast' => $toastData]);
    }
}
