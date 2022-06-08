<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Billing\HyperPayBilling;
use App\Events\OrderEvent;
use App\Models\AcademicClass;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Program;
use App\Subject;
use Illuminate\Support\Str;
use Devinweb\LaravelHyperpay\Facades\LaravelHyperpay;
use JWTAuth;
use Devinweb\LaravelHyperpay\Events\SuccessTransaction;

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
            'resourcePath' => "required",
            'id' => "required",
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

        $resourcePath = $request->get('resourcePath');
        $checkout_id = $request->get('id');
        $payment_details = LaravelHyperpay::paymentStatus($resourcePath, $checkout_id);

        $paymentDetails = json_decode(json_encode($payment_details));

        if ($paymentDetails->original->transaction_status == 'success') {

            $rules = [
                'field_of_study' =>  'required',

                'language_id' =>  'required',
                // 'book_type' =>  'required',
                'book_info' =>  'required',
                'total_classes' =>  'required',
                'total_hours' =>  'required',
                'total_price' =>  'required',
                'subject_id' =>  'required',
                'weekdays' =>  'required',

                'teacher_id' =>  'required',
                'start_date' =>  'required',
                'end_date' =>  'required',
                'start_time' =>  'required',
                'end_time' =>  'required',
                'class_type' =>  'required',
                'classes' =>  'required',

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

            if ($request->program_id == 3) {
                $request->validate([
                    'country_id' =>  'required',
                ]);
            }

            //*********** Saving records to Courses table ***********
            $course = new Course();
            if ($request->program_id == 3) {
                $course->program_id = $request->program_id;
                $course->country_id = $request->country_id;
            } else {
                $course->program_id = $request->program_id;
            }
            if ($request->book_info == 2) {
                $request->validate([
                    'files' =>  'required',
                ]);
                $course->book_info = $request->book_info;
                if ($request->hasFile('files')) {
                    //************* book files **********\\
                    $images = array();
                    $files = $request->file('files');
                    foreach ($files as $file) {
                        $imageName = date('YmdHis') . random_int(10, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('assets/images/class_files'), $imageName);
                        $images[] = $imageName;
                    }
                    $course->files = implode("|", $images);
                    //************* book files ends **********\\
                }
            }
            if ($request->book_info == 3) {
                $request->validate([
                    'book_name' =>  'required',
                    'book_edition' =>  'required',
                    'book_author' =>  'required',
                ]);
                $course->book_info = $request->book_info;
                $course->book_name = $request->book_name;
                $course->book_edition = $request->book_edition;
                $course->book_author = $request->book_author;
            } else {
                $course->book_info = $request->book_info;
            }

            $token_1 = JWTAuth::getToken();
            $token_user = JWTAuth::toUser($token_1);

            $course->field_of_study = $request->field_of_study;

            $course->language_id = $request->language_id;
            // $course->book_type = $request->book_type;
            $course->total_price = $request->total_price;
            $course->total_hours = $request->total_hours;
            $course->total_classes = $request->total_classes;
            $course->subject_id = $request->subject_id;
            $course->teacher_id = $request->teacher_id;
            $course->student_id = $token_user->id;
            $course->weekdays = $request->weekdays;
            $course->start_date = $request->start_date;
            $course->end_date = $request->end_date;
            $course->start_time = $request->start_time;
            $course->end_time = $request->end_time;
            $course->status = 'pending';
            $course->save();

            $classes = $request->classes;
            $classes = json_decode($classes);
            foreach ($classes as $session) {
                $class = new AcademicClass();
                $class->course_id = $course->id;
                $class->teacher_id = $request->teacher_id;
                $class->student_id = $token_user->id;
                $class->start_date = $session->date;
                $class->end_date = $request->end_date;
                $class->start_time = $session->start_time;
                $class->end_time = $session->end_time;
                $class->class_type = $request->class_type;
                $class->duration = $session->duration;
                $class->day = $session->day;
                $class->status = 'pending';
                $class->save();
            }
            $program = Program::find($request->program_id);
            $subject = Subject::find($request->subject_id);
            $course_count = Course::where('subject_id', $subject->id)->where('program_id', $request->program_id)->where('course_code', '!=', null)->latest()->first();
            if ($course_count != null) {
                $course_count = substr($course_count->course_code, 7) + 1;
            } else {
                $course_count = 1;
            }



            $course = Course::with('subject', 'language', 'field', 'teacher', 'program')->find($course->id);
            $course->course_code = $program->code . '-' . Str::limit($subject->name, 3, '') . '-' . ($course_count);

            if ($course_count == null) {
                $course->course_name = $subject->name . "0001";
            } else {
                $course->course_name = $subject->name . "000" . $course_count;
            }


            $course->update();

            $classRoom = new ClassRoom();
            $classRoom->course_id = $course->id;
            $classRoom->teacher_id = $request->teacher_id;
            $classRoom->student_id = $token_user->id;
            $classRoom->status = 'pending';
            $classRoom->save();

            $user = User::find($token_user->id);
            $teacher = User::find($request->teacher_id);

            // Event notification
            $teacher_message = 'New Course Created!';
            $student_message = 'Course Created Successfully!';

            // event(new NewCourse($course, $course->teacher_id, $teacher_message, $teacher));
            // event(new NewCourse($course, $course->student_id, $student_message, $user));

        } else {
            return response()->json([
                'status' => false,
                'message' => "Payment details!",
                'payment_details' => $payment_details,
            ], 422);
        }

        $course = Course::findOrFail($course->id);
        event(new OrderEvent($course->id, $course->student_id, $paymentDetails->original->merchantTransactionId));
        // return view('payment_status', compact('payment_details'));

        return response()->json([
            'status' => true,
            'message' => "Payment details!",
            'payment_details' => $payment_details,
            'course' => $course,
        ]);
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
        return $responseData;
    }

    public function debit_request(Request $request)
    {
        $url = "https://test.oppwa.com/v1/payments";
        $data = "entityId=8ac7a4ca80b2d4470180b3d5cdf604c6" .
            "&amount=52.00" .
            "&currency=USD" .
            "&paymentBrand=GIROPAY" .
            "&paymentType=DB" .
            "&bankAccount.bic=TESTDETT421" .
            "&bankAccount.iban=DE14940593100000012346" .
            "&bankAccount.country=DE" .
            "&shopperResultUrl=https://gate2play.docs.oppwa.com/tutorials/server-to-server";

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
        return $responseData;
    }
}
