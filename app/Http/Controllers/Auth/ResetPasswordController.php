<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\UserCode;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


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
    
    
    
public function change_password(Request $request){
    
    
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
        
        $user=User::find(auth('sanctum')->user()->id);
        
        // print_r($user);die;
        
        if (Hash::check($request->current_password, $user->password)) { 
        
              $user->password=bcrypt($request->new_password);
              $user->update();
                return response()->json([
                'success' => true,
                'message' => 'Password Updated Successfully'
            ]);
             
        
        }else{
            
             return response()->json([
                'success' => false,
                'message' => 'Current password is not correct'
            ]);
        }
        
    return response()->json([
                'success' => false,
                'message' => 'something went wrong'
            ]);
    
}

public function change_email(Request $request){
    
    
    $data = $request->all();
        $validator = Validator::make($data, [
            // 'id' => 'required',
           'current_password' => 'required',
           'email' => 'required|string|email|max:100|unique:users',
           
        ]);
        
         if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        
        $user=User::find(auth('sanctum')->user()->id);
        
        
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
        else{
            
             return response()->json([
                'success' => false,
                'message' => 'Current password is not correct'
            ]);
        }
        
    return response()->json([
                'success' => false,
                'message' => 'something went wrong'
            ]);
    
}

public function submit_email_withOtp(Request $request){
    
    
    $data = $request->all();
        $validator = Validator::make($data, [
            'otp' => 'required',
           'current_password' => 'required',
           'email' => 'required|string|email|max:100',
           
        ]);
        
         if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        
        $user=User::find(auth('sanctum')->user()->id);
        
          $find=UserCode::where('user_id',auth('sanctum')->user()->id)->where('code',$request->otp)->where('updated_at', '>=', now()->subMinutes(5))->first();
          
        //   return $find;
         
         
         if($find != null){
             
               $find=UserCode::where('user_id',auth('sanctum')->user()->id)->where('code',$request->otp)->where('updated_at', '>=', now()->subMinutes(5))->first();
               
               if($find != null){
                   
                     if (Hash::check($request->current_password, $user->password)) { 
              $user->email=$request->email;
              $user->update();
                return response()->json([
                'success' => true,
                'message' => 'email Updated Successfully'
            ]);
        }
        else{
            
             return response()->json([
                'success' => false,
                'message' => 'Current password is not correct'
            ]);
        }
                    
                   
               }else{
                   return response()->json([
                
                'status' => 'false',
                'message' => "OTP Expired",
                ]) ;
                   
               }
              
               
         }else{
             
              return response()->json([
                
                'status' => 'false',
                'message' => "Invalid OTP",
                ]) ;
             
         }
        
       
       
        
    return response()->json([
                'success' => false,
                'message' => 'something went wrong'
            ]);
    
}

    
    

    public function validate_password(Request $request)
    {
        
        
        
          $rules = [
          
            'current_password' => 'required|string|min:4',
           
           
        ];

      
        $validator=Validator::make($request->all(),$rules);
        
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            
            return response()->json([
                
                'status' => 'false',
                'errors' => $errors,
                ]) ;
            
        }
        
        $user=auth('sanctum')->user();
        
        if (Hash::check($request->current_password, $user->password)) { 
        
             
                return response()->json([
                'success' => true,
                'message' => 'Password Validated'
            ]);
             
        
        }else{
            
             return response()->json([
                'success' => false,
                'message' => 'Current password is not correct'
            ]);
        }
        
        
        
        
        
        
    }
    public function updatePassword(Request $request)
    {
        
        
        
        
        
          $rules = [
           'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
           
        ];

      
        $validator=Validator::make($request->all(),$rules);
        
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            
            return response()->json([
                
                'status' => 'false',
                'errors' => $errors,
                ]) ;
            
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
            
            // return $toastData;
            
            
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
             'message' => trans('auth.reset_password_token_invalid'),
        ];
        
        
        return $toastData;
        return back()->withInput()->with(['toast' => $toastData]);
    }
}
