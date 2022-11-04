<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use JWTAuth;
use Illuminate\Support\Facades\Validator;
use IlluminateAgnostic\Arr\Support\Collection;

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
        // Making coupon id
        $coupon_id = 'COP' . random_int(1000,9999);

        $coupon->coupon_id = $coupon_id;
        $coupon->name = $request->name;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->description = $request->description;
        $coupon->discount = $request->discount;
        $coupon->status = 'active';
        $coupon->save();

        return response()->json([
            'status' => true,
            'message' => 'Coupon created successfully',
            'coupon' => $coupon,
        ]);
    }

    public function coupons(Request $request)
    {
        $coupons = Coupon::paginate($request->per_page ?? 10);

        if ($request->has('search')) {
            $coupons = Coupon::where('name', 'LIKE', "%$request->search%")
                ->orWhere('description', 'LIKE', "%$request->search%")
                ->orWhereDate('expiry_date', $request->search)
                ->orWhere('coupon_id', $request->search)
                ->orWhere('discount', $request->search)
                ->paginate($request->per_page ?? 10);
        }

        $current_date = Carbon::now()->format('Y-m-d');

        $expired_coupons = Coupon::whereDate('expiry_date', '<', $current_date)
            ->where('status', 'active')->get();

        if (count($expired_coupons) > 0) {
            foreach ($expired_coupons as $coupon) {
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

    public function edit_coupon($coupon_id, Request $request)
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

        $coupon = Coupon::find($coupon_id);

        if ($coupon == '') {
            return response()->json([
                'status' => false,
                'message' => "Coupon not found",
            ], 400);
        }

        $coupon->name = $request->name;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->description = $request->description;
        $coupon->discount = $request->discount;
        $coupon->status = 'active';
        $coupon->update();

        return response()->json([
            'status' => true,
            'message' => 'Coupon updated successfully',
            'coupon' => $coupon,
        ]);
    }

    public function del_coupon($coupon_id)
    {

        $coupon = Coupon::find($coupon_id);

        if ($coupon == '') {
            return response()->json([
                'status' => false,
                'message' => "Coupon not found",
            ], 400);
        }

        $coupon->delete();

        return response()->json([
            'status' => true,
            'message' => "Coupon deleted successfully",
        ]);
    }

    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }
}
