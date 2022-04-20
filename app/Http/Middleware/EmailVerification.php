<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use JWTAuth;

class EmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $user = User::find($token_user->id);

        if ($user->verified == 1) {
            return $next($request);
        } else {
            //********* Sending Email **********
            // $user_email = $user->email;
            // $to_email = $user_email;
            // $title = "Verification Code";
            // $message = "Your Email verifiaction code is given below";
            // $data = array('email' =>  $user_email);

            // Mail::send('web.default.emails.confirmCode', $data, function ($message) use ($to_email) {
            //     $message->to($to_email)->subject('Course Accepted');
            //     $message->from('metutorsmail@gmail.com', 'MeTutor');
            // });
            //********* Sending Email ends **********
            return response()->json([
                'status' => 'false',
                'errors' => 'Email Not Verified! Check your Mail.',
            ], 400);
        }
    }
}
