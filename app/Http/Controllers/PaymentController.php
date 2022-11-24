<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Billing\HyperPayBilling;
use App\Events\CompletePaymentEvent;
use App\Events\OrderEvent;
use App\Jobs\CompletePaymentJob;
use App\Models\AcademicClass;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\DisputeComment;
use App\Models\DisputeTicket;
use App\Models\Order;
use App\Models\TeacherPayment;
use App\Models\Ticket;
use App\Program;
use App\Subject;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Devinweb\LaravelHyperpay\Facades\LaravelHyperpay;
use JWTAuth;
use Devinweb\LaravelHyperpay\Events\SuccessTransaction;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use stdClass;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use IlluminateAgnostic\Arr\Support\Collection;

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
            'message' => "Checkout prepared successfully!",
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

        $course = Course::findOrFail($request->course_id);
        if ($course->status == 'pending') {
            return response()->json([
                'status' => false,
                'message' => 'The course has already booked',
            ], 400);
        }

        $resourcePath = $request->get('resource_path');
        $checkout_id = $request->get('id');
        // $payment_details = LaravelHyperpay::paymentStatus($resourcePath, $checkout_id);

        $url = "https://eu-test.oppwa.com/v1/checkouts/" . $checkout_id . "/payment";
        $url .= "?entityId=8ac7a4ca80b2d4470180b3d5cdf604c6";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjN2E0Y2E4MGIyZDQ0NzAxODBiM2Q1MzM5ODA0YzJ8UWhTUDhQZDZtNA=='
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $payment_details = curl_exec($ch);
        if (curl_errno($ch)) {
            // return curl_error($ch);
            return response()->json([
                'status' => false,
                'message' =>  curl_errno($ch),
            ], 400);
        }
        curl_close($ch);




        $paymentDetails = json_decode(json_encode($payment_details));
        $paymentDetails = json_decode($paymentDetails);

        $course = Course::findOrFail($request->course_id);
        $classroom = ClassRoom::where('course_id', $request->course_id)->first();
        $admin_message = "Course paymente completed!";
        $student = User::findOrFail($course->student_id);
        if ($course->teacher_id != null) {
            $teacher = User::findOrFail($course->teacher_id);
        }

        $admin = User::where('role_name', 'admin')->first();

        //If we got success Response From HyperPay
        // if ($paymentDetails->original->transaction_status == 'success') {
        $course->status = "pending";
        $classroom->status = "pending";
        $course->update();
        $classroom->update();

        $user = User::with('billing_info')->findOrFail($course->student_id);
        $biiling_info = $user['billing_info'];

        $order = new Order();
        $order->user_id = $course->student_id;
        $order->course_id = $course->id;
        $order->transaction_id = $paymentDetails->id;

        //Assigning booking_id
        $Order = Order::whereNotNull('booking_id')->latest()->first();
        if ($Order != '') {
            $id = str_replace('B', ' ', $Order->booking_id);
            $id = $id + 1;
            $order->booking_id = 'B' . $id;
            $order->invoice_id = 'IN' . $id;
        } else {
            $order->booking_id = 'B100001';
            $order->invoice_id = 'IN100001';
        }


        //billing details
        $order->billing_country = $biiling_info->country;
        $order->billing_city =  $biiling_info->city;
        $order->billing_state =  $biiling_info->state;
        $order->billing_street =  $biiling_info->street;
        $order->postcode =  $biiling_info->postcode;

        // $order->transaction_id = $paymentDetails->original->transaction_id;
        $order->payment_status = 'success';
        $order->save();

        event(new CompletePaymentEvent($admin->id, $admin, $admin_message, $course));
        event(new CompletePaymentEvent($student->id, $student, $admin_message, $course));
        if ($course->teacher_id != null) {
            event(new CompletePaymentEvent($teacher->id, $teacher, $admin_message, $course));
            dispatch(new CompletePaymentJob($teacher->id, $teacher, $admin_message, $course));
        }

        dispatch(new CompletePaymentJob($admin->id, $admin, $admin_message, $course));
        dispatch(new CompletePaymentJob($student->id, $student, $admin_message, $course));


        return response()->json([
            'status' => true,
            'message' => "Payment details!",
            'payment_details' => $paymentDetails,
            'course' => $course,
        ]);
        // } else {
        // $admin_message = "Course payment Failed!";
        // event(new CompletePaymentEvent($admin->id, $admin, $admin_message, $course));
        // event(new CompletePaymentEvent($student->id, $student, $admin_message, $course));
        // event(new CompletePaymentEvent($teacher->id, $teacher, $admin_message, $course));
        // dispatch(new CompletePaymentJob($admin->id, $admin, $admin_message, $course));
        // dispatch(new CompletePaymentJob($student->id, $student, $admin_message, $course));
        // dispatch(new CompletePaymentJob($teacher->id, $teacher, $admin_message, $course));

        return response()->json([
            'status' => false,
            // 'message' => $paymentDetails->original->message,
            'message' => $paymentDetails->result->description,
            'payment_details' => $paymentDetails,
        ], 400);
        // }
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
        // $payment_details = LaravelHyperpay::paymentStatus($resourcePath, $checkout_id);

        $url = "https://eu-test.oppwa.com/v1/checkouts/" . $checkout_id . "/payment";
        $url .= "?entityId=8ac7a4ca80b2d4470180b3d5cdf604c6";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjN2E0Y2E4MGIyZDQ0NzAxODBiM2Q1MzM5ODA0YzJ8UWhTUDhQZDZtNA=='
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $payment_details = curl_exec($ch);
        if (curl_errno($ch)) {
            // return curl_error($ch);
            return response()->json([
                'status' => false,
                'message' =>  curl_errno($ch),
            ], 400);
        }
        curl_close($ch);

        $paymentDetails = json_decode(json_encode($payment_details));

        // If we got success Response From HyperPay
        // if ($paymentDetails->original->transaction_status == 'success') {

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
                'start_time' => Carbon::parse($class->start_time)->format('G:i a'),
                'end_time' => Carbon::parse($class->end_time)->format('G:i a'),
                'date' => Carbon::parse($class->start_date)->format('Y-m-d'),
                'currency' => "USD",
                'ispaid' => null,
                'is_recurring' => 0,
                'repeat' => 0,
                'weekdays' => $course->weekdays,
                'end_date' => Carbon::parse($class->start_date)->format('Y-m-d'),
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
            'payment_details' => json_decode($payment_details),
            'course' => $course,
            'classes' => $classes,
        ]);
        // } else {
        //     return response()->json([
        //         'status' => false,
        //         'message' => $paymentDetails->original->message,
        //         'payment_details' => $payment_details,
        //     ], 400);
        // }
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
            'message' => "Refunded successfully!",
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
            'message' => "Refunded successfully!",
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
        // $resourcePath = $request->get('resourcePath');
        // $checkout_id = $request->get('id');
        // $payment_details = LaravelHyperpay::paymentStatus($resourcePath, $checkout_id);

        // $paymentDetails = json_decode(json_encode($payment_details));

        // //If we got success Response From HyperPay
        // if ($paymentDetails->original->transaction_status == 'success') {
        //     // $course = Course::findOrFail($request->course_id);
        //     // $course->status = "pending";
        //     // $course->update();

        //     return response()->json([
        //         'status' => true,
        //         'message' => "Payment details!",
        //         'payment_details' => $payment_details,
        //         // 'course' => $course,
        //     ]);
        // } else {
        //     return response()->json([
        //         'status' => false,
        //         'message' => $paymentDetails->original->message,
        //         'payment_details' => $payment_details,
        //     ], 400);
        // }


        //Curl implementation for payment request 

        $url = "https://eu-test.oppwa.com/v1/checkouts/" . $request->get('id') . "/payment";
        $url .= "?entityId=8ac7a4ca80b2d4470180b3d5cdf604c6";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjN2E0Y2E4MGIyZDQ0NzAxODBiM2Q1MzM5ODA0YzJ8UWhTUDhQZDZtNA=='
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $paymentDetails = curl_exec($ch);
        if (curl_errno($ch)) {
            // return curl_error($ch);
            return response()->json([
                'status' => false,
                'message' => curl_error($ch),
                'payment_details' => $paymentDetails,
            ], 400);
        }
        curl_close($ch);
        // return $paymentDetails;
        return response()->json([
            'status' => true,
            'message' => "Payment details!",
            'payment_details' => $paymentDetails,
        ]);
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

    public function payment_records(Request $request)
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($request->has('payment')) {
            ###### Pending payments
            if ($request->payment == 'pending') {

                $records = TeacherPayment::with('course.course_order', 'course.subject')
                    ->where('payment_status', 'pending')
                    ->where('user_id', $token_user->id)
                    // ->where('is_dispute', '0')
                    ->get();

                $payment_records = $records->groupBy('transaction_id');

                $pending_array = [];
                if (count($payment_records) > 0) {
                    foreach ($payment_records as $payment_record) {
                        $course = new stdClass();
                        $course->transaction_id = $payment_record[0]->transaction_id;
                        $course->month = Carbon::parse($payment_record[0]->created_at)->format('M Y');
                        // $course->amount =  $payment_record[0]['course']->total_price;
                        $course->status =  'pending';

                        $courses = $payment_record->unique('course_id');

                        $pending_courses = [];
                        $total_classes = 0;
                        $overall_amount = 0;

                        foreach ($courses as $record) {

                            $completed_classes = AcademicClass::with('course.subject')
                                ->where('course_id', $record->course_id)
                                ->where('status', 'completed')
                                ->where('payment_status', 'in_progress')
                                ->get();

                            if (count($completed_classes) > 0) {
                                $total_hours =  $completed_classes->sum('duration');
                                $total_amount = $total_hours * $completed_classes[0]['course']['subject']->price_per_hour;

                                $course1 = new stdClass();
                                $course1->course_id = $record->course_id;
                                $course1->order_id = $record['course_order']->booking_id ?? '';
                                $course1->course_code = $record['course']->course_code;
                                $course1->course_name = $record['course']->course_name;
                                $course1->completion_date = Carbon::parse($record['course']->end_date)->format('d M Y');
                                $course1->price_per_hour = $record['course']['subject']->price_per_hour;
                                $course1->completed_classes = count($completed_classes);
                                $course1->total_hours = $total_hours;
                                $course1->total_amount = $total_amount;
                                $pending_courses[] = $course1;
                                $total_classes = $total_classes + count($completed_classes);
                                $overall_amount = $overall_amount + $total_amount;
                            }
                        }


                        $course->total_classes = $total_classes;
                        $course->total_amount = $overall_amount;
                        $course->courses =  $pending_courses;
                        $pending_array[] = $course;
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => "Pending payments!",
                    'payment_records' => $this->paginate($pending_array, $request->per_page ?? 10),
                ]);
            } elseif ($request->payment == 'disputed') {

                $records = TeacherPayment::with('course.course_order', 'course.subject')
                    ->where('payment_status', 'pending')
                    ->where('is_dispute', '1')
                    ->where('user_id', $token_user->id)
                    ->get();

                $payment_records = $records->groupBy('transaction_id');

                $pending_array = [];
                if (count($payment_records) > 0) {
                    foreach ($payment_records as $payment_record) {
                        $course = new stdClass();
                        $course->transaction_id = $payment_record[0]->transaction_id;
                        $course->month = Carbon::parse($payment_record[0]->created_at)->format('M Y');
                        $course->clearance_date = $payment_record[0]->clearance_date;
                        // $course->amount =  $payment_record[0]['course']->total_price;
                        $course->method = $payment_record[0]->payment_method;
                        $course->status =  'pending';

                        $courses = $payment_record->unique('course_id');

                        $pending_courses = [];
                        $total_classes = 0;
                        $overall_amount = 0;

                        foreach ($courses as $record) {

                            $completed_classes = AcademicClass::with('course.subject')->where('course_id', $record->course_id)
                                ->where('status', 'completed')
                                ->where('payment_status', 'in_progress')
                                ->get();

                            if (count($completed_classes) > 0) {
                                $total_hours =  $completed_classes->sum('duration');
                                $total_amount = $total_hours * $completed_classes[0]['course']['subject']->price_per_hour;

                                $course1 = new stdClass();
                                $course1->course_id = $record->course_id;
                                $course1->order_id = $record['course_order']->booking_id ?? '';
                                $course1->course_code = $record['course']->course_code;
                                $course1->course_name = $record['course']->course_name;
                                $course1->completion_date = Carbon::parse($record['course']->end_date)->format('d M Y');
                                $course1->price_per_hour = $record['course']['subject']->price_per_hour;
                                $course1->completed_classes = count($completed_classes);
                                $course1->total_hours = $total_hours;
                                $course1->total_amount = $total_amount;
                                $pending_courses[] = $course1;
                                $total_classes = $total_classes + count($completed_classes);
                                $overall_amount = $overall_amount + $total_amount;
                            }
                        }


                        $course->total_classes = $total_classes;
                        $course->total_amount = $overall_amount;
                        $course->courses =  $pending_courses;
                        $pending_array[] = $course;
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => "Disputed payments!",
                    'payment_records' => $this->paginate($pending_array, $request->per_page ?? 10),
                ]);
            } elseif ($request->payment == 'history') {

                $records = TeacherPayment::with('course.course_order', 'course.subject')
                    ->where('payment_status', 'completed')
                    ->where('user_id', $token_user->id)
                    ->get();

                $payment_records = $records->groupBy('transaction_id');

                $pending_array = [];
                if (count($payment_records) > 0) {
                    foreach ($payment_records as $payment_record) {
                        $course = new stdClass();
                        $course->transaction_id = $payment_record[0]->transaction_id;
                        $course->month = Carbon::parse($payment_record[0]->created_at)->format('M Y');
                        $course->transaction_date = $payment_record[0]->transaction_date;
                        // $course->amount =  $payment_record[0]['course']->total_price;
                        $course->method = $payment_record[0]->payment_method;
                        $course->status =  'completed';

                        $courses = $payment_record->unique('course_id');

                        $pending_courses = [];
                        $total_classes = 0;
                        $overall_amount = 0;

                        foreach ($courses as $record) {

                            $completed_classes = AcademicClass::with('course.subject')->where('course_id', $record->course_id)
                                ->where('status', 'completed')
                                ->where('payment_status', 'completed')
                                ->get();

                            if (count($completed_classes) > 0) {
                                $total_hours =  $completed_classes->sum('duration');
                                $total_amount = $total_hours * $completed_classes[0]['course']['subject']->price_per_hour;

                                $course1 = new stdClass();
                                $course1->course_id = $record->course_id;
                                $course1->order_id = $record['course_order']->booking_id ?? '';
                                $course1->course_code = $record['course']->course_code;
                                $course1->course_name = $record['course']->course_name;
                                $course1->completion_date = Carbon::parse($record['course']->end_date)->format('d M Y');
                                $course1->price_per_hour = $record['course']['subject']->price_per_hour;
                                $course1->completed_classes = count($completed_classes);
                                $course1->total_hours = $total_hours;
                                $course1->total_amount = $total_amount;
                                $pending_courses[] = $course1;
                                $total_classes = $total_classes + count($completed_classes);
                                $overall_amount = $overall_amount + $total_amount;
                            }
                        }


                        $course->total_classes = $total_classes;
                        $course->total_amount = $overall_amount;
                        $course->courses =  $pending_courses;
                        $pending_array[] = $course;
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => "Payment history!",
                    'payment_records' => $this->paginate($pending_array, $request->per_page ?? 10),
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Something went wrong",
                ], 400);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "payment param required",
            ], 400);
        }
    }

    public function pending_payment_details(Request $request)
    {
        $rules = [
            'transaction_id' => 'required|string',
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

        $records = TeacherPayment::with('course.course_order', 'course.subject')
            ->where('payment_status', 'pending')
            ->where('transaction_id', $request->transaction_id)
            ->where('user_id', $token_user->id)
            ->get();

        $payment_records = $records->groupBy('transaction_id');

        $pending_array = [];
        if (count($payment_records) > 0) {
            foreach ($payment_records as $payment_record) {

                // // $course = new stdClass();
                // $transaction_id = $payment_record[0]->transaction_id;
                // $course_month = Carbon::parse($payment_record[0]->created_at)->format('M Y');
                // $course_status =  'pending';

                $dispute_payment = TeacherPayment::where('transaction_id', $request->transaction_id)
                    ->where('is_dispute', '1')
                    ->first();

                if ($dispute_payment == '') {
                    $request_payment =  1;
                } else {
                    $request_payment =  0;
                }


                $courses = $payment_record->unique('course_id');

                $pending_courses = [];
                $total_classes = 0;
                $overall_amount = 0;

                foreach ($courses as $record) {
                    $completed_classes = AcademicClass::with('course.subject')->where('course_id', $record->course_id)
                        ->where('status', 'completed')
                        ->where('payment_status', 'in_progress')
                        ->get();

                    $total_hours =  $completed_classes->sum('duration');
                    $total_amount = $total_hours * $completed_classes[0]['course']['subject']->price_per_hour;

                    $course1 = new stdClass();
                    $course1->course_id = $record->course_id;
                    $course1->order_id = $record['course_order']->booking_id ?? '';
                    $course1->course_code = $record['course']->course_code;
                    $course1->course_name = $record['course']->course_name;
                    $course1->completion_date = Carbon::parse($record['course']->end_date)->format('d M Y');
                    $course1->price_per_hour = $record['course']['subject']->price_per_hour;
                    $course1->completed_classes = count($completed_classes);
                    $course1->total_hours = $total_hours;
                    $course1->total_amount = $total_amount;
                    $course1->is_dispute =  $record->is_dispute;
                    $course1->reason =  $record->reason;
                    $pending_courses[] = $course1;
                    $total_classes = $total_classes + count($completed_classes);
                    $overall_amount = $overall_amount + $total_amount;
                }

               
                $pending_array['request_payment']=$request_payment;
                $pending_array['total_classes']=$total_classes;
                $pending_array['total_amount']=$overall_amount;
                $pending_array['courses']=$pending_courses;
                // $course->total_classes = $total_classes;
                // $course->total_amount = $overall_amount;
                // $course->courses =  $pending_courses;
                // $pending_array[] = $course;
            }
        }

        return response()->json([
            'status' => true,
            'message' => "Pending payments details",
            // 'request_payment' => $request_payment,
            // 'transaction_id' => $payment_record[0]->transaction_id,
            'payment_records' => $pending_array,
        ]);
    }

    public function add_dispute(Request $request)
    {

        $rules = [
            'transaction_id' => 'required|string',
            'reason' => 'required|string',
            'courses' => 'required',
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

         $payments = TeacherPayment::where('transaction_id', $request->transaction_id)
            ->whereIn('course_id', $request->courses)
            ->get();

        if (count($payments) < 1) {
            return response()->json([
                'status' => false,
                'message' => "no record found with this transaction id",
            ], 400);
        }

        foreach ($payments as $payment) { 
            $payment->is_dispute = 1;
            $payment->reason = $request->reason;
            $payment->save();
        }

        $user = $token_user;
        if ($request->hasFile('file')) {
            $imageName = time() . '.' . $request->file->getClientOriginalExtension();
            $request->file->move(public_path('uploads/images'), $imageName);
            $file = $imageName;
        } else {
            $file = null;
        }

        $ticket = new DisputeTicket();
        $ticket->subject = 'Dispute Payment';
        $ticket->user_id =  $user->id;
        $ticket->course_ids =  json_encode($request->courses);
        $ticket->transaction_id =  $request->transaction_id;
        $ticket->dispute_id = strtoupper(Str::random(10));
        $ticket->priority = 'High';
        $ticket->message = 'Here you can contact with Admin regarding dispute, payment issue';
        $ticket->status = "Open";
        $ticket->file = $file;
        $ticket->save();



        // if ($request->hasFile('file')) {
        //     $imageName = time() . '.' . $request->file->getClientOriginalExtension();
        //     $request->file->move(public_path('uploads/images'), $imageName);
        //     $file = $imageName;
        // } else {
        $file = null;
        // }
        $comment = DisputeComment::create([
            'dispute_id' => $ticket->dispute_id,
            'user_id' => $user->id,
            'comment' => $request->reason,
            'file' => $file,
        ]);


        return response()->json([
            'status' => true,
            'message' => "Dispute added successfully",
        ]);
    }

    public function dispute_reason(Request $request)
    {

        $rules = [
            'transaction_id' => 'required|string',
            'course_id' => 'required|integer',
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

        $payment = TeacherPayment::where('transaction_id', $request->transaction_id)
            ->where('course_id', $request->course_id)
            ->select('id', 'course_id', 'transaction_id', 'reason')
            ->first();

        return response()->json([
            'status' => true,
            'message' => "Dispute reason",
            'payment' => $payment,
        ]);
    }


    public function dispute_details(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $rules = [
            'transaction_id' => 'required|string',
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

        $ticket = DisputeTicket::with('user', 'dispute_comments.user')
            ->where('user_id', $token_user->id)
            ->where('transaction_id', $request->transaction_id)
            ->first();

        if(!$ticket){
            return response()->json([
                'status' => 'false',
                'errors' => 'no records found with this transaction id',
            ], 400);
        }


        $latest_comment = DisputeComment::where('dispute_id',$ticket->dispute_id)->latest()->first();
        if ($latest_comment) {
            $ticket->last_reply = $latest_comment->created_at;
        } else {
            $ticket->last_reply = null;
        }
        // unset($ticket['dispute_comments']);

        return response()->json([
            'status' => true,
            'message' => "Dispute details",
            'dispute' => $ticket,
        ]);
    }

    public function dispute_comment(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'dispute_id' => 'required',
            'comment' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([

                'status' => 'false',
                'errors' => $errors,
            ], 400);
        }
        $ticket = DisputeTicket::where('dispute_id', $request->dispute_id)->first();
        if ($ticket == '') {
            return response()->json([
                'status' => false,
                'message' => trans('Invalid dispute id'),
            ], 400);
        }

        if ($ticket->status == "Closed") {
            return response()->json([
                'status' => 'false',
                'message' => trans('api_messages.TICKET_CLOSED_U_NOT_ADD_COMMENT'),
            ], 400);
        }


        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $user = $token_user;
        if ($request->hasFile('file')) {
            $imageName = time() . '.' . $request->file->getClientOriginalExtension();
            $request->file->move(public_path('uploads/images'), $imageName);
            $file = $imageName;
        } else {
            $file = null;
        }
        $comment = DisputeComment::create([
            'dispute_id' => $request->dispute_id,
            'user_id' => $user->id,
            'comment' => $request->comment,
            'file' => $file,
        ]);

        $ticket->updated_at = Carbon::now();
        $ticket->update();

        return response()->json([
            'message' => true,
            'status' => "Reply has been submitted",
            'comment' => $comment,
        ]);
    }

    public function payment_request(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $rules = [
            'transaction_id' => 'required|string',
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

        $dispute_payment = TeacherPayment::where('transaction_id', $request->transaction_id)
            ->first();

        if ($dispute_payment->payment_status == 'completed') {
            return response()->json([
                'status' => false,
                'message' => 'Payment already transfered',
            ], 400);
        }

        if ($dispute_payment->payment_status == 'payment_requested') {
            return response()->json([
                'status' => false,
                'message' => 'Payment Inprogress. Please wait.',
            ], 400);
        }

        $dispute_payment = TeacherPayment::where('transaction_id', $request->transaction_id)
            ->where('is_dispute', '1')
            ->first();
        if ($dispute_payment == '') {
            $pending_payments = TeacherPayment::where('transaction_id', $request->transaction_id)
                ->where('payment_status', 'pending')
                ->get();

            if (count($pending_payments) > 0) {
                foreach ($pending_payments as  $pending_payment) {
                    $pending_payment->payment_status = 'payment_requested';
                    $pending_payment->save();
                    $acadamic_class =  DB::table('academic_classes')
                        ->where('id', $pending_payment->academic_class_id)
                        ->update(['payment_status' => 'payment_requested']);;
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Your payment request has been submitted',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No record found',
                ], 400);
            }
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Please resolve all the disputes first',
            ], 400);
        }
    }

    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }
}
