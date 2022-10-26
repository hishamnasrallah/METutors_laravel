<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JWTAuth;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function create_coupon(Request $request)
    {
        
        
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $rules = [
            'name' =>  'required|string',
            'expiry_date' =>  'required',
            'description' =>  'required|string',
            'discount' =>  'required|integer',
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
       
        $coupons = Coupon::count();
        $coupons++;

        $coupon = new Coupon();
        $coupon->user_id = $token_user->id;
        $coupon_count = mb_strlen($coupons);

        // Making coupon id
        if($coupon_count == 1){
            $coupon_id = 'COP000'.$coupons;
        }
        elseif($coupon_count == 2){
            $coupon_id = 'COP00'.$coupons;
        }
        elseif($coupon_count == 3){
            $coupon_id = 'COP0'.$coupons;
        }
        else{
            $coupon_id = 'COP'.$coupons;
        }

        $coupon->coupon_id = $coupon_id;
        $coupon->name = $request->name;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->description = $request->description;
        $coupon->discount = $request->discount;
        $coupon->save();

        return response()->json([
            'status' => true,
            'message' => 'Coupon created successfully',
            'coupon' => $coupon,
        ]);
    }

    public function coupons()
    {
        $coupons = Coupon::all();
        $current_date = Carbon::now()->format('Y-m-d');

         $expired_coupons = Coupon::whereDate('expiry_date','<',$current_date)
        ->where('status','active')->get();

        if(count($expired_coupons) > 0){
            foreach($expired_coupons as $coupon){
                $Coupon = Coupon::find($coupon->id);
                $Coupon->status = 'expired';
                $Coupon->update();
            }
        }
        

        return response()->json([
            'status' => true,
            'message' => trans('api_messages.All_COUPONS'),
            'coupons' => $coupons,
        ]);
    }
}
