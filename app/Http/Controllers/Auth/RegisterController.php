<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegisterEvent;
use App\Http\Controllers\Controller;
use App\Jobs\UserRegisterJob;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }



    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $username = $this->username();

        if ($username == 'mobile') {
            $data[$username] = ltrim($data['country_code'], '+') . ltrim($data[$username], '0');
        }

        return Validator::make($data, [
            'country_code' => ($username == 'mobile') ? 'required' : 'nullable',
            $username => ($username == 'mobile') ? 'required|numeric|unique:users' : 'required|string|email|max:255|unique:users',
            'term' => 'required',
            'full_name' => 'required|string|min:3',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return
     */
    protected function create(array $data)
    {
        $username = $this->username();

        $roles = Role::find($data['role']);

        if ($username == 'mobile') {
            $data[$username] = ltrim($data['country_code'], '+') . ltrim($data[$username], '0');
        }

        $user = User::create([
            'role_name' => $roles->name,
            'role_id' => $data['role'],
            $username => $data[$username],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'status' => User::$pending,
            'password' => Hash::make($data['password']),
            'mobile' => $data['country_code'] . '' . $data['mobile'],

        ]);

        return $user;
    }

    public function username()
    {
        $email_regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

        if (empty($this->username)) {
            $this->username = 'mobile';
            if (!empty(request('email', null))) {
                $this->username = 'email';
            }
        }
        return $this->username;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));

        $username = $this->username();

        $value = $request->get($username);
        if ($username == 'mobile') {
            $value = $request->get('country_code') . ltrim($request->get($username), '0');
        }

        $verificationController = new VerificationController();
        $checkConfirmed = $verificationController->checkConfirmed($user, $username, $value);

        if ($checkConfirmed['status'] == 'send') {
            return redirect('/verification');
        } elseif ($checkConfirmed['status'] == 'verified') {
            $this->guard()->login($user);

            $user->update([
                'status' => User::$active,
            ]);

            if ($response = $this->registered($request, $user)) {
                return $response;
            }

            return $request->wantsJson()
                ? new JsonResponse([], 201)
                : redirect($this->redirectPath());
        }
    }


    public function registeration(Request $request)
    {

        $rules = [
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'country_code' => 'required',
            'mobile' => 'required|min:5|max:15',
            'role' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:8|max:100',
            'confirm_password' => 'required|same:password',
        ];

        if ($this->username() == 'email') {
            $rules['email'] = 'required|string|email|max:255|unique:users';
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

        if ($request->role == 2 || $request->role > 3) {



            return response()->json([

                'status' => 'false',
                'message' => "Invalid role",
            ], 400);
            // return $this->respondWithError($errors,500);
        }


        $user = $this->create($request->all());

        $username = $this->username();

        $value = $request->get($username);
        if ($username == 'mobile') {
            $value = $request->get('country_code') . ltrim($request->get($username), '0');
        }

        $verificationController = new VerificationController();
        $checkConfirmed = $verificationController->checkConfirmed($user, $username, $value);

        if ($checkConfirmed['status'] == 'send') {

            if ($user->role_name == 'teacher') {
                $user_count = User::where('role_name', 'teacher')->count();

                $user_count = 100000 + $user_count;
                $id_number = 'T' . $user_count;
            }
            if ($user->role_name == 'student') {
                $user_count = User::where('role_name', 'student')->count();
                
                $user_count = 100000 + $user_count;
                $id_number = 'S' . $user_count;
            }

            $update_user = User::find($user->id);
            $update_user->id_number = $id_number;
            if ($request->has('return_url')) {
                $update_user->redirect_url = $request->return_url;
            }
            $update_user->update();

            $admin = User::where('role_name', 'admin')->first();

            // Emails and notifications for registeration
            // event(new UserRegisterEvent($user->id, $user, "Registerd Successfully"));
            // event(new UserRegisterEvent($admin->id, $admin, "A New User Registerd Successfully"));
            // dispatch(new UserRegisterJob($user->id, $user, "Registerd Successfully"));
            // dispatch(new UserRegisterJob($admin->id, $admin, "A New User Registerd Successfully"));

            return response()->json([
                'status' => true,
                'message' => trans('api_messages.USER_REGISTERED_VERIFICATION_SENT'),
                'return_url' => $request->return_url ?? false,
            ]);

            return redirect('/verification');
        } elseif ($checkConfirmed['status'] == 'verified') {
            $this->guard()->login($user);

            $user->update([
                'status' => User::$active,
            ]);

            if ($response = $this->registered($request, $user)) {
                return $response;
            }
        }
    }
}
