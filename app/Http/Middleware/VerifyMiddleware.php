<?php

namespace App\Http\Middleware;

use Closure;

class VerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if (auth()->check() and auth()->user()->verified == 1) {

            return $next($request);
        }else{
             return response()->json([
                
                'status' => 'false',
                'message' => "Please verify Your Email First",
                ]);
        }
        
        return $next($request);
    }
}
