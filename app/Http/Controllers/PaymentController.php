<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Billing\HyperPayBilling;
use App\Events\CompletePaymentEvent;
use App\Events\OrderEvent;
use App\Jobs\CompletePaymentJob;
use App\Models\AcademicClass;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Order;
use App\Program;
use App\Subject;
use Illuminate\Support\Str;
use Devinweb\LaravelHyperpay\Facades\LaravelHyperpay;
use JWTAuth;
use Devinweb\LaravelHyperpay\Events\SuccessTransaction;
use GuzzleHttp\Client;

class PaymentController extends Controller
{
    //Prepare Checkout page
    public function prepareCheckout(Request $request)
    {
        $rules = [
            'amount' => "required",
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

        $trackable_data = [
            'product_id' => 'bc842310-371f-49d1-b479-ad4b387f6630',
            'product_type' => 't-shirt'
        ];
        $user = User::findOrFail($token_user->id);
        $amount = $request->amount;
        $brand = 'VISA'; // MASTER OR MADA

        $id = Str::random('64');
        $payment = LaravelHyperpay::addMerchantTransactionId($id)->addBilling(new HyperPayBilling())->checkout($trackable_data, $user, $amount, $brand, $request);
        $payment = json_decode(json_encode($payment));
        $script_url = $payment->original->script_url;
        $shopperResultUrl = $payment->original->shopperResultUrl;
        // return view('payment_form', compact('script_url', 'shopperResultUrl'));
        return response()->json([
            'status' => true,
            'message' => "Checkout Prepared Successfully!",
            'script_url' => $script_url,
            'shopperResultUrl' => $shopperResultUrl,
        ]);
    }

    // After Form Submition based on success status create course
    public function paymentStatus(Request $request)
    {
        $rules = [
            'resource_path' => "required",
            'id' => "required",
            'course_id' => "required"
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

        $resourcePath = $request->get('resource_path');
        $checkout_id = $request->get('id');
        $payment_details = LaravelHyperpay::paymentStatus($resourcePath, $checkout_id);

        $paymentDetails = json_decode(json_encode($payment_details));

        $course = Course::findOrFail($request->course_id);
        $admin_message = "Course paymente completed!";
        $student = User::findOrFail($course->student_id);
        $teacher = User::findOrFail($course->teacher_id);
        $admin = User::where('role_name', 'admin')->first();

        //If we got success Response From HyperPay
        if ($paymentDetails->original->transaction_status == 'success') {
            $course->status = "pending";
            $course->update();

            $order = new Order();
            $order->user_id = $course->student_id;
            $order->course_id = $course->id;
            $order->transaction_id = $paymentDetails->original->id;
            // $order->transaction_id = $paymentDetails->original->transaction_id;
            $order->payment_status = 'success';
            $order->save();

            event(new CompletePaymentEvent($admin->id, $admin, $admin_message, $course));
            event(new CompletePaymentEvent($student->id, $student, $admin_message, $course));
            event(new CompletePaymentEvent($teacher->id, $teacher, $admin_message, $course));
            dispatch(new CompletePaymentJob($admin->id, $admin, $admin_message, $course));
            dispatch(new CompletePaymentJob($student->id, $student, $admin_message, $course));
            dispatch(new CompletePaymentJob($teacher->id, $teacher, $admin_message, $course));

            return response()->json([
                'status' => true,
                'message' => "Payment details!",
                'payment_details' => $payment_details,
                'course' => $course,
            ]);
        } else {
            $admin_message = "Course payment Failed!";
            event(new CompletePaymentEvent($admin->id, $admin, $admin_message, $course));
            event(new CompletePaymentEvent($student->id, $student, $admin_message, $course));
            event(new CompletePaymentEvent($teacher->id, $teacher, $admin_message, $course));
            dispatch(new CompletePaymentJob($admin->id, $admin, $admin_message, $course));
            dispatch(new CompletePaymentJob($student->id, $student, $admin_message, $course));
            dispatch(new CompletePaymentJob($teacher->id, $teacher, $admin_message, $course));

            return response()->json([
                'status' => false,
                'message' => $paymentDetails->original->message,
                'payment_details' => $payment_details,
            ], 400);
        }
    }


    // After Form Submition based on success status create course
    public function classPaymentStatus(Request $request)
    {
        $rules = [
            'resource_path' => "required",
            'id' => "required",
            'course_id' => "required",
            'classes' => "required",
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


        $resourcePath = $request->get('resource_path');
        $checkout_id = $request->get('id');
        $payment_details = LaravelHyperpay::paymentStatus($resourcePath, $checkout_id);

        $paymentDetails = json_decode(json_encode($payment_details));

        // If we got success Response From HyperPay
        if ($paymentDetails->original->transaction_status == 'success') {

            $course = Course::findOrFail($request->course_id);
            $Classes = $request->classes;
            $requestedClasses = AcademicClass::whereIn('id', $Classes)->get();

            $classes = [];
            foreach ($requestedClasses as $class) {
                $apiURL = 'https://api.braincert.com/v2/schedule';
                $postInput = [
                    'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
                    'title' =>  "title",
                    'timezone' => 90,
                    'start_time' => $class->start_time,
                    'end_time' => $class->end_time,
                    'date' => $class->start_date,
                    'currency' => "USD",
                    'ispaid' => null,
                    'is_recurring' => 0,
                    'repeat' => 0,
                    'weekdays' => $course->weekdays,
                    'end_date' => $class->start_date,
                    'seat_attendees' => null,
                    'record' => 0,
                    'isRecordingLayout ' => 1,
                    'isVideo  ' => 1,
                    'isBoard ' => 1,
                    'isLang ' => null,
                    'isRegion ' => null,
                    'isCorporate ' => null,
                    'isScreenshare ' => 1,
                    'isPrivateChat  ' => 0,
                    'description ' => null,
                    'keyword ' => null,
                    'format ' => "json",
                ];

                $client = new Client();
                $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

                $statusCode = $response->getStatusCode();
                $academic_class = AcademicClass::findOrFail($class->id);
                $responseBody = json_decode($response->getBody(), true);
                if ($responseBody['status'] == "ok") {

                    $academic_class->title = "class";
                    $academic_class->lesson_name = "lesson";
                    $academic_class->class_id = $responseBody['class_id'];
                    $academic_class->status = "scheduled";
                    $course->status = "active";
                    $course->update();
                    $academic_class->save();
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => $responseBody['error'],

                    ], 400);
                }
            }
            // return $classes;

            $classes = AcademicClass::whereIn('id', $Classes)->get();
            return response()->json([
                'status' => true,
                'message' => "Payment details!",
                'payment_details' => $payment_details,
                'course' => $course,
                'classes' => $classes,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $paymentDetails->original->message,
                'payment_details' => $payment_details,
            ], 400);
        }
    }

    public function payment_details(Request $request)
    {
        $rules = [
            'checkout_id' => "required",
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
        // return $request->checkout_id;
        $url = "https://test.oppwa.com/v1/checkouts/$request->checkout_id/payment";
        $url .= "?entityId=8ac7a4ca80b2d4470180b3d5cdf604c6";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'OGFjN2E0Y2E4MGIyZDQ0NzAxODBiM2Q1MzM5ODA0YzJ8UWhTUDhQZDZtNA=='
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

    public function refund($payment_id)
    {
        $url = "https://test.oppwa.com/v1/payments/$payment_id";
        $data = "entityId=8ac7a4ca80b2d4470180b3d5cdf604c6" .
            "&amount=10.00" .
            "&currency=USD" .
            "&paymentType=RF";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjN2E0Y2E4MGIyZDQ0NzAxODBiM2Q1MzM5ODA0YzJ8UWhTUDhQZDZtNA=='
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        // return $responseData;

        return response()->json([
            'status' => true,
            'message' => "Refunded Successfully!",
            'refund_details' => json_decode($responseData),
            // 'course' => $course,
        ]);
    }

    public function refund2(Request $request)
    {

        $rules = [
            'amount' => "required|numeric",
            'payment_brand' => "required|string",
            'card_number' => "required|numeric",
            'card_expiry_month' => "required|numeric",
            'card_expiry_year' => "required|numeric",
            'card_holder' => "required|string",
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

        $url = "https://test.oppwa.com/v1/payments";
        $data = "entityId=8ac7a4ca80b2d4470180b3d5cdf604c6" .
            "&amount=$request->amount" .
            "&currency=USD" .
            "&paymentBrand=$request->payment_brand" .
            "&paymentType=CD" .
            "&card.number=$request->card_number" .
            "&card.expiryMonth=$request->card_expiry_month" .
            "&card.expiryYear=$request->card_expiry_year" .
            "&card.holder=$request->card_holder";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjN2E0Y2E4MGIyZDQ0NzAxODBiM2Q1MzM5ODA0YzJ8UWhTUDhQZDZtNA=='
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        // return $responseData;
        return response()->json([
            'status' => true,
            'message' => "Refunded Successfully!",
            'refund_details' => json_decode($responseData),
            // 'course' => $course,
        ]);
    }


    // ***********************************Testing Functions*******************

    public function checkout(Request $request)
    {
        $trackable_data = [
            'product_id' => 'bc842310-371f-49d1-b479-ad4b387f6630',
            'product_type' => 't-shirt'
        ];
        $user = User::findOrFail(1149);
        $amount = 10;
        $brand = 'VISA'; // MASTER OR MADA

        $id = Str::random('64');
        $payment = LaravelHyperpay::addMerchantTransactionId($id)->addBilling(new HyperPayBilling())->checkout($trackable_data, $user, $amount, $brand, $request);
        $payment = json_decode(json_encode($payment));
        $script_url = $payment->original->script_url;
        $shopperResultUrl = $payment->original->shopperResultUrl;
        return view('payment_form', compact('script_url', 'shopperResultUrl'));
    }

    public function payment_status(Request $request)
    {
        $resourcePath = $request->get('resourcePath');
        $checkout_id = $request->get('id');
        $payment_details = LaravelHyperpay::paymentStatus($resourcePath, $checkout_id);

        return $paymentDetails = json_decode(json_encode($payment_details));

        //If we got success Response From HyperPay
        if ($paymentDetails->original->transaction_status == 'success') {
            // $course = Course::findOrFail($request->course_id);
            // $course->status = "pending";
            // $course->update();

            return response()->json([
                'status' => true,
                'message' => "Payment details!",
                'payment_details' => $payment_details,
                // 'course' => $course,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $paymentDetails->original->message,
                'payment_details' => $payment_details,
            ], 400);
        }
    }


    public function statusPayment($payment_id)
    {
        // $url = "https://test.oppwa.com/v1/payments/8ac7a4a181d328fb0181d32b149e04b5";
        $url = "https://test.oppwa.com/v1/payments/$payment_id";
        $url .= "?entityId=8ac7a4ca80b2d4470180b3d5cdf604c6";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjN2E0Y2E4MGIyZDQ0NzAxODBiM2Q1MzM5ODA0YzJ8UWhTUDhQZDZtNA=='
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return response()->json([
            'status' => true,
            'message' => "Payment details!",
            'payment_details' => json_decode($responseData),
            // 'course' => $course,
        ]);
    }
}
