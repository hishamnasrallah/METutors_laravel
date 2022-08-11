<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    public function index()
    {
        $verificationId = session()->get('verificationId', null);

        if (!empty($verificationId)) {
            $verification = Verification::where('id', $verificationId)
                ->whereNull('verified_at')
                ->where('expired_at', '>', time())
                ->first();

            if (!empty($verification)) {

                $user = User::find($verification->user_id);

                if (!empty($user) and !$user->verified) {
                    $data = [
                        'pageTitle' => trans('auth.email_confirmation'),
                        'username' => !empty($verification->mobile) ? 'mobile' : 'email',
                        'usernameValue' => !empty($verification->mobile) ? $verification->mobile : $verification->email,
                    ];

                    return view('web.default.auth.verification', $data);
                }
            }
        }

        return redirect('/login');
    }

    public function resendCode(Request $request)
    {



        $rules = [
            'email' => 'required|string|email',

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



        $verificationId = $request->email;

        // return $verificationId;


        $verification = Verification::where('email', $verificationId)
            ->whereNull('verified_at')
            ->where('expired_at', '>', time())
            ->first();


        // if (!empty($verification)) {
        //     if (!empty($verification->mobile)) {

        //         // return 'mobile';

        //         $verification->sendSMSCode();
        //     } else {

        //         // return 'email';

        //         $verification->sendEmailCode();
        //     }

        //     return response()->json([
        //             'status'=>true,
        //             'message'=>'Verification Code Has Been Sent !!' ,


        //             ]);

        //     return redirect('/verification');
        // }else{
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
            'message' => 'Verification Code Has Been Sent !!',


        ]);

        // }


        return 'hello';

        return redirect('/login');
    }

    public function checkConfirmed($user = null, $username, $value)
    {
        if (!empty($value)) {
            $verification = Verification::where($username, $value)
                ->where('expired_at', '>', time())
                ->where(function ($query) {
                    $query->whereNull('user_id')
                        ->orWhereHas('user');
                })
                ->first();

            $data = [];
            $time = time();

            if (!empty($verification)) {
                if (!empty($verification->verified_at)) {
                    return [
                        'status' => 'verified'
                    ];
                } else {
                    $data['created_at'] = $time;
                    $data['expired_at'] = $time + Verification::EXPIRE_TIME;

                    if (time() > $verification->expired_at) {
                        $data['code'] = $this->getNewCode();
                    } else {
                        $data['code'] = $verification->code;
                    }
                }
            } else {
                $data[$username] = $value;
                $data['code'] = $this->getNewCode();
                $data['user_id'] = !empty($user) ? $user->id : (auth()->check() ? auth()->id() : null);
                $data['created_at'] = $time;
                $data['expired_at'] = $time + Verification::EXPIRE_TIME;
            }

            $data['verified_at'] = null;

            $verification = Verification::updateOrCreate([$username => $value], $data);

            session()->put('verificationId', $verification->id);

            if ($username == 'mobile') {
                $verification->sendSMSCode();
            } else {
                $verification->sendEmailCode();
            }

            return [
                'status' => 'send'
            ];
        }

        abort(404);
    }

    public function confirmCode(Request $request)
    {






        $value = $request->get('username');
        $code = $request->get('code');
        $username = $this->username($value);
        $request[$username] = $value;
        $time = time();

        Verification::where($username, $value)
            ->whereNull('verified_at')
            ->where('code', $code)
            ->where('created_at', '>', $time - 24 * 60 * 60)
            ->update([
                'verified_at' => $time,
                'expired_at' => $time + 50,
            ]);

        $rules = [
            'code' => [
                'required',
                Rule::exists('verifications')->where(function ($query) use ($value, $code, $time, $username) {
                    $query->where($username, $value)
                        ->where('code', $code)
                        ->whereNotNull('verified_at')
                        ->where('expired_at', '>', $time);
                }),
            ],
            'username' => 'required'
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



        $authUser = auth()->check() ? auth()->user() : null;

        if (empty($authUser)) {
            $authUser = User::select('id', 'first_name', 'last_name', 'role_name', 'mobile', 'email',  'verified', 'avatar')
                ->where($username, $value)
                ->first();

            $loginController = new LoginController();

            if (!empty($authUser)) {
                if (\Auth::loginUsingId($authUser->id)) {

                    $user1 = User::find($authUser->id);
                    if ($user1->role_name == 'student') {
                        $user1->status = 'active';
                    }

                    $user1->verified = 1;
                    $user1->update();

                    return response()->json([
                        'status' => true,
                        'message' => 'Email verified Successfully! Please login to continue',
                        'return_url' => $request->return_url ?? false,

                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'user not found!!',

                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Email verified Successfully! Please login to continue',
                'return_url' => $request->return_url ?? false,
            ]);
        }
    }

    private function username($value)
    {
        $username = 'email';
        $email_regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

        if (preg_match($email_regex, $value)) {
            $username = 'email';
        } elseif (is_numeric($value)) {
            $username = 'mobile';
        }

        return $username;
    }

    private function getNewCode()
    {
        return rand(10000, 99999);
    }


    public function show()
    {


        return response()->json([
            'status' => false,
            'message' => 'Email is not verified yet',

        ], 401);
    }
}
