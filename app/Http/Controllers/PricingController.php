<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Newsletter;
use App\Models\Role;
use App\Models\UserMeta;
use App\TeacherDocument;
use App\Models\UserOccupation;
use App\Models\UserZoomApi;
use App\Models\LevelOfEducation;
use App\User;
use App\Subject;
use App\Country;
use App\City;
use App\Program;
use App\TimeZone;
use App\FieldOfStudy;
use App\TeacherInterviewRequest;
use App\TeachingSpecification;
use App\TeachingQualification;
use App\CourseLevel;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;
use DateTime;
use \App\Mail\SendMailInvite;
use App\Models\Coupon;
use Carbon\Carbon;

class PricingController extends Controller
{



    public function estimated_price(Request $request)
    {


        $subject = Subject::find($request->subject_id);

        return response()->json([

            'status' => true,
            'estimated_price_per_hour' => $subject->price_per_hour,
        ]);
    }


    public function final_invoice(Request $request)
    {

        // print_r($request->classes);die;
        $total_hours = 0;
        // $total_hours = '00:00';
        // $total_hours = new DateTime(date('Y-m-d') . ' ' . $total_hours);
        // $total_hours = $total_hours->format('H:i');
        $classes = 0;

        foreach (json_decode(json_encode($request->classes)) as $class) {
            $classes++;
            $total_hours = $total_hours + $class->duration;
            // $start_time = $class->start_time;
            // $end_time = $class->end_time;

            // $start_datetime = new DateTime(date('Y-m-d') . ' ' . $start_time);
            // $end_datetime = new DateTime(date('Y-m-d') . ' ' . $end_time);

            // $time = $start_datetime->diff($end_datetime)->format('%H:%i');

            // $secs = strtotime($total_hours) - strtotime("00:00");
            // $total_hours = date("H:i:s", strtotime($time) + $secs);
        }
        // function decimal($total_hours)
        // {
        //     $hms = explode(":", $total_hours);
        //     return ($hms[0] + ($hms[1] / 60) + ($hms[2] / 3600));
        // }


        // $total_h = decimal($total_hours);


        // print_r($total_h);die;
        $subject = Subject::find($request->subject_id);
        $total_amount = $total_hours * $subject->price_per_hour;

        return response()->json([
            'status' => true,
            'no_of_classes' => $classes,
            'price_per_hour' => $subject->price_per_hour,
            'total_hours' => $total_hours,
            'total_amount' => $total_amount,
        ]);
    }

    public function discounted_final_invoice(Request $request)
    {
        $rules = [
            'promo_code' => 'required',
            'subject_id' => 'required',
            'classes' => 'required',
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
        
        $total_hours = 0;
        $classes = 0;

        foreach (json_decode(json_encode($request->classes)) as $class) {
            $classes++;
            $total_hours = $total_hours + $class->duration;
        }

        $subject = Subject::find($request->subject_id);
        $total_amount = $total_hours * $subject->price_per_hour;

        $today_date = Carbon::now()->toISOString();
        $coupon = Coupon::where('coupon_id', $request->promo_code)->first();
        if ($today_date > $coupon->expiry_date) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon expired',
            ], 400);
        }
        $discounted_price = $total_amount - ($total_amount * ($coupon->discount / 100));


        return response()->json([
            'status' => true,
            'no_of_classes' => $classes,
            'price_per_hour' => $subject->price_per_hour,
            'total_hours' => $total_hours,
            'total_amount' => $total_amount,
            'discount_percentage' => $coupon->discount,
            'discounted_amount' => $discounted_price,
        ]);
    }
}
