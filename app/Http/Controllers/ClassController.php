<?php

namespace App\Http\Controllers;

use App\Country;
use App\Events\CourseBooked;
use App\Events\NewCourse;
use App\FieldOfStudy;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\ClassSession;
use App\Models\Feedback;
use App\Models\NoTeacherCourse;
use App\Models\RescheduleClass;
use App\Models\UserFeedback;
use App\Models\RequestedCourse;
use App\User;
use App\Program;
use App\Subject;
use App\TimeZone;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JWTAuth;
use Stevebauman\Location\Facades\Location;
use Devinweb\LaravelHyperpay\Events\SuccessTransaction;
use App\Billing\HyperPayBilling;
use App\Events\ClassStartedEvent;
use App\Events\CourseBookingEvent;
use App\Events\NoTeacherEvent;
use App\Events\RequestCourseEvent;
use App\Jobs\ClassStartedJob;
use App\Jobs\CourseBookingJob;
use App\Jobs\NoTeacherJob;
use App\Jobs\RequestCourseJob;
use App\Models\HighlightedTopic;
use App\Models\LastActivity;
use App\Models\RejectedCourse;
use App\TeacherAvailability;
use App\TeacherProgram;
use App\TeacherSubject;
use App\TeachingSpecification;
use Devinweb\LaravelHyperpay\Facades\LaravelHyperpay;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use PragmaRX\Countries\Package\Countries;
use stdClass;

class ClassController extends Controller
{
    //********* Create Course *********
    public function create_course(Request $request)
    {
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

            // 'teacher_id' =>  'required',
            'start_date' =>  'required',
            'end_date' =>  'required',
            'start_time' =>  'required',
            'end_time' =>  'required',
            'class_type' =>  'required',
            'classes' =>  'required',
            // 'highlighted_topics' =>  'required',

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

        // Adding Course Data
        $course = new Course();
        if ($request->program_id == 3) {
            $course->program_id = $request->program_id;
            $course->country_id = $request->country_id;
        } else {
            $course->program_id = $request->program_id;
        }
        if ($request->book_info == 2) {
            $request->validate([
                'file' =>  'required',
            ]);
            $course->files = $request->file; 
            
            
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

        if ($request->total_hours < 0.5) {
            return response()->json([
                'status' => false,
                'message' => "Course should have atleast 30 minutes duration!",
            ], 400);
        }

        //**************** Availabilities and checkues for course booking ****************
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);


        // if ($start_date < Carbon::now()) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => "Please select another date!",
        //     ], 400);
        // } else {
        //     if ($start_date->diffInHours(Carbon::now()) < 24) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => "Please maintain a 24 hour gap!",
        //         ], 400);
        //     }
        // }

        $teacher_specification = TeachingSpecification::where('user_id', $request->teacher_id)->first();

        // if ($end_date > $teacher_specification->availability_end_date) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => "Teacher Availability date Exceeded!",
        //     ], 400);
        // }


        $availability_start = Carbon::parse($teacher_specification->availability_start_date);
        $availability_end = Carbon::parse($teacher_specification->availability_start_date);


        //If Request has teacher id
        // if ($request->has('teacher_id')) {
        //     //Checking teacher Availability
        //     $days = explode(",", $request->weekdays);

        //     $teacher_availabilities = TeacherAvailability::whereIn('day', $days)
        //         ->where('user_id', $request->teacher_id)
        //         ->get();

        //     $db_days = $teacher_availabilities->pluck('day')->unique();
        //     //if Db days are not equal to requested weekdays
        //     if (count($db_days) != count($days)) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => "Teacher is not available at this day!",
        //         ], 400);
        //     }
        //     //If teacher has no availability at requested days.
        //     if (count($teacher_availabilities) == 0) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => "Teacher is not available at this day!",
        //         ], 400);
        //     }

        //     //Checking availability time
        //     foreach (json_decode($request->classes) as $requested_class) {
        //         $start_time = $requested_class->start_time;
        //         $end_time = $requested_class->end_time;
        //         $check_time = TeacherAvailability::where('day', $requested_class->day)
        //             ->where('user_id', $request->teacher_id)->where(function ($q) use ($start_time, $end_time) {
        //                 $q->whereBetween('time_from', [$start_time, $end_time])
        //                     ->where('time_to', "<=",  $end_time);
        //             })->get();
        //         if (count($check_time) == 0) {
        //             return response()->json([
        //                 'status' => false,
        //                 'message' => "Teacher not available on this time!",
        //             ], 400);
        //         }
        //     }


        //     //getting db courses on requested start_date and end_date
        //     $teacher_courses = Course::with('classes')->where('teacher_id', $request->teacher_id)
        //         ->where(function ($q) use ($start_date, $end_date) {
        //             $q->whereBetween('start_date', [$start_date, $end_date])
        //                 ->orWhereBetween('end_date', [$start_date, $end_date]);
        //         })->get();

        //     //Adding all classes of coursses in classes array
        //     $classes = [];
        //     $classes_id = [];
        //     foreach ($teacher_courses as $db_course) {
        //         foreach ($db_course['classes'] as $class) {
        //             array_push($classes, $class->start_date); #pushing classes start dates
        //             array_push($classes_id, $class->id); #pushing classes id's
        //         }
        //     }

        //     //Requested classes loop
        //     foreach (json_decode($request->classes) as $req_class) {
        //         // finding the requested class in array
        //         if (in_array($req_class->date, $classes)) {
        //             // echo "classes found in db";
        //             $start_time = $req_class->start_time;
        //             $end_time = $req_class->end_time;
        //             $db_classes = AcademicClass::wherein('id', $classes_id)
        //                 ->where('status', '!=', "completed")
        //                 ->where(function ($q) use ($start_time, $end_time) {
        //                     $q->whereBetween('start_time', [$start_time, $end_time])
        //                         ->orWhereBetween('end_time', [$start_time, $end_time]);
        //                 })
        //                 ->get();
        //             // if database has the classes on the requested time of class
        //             if (count($db_classes) != 0) {
        //                 return response()->json([
        //                     'status' => false,
        //                     'message' => "Teacher Already have classes at that time!",
        //                 ], 400);
        //             }
        //         }
        //     }
        // }
        //**************** Availabilities and checkues for course booking ends ****************

        $course->field_of_study = $request->field_of_study;

        $course->language_id = $request->language_id;
        // $course->book_type = $request->book_type;
        $course->total_price = $request->total_price;
        $course->total_hours = $request->total_hours;
        $course->total_classes = $request->total_classes;
        $course->subject_id = $request->subject_id;
        //if request has teacher id
        if ($request->has('teacher_id')) {
            $course->teacher_id = $request->teacher_id;
        }
        $course->student_id = $token_user->id;
        $course->weekdays = $request->weekdays;
        $course->start_date = $request->start_date; 
        $course->end_date = $request->end_date;
        $course->start_time = $request->start_time;
        $course->end_time = $request->end_time;
        $course->status = 'payment_pending';     
        $course->save(); 

        //Adding Academic Classes data
        $classes = $request->classes;
        // $classes = json_decode($classes); 
        foreach ($classes as $session) {  
              
            $class = new AcademicClass();
            $class->course_id = $course->id;
            if ($request->has('teacher_id')) {
                $class->teacher_id = $request->teacher_id; 
            }
            $class->student_id = $token_user->id;
            $class->start_date = $session['date'];
            $class->end_date = $request->end_date;
            $class->start_time = $session['start_time'];
            $class->end_time = $session['end_time'];
            $class->class_type = $request->class_type;
            $class->duration = $session['duration'];
            $class->day = $session['day'];
            $class->status = 'pending';
            $class->save();
        }

        $program = Program::find($request->program_id);
        $subject = Subject::find($request->subject_id);
        $course_count = Course::where('subject_id', $subject->id)->where('program_id', $request->program_id)->where('course_code', '!=', null)->count();
        if ($course_count != null) {
            $course_count =  $course_count + 1;
        } else {
            $course_count = 1;
        }


        $course = Course::with('subject.country', 'language', 'field', 'teacher', 'program')->find($course->id);

        // Adding Course Code
        $course->course_code = $program->code . '-' . Str::limit($subject->name, 3, '') . '-' . ($course_count);

        // Adding Course name
        if ($course_count == 0) {
            $course->course_name = $subject->name . "0001";
        } else {
            //Course name conditions
            $number = strlen($course_count);
            if ($number == 1) {
                $course->course_name = $subject->name . "-" . "000" . $course_count;
            } elseif ($number == 2) {
                $course->course_name = $subject->name . "-" . "00" . $course_count;
            } elseif ($number == 3) {
                $course->course_name = $subject->name . "-" . "0" . $course_count;
            } else {
                $course->course_name = $subject->name . "-" . $course_count;
            }
        }
        $course->update();

        //Adding Classroom Data
        $classRoom = new ClassRoom();
        $classRoom->course_id = $course->id;
        if ($request->has('teacher_id')) {
            $classRoom->teacher_id = $request->teacher_id;
        }

        $classRoom->student_id = $token_user->id;
        $classRoom->status = 'payment_pending';
        $classRoom->save();

        //Adding Highlighted topic data
        if ($request->has('highlighted_topics')) {
            foreach ($request->highlighted_topics as $topic) { 
              
                $topicc = new HighlightedTopic();
                $topicc->name = $topic['name'];
                $topicc->confidence_scale = $topic['knowledge_scale'];
                $topicc->course_id = $course->id;
                $topicc->save();
            }
        }

        $user = User::find($token_user->id);
        if ($request->has('teacher_id')) {
            $teacher = User::find($request->teacher_id);
            // Event notification
            $teacher_message = 'New Course Created!';
            $student_message = 'Course Created Successfully!';

            event(new CourseBookingEvent($course->teacher_id, $teacher, $teacher_message,  $course));
            event(new CourseBookingEvent($course->student_id, $user, $student_message,  $course));
            dispatch(new CourseBookingJob($course->teacher_id, $teacher, $teacher_message, $course));
            dispatch(new CourseBookingJob($course->student_id, $user, $student_message, $course));
        } else {
            $noTeacher = new NoTeacherCourse();
            $noTeacher->course_id = $course->id;
            $noTeacher->save();
            $admin = User::where('role_name', 'admin')->first();

            // Event notification
            $admin_message = 'New Course Created! Kindly assign teacher!';
            $student_message = 'Course Created Successfully!';
            event(new NoTeacherEvent($admin->id, $admin, $admin_message,  $course));
            event(new NoTeacherEvent($course->student_id, $user, $student_message, $course));
            dispatch(new NoTeacherJob($admin->id, $admin, $admin_message, $course));
            dispatch(new NoTeacherJob($course->student_id, $user, $student_message,  $course));
        }

        $trackable_data = [
            'course_id' => $course->id,
            'course_code' => $course->course_code
        ];
        $user = User::findOrFail($token_user->id);
        $amount = $request->total_price;
        $brand = 'VISA'; // MASTER OR MADA

        $id = Str::random('64');
        $payment = LaravelHyperpay::addMerchantTransactionId($id)
            ->addBilling(new HyperPayBilling())
            ->checkout($trackable_data, $user, $amount, $brand, $request);

        $payment = json_decode(json_encode($payment));
        $script_url = $payment->original->script_url;
        $shopperResultUrl = $payment->original->shopperResultUrl;
        $redirect_url = $request->redirect_url;
        // return view('payment_form', compact('script_url', 'shopperResultUrl'));
        return response()->json([
            'status' => true,
            'message' => "Checkout prepared successfully!",
            'script_url' => $script_url,
            'shopperResultUrl' => $redirect_url . "?course_id=" . $course->id,
            'course' => $course
        ]);
    }


    public function add_classes(Request $request)
    {
        $rules = [

            'course_id' =>  'required',
            'total_classes' =>  'required',
            'total_hours' =>  'required',
            'total_price' =>  'required',
            // 'start_date' =>  'required',
            // 'end_date' =>  'required',
            // 'start_time' =>  'required',
            // 'end_time' =>  'required',
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


        // $token_1 = JWTAuth::getToken();
        // $token_user = JWTAuth::toUser($token_1);

        // $course = Course::find($request->course_id);
        // $trackable_data = [
        //     'course_id' => $course->id,
        //     'course_code' => $course->course_code
        // ];

        // //Adding hours and price to Courses table
        // $course->total_classes = $course->total_classes + $request->total_classes;
        // $course->total_price = $course->total_price + $request->total_price;
        // $course->total_hours = $course->total_hours + $request->total_hours;
        // $counter = count($course['classes']) + 1;

        // $classes = json_decode(json_encode($request->classes));
        // $classes_array = [];
        // foreach ($classes as $reqClass) {
        //     $academicClass = new AcademicClass();
        //     $academicClass->start_date = $reqClass->date;
        //     $academicClass->end_date = $reqClass->date;
        //     $academicClass->day = $reqClass->day;
        //     $academicClass->start_time = $reqClass->start_time;
        //     $academicClass->end_time = $reqClass->end_time;
        //     $academicClass->duration = $reqClass->duration;
        //     $academicClass->status = 'pending_payment';
        //     $academicClass->student_id = $token_user->id;
        //     $academicClass->teacher_id = $course->teacher_id;
        //     $academicClass->class_type = $course->classes[0]->class_type;
        //     $academicClass->course_id = $course->id;
        //     $academicClass->class_paradigm = "added";
        //     $academicClass->save();
        //     array_push($classes_array, $academicClass->id);
        // }

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $course = Course::find($request->course_id);
        $trackable_data = [
            'course_id' => $course->id,
            'course_code' => $course->course_code
        ];

        $course = Course::with('classes')->find($request->course_id);
        if ($course->status == 'completed') {
            return response()->json([
                'status' => false,
                'message' => 'Course has been completed!',
            ], 400);
        }

        //Teacher Day Availability and requested classes Availability
        $availability = TeacherAvailability::where('user_id', $course->teacher_id)->get();
        $requestedClasses = json_decode(json_encode($request->classes));
        foreach ($requestedClasses as $class) {
            $weekAvailabilities = $availability->where('day', $class->day);
            if (count($weekAvailabilities) == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher not available at this day! please check teacher availability first',
                ], 400);
            }
            $flag = 0;
            foreach ($weekAvailabilities as $weekAvailability) {
                $start_time = Carbon::parse($weekAvailability->time_from)->format('G:i');
                $end_time = Carbon::parse($weekAvailability->time_to)->format('G:i');
                $request_startTime = Carbon::parse($class->start_time)->format('G:i');
                $request_endTime = Carbon::parse($class->end_time)->format('G:i');

                if (($request_startTime >= $start_time) && ($request_startTime <= $end_time) && ($request_endTime >= $start_time) && ($request_endTime <= $end_time)) {
                    break;
                } else {
                    $flag++;
                    if ($flag == count($weekAvailabilities))
                        return response()->json([
                            'status' => false,
                            'message' => 'Teacher not available at this time slot!! please check teacher availability first',
                        ], 400);
                }
            }
            // Checking if teacher is booked
            $classes = AcademicClass::where('day', $class->day)->where('teacher_id', $course->teacher_id)
                ->where('start_date', $class->date)
                ->get();
            if (count($classes) > 0) {
                $flag = 0;
                foreach ($classes as $academicClass) {
                    $start_time = Carbon::parse($academicClass->start_time)->format('G:i');
                    $end_time = Carbon::parse($academicClass->end_time)->format('G:i');
                    $request_startTime = Carbon::parse($class->start_time)->format('G:i');
                    $request_endTime = Carbon::parse($class->end_time)->format('G:i');

                    if (($request_startTime >= $start_time) && ($request_startTime <= $end_time) || ($request_endTime >= $start_time) && ($request_endTime <= $end_time)) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Teacher is not available on this slot! please check teacher availability first',
                        ], 400);
                    }
                }
            }
        }

        $course->total_classes = $course->total_classes + $request->total_classes;
        $course->total_price = $course->total_price + $request->total_price;
        $course->total_hours = $course->total_hours + $request->total_hours;
        $counter = count($course['classes']) + 1;
        $classes_array = [];
        foreach ($requestedClasses as $reqClass) {
            $academicClass = new AcademicClass();
            $academicClass->start_date = $reqClass->date;
            $academicClass->end_date = $reqClass->date;
            $academicClass->day = $reqClass->day;
            $academicClass->start_time = $reqClass->start_time;
            $academicClass->end_time = $reqClass->end_time;
            $academicClass->duration = $reqClass->duration;
            $academicClass->status = 'pending';
            $academicClass->student_id = $token_user->id;
            $academicClass->teacher_id = $course->teacher_id;
            $academicClass->class_type = $course->classes[0]->class_type;
            $academicClass->course_id = $request->course_id;
            $academicClass->class_paradigm = "added";
            $academicClass->save();
            array_push($classes_array, $academicClass->id);
            // Braincert Curl Implementation

            $apiURL = 'https://api.braincert.com/v2/schedule';
            $postInput = [
                'apikey' =>  "xKUyaLJHtbvBUtl3otJc",
                'title' =>  'class' . $counter,
                'timezone' => 90,
                'start_time' => Carbon::parse($academicClass->start_time)->format('G:i a'),
                'end_time' => Carbon::parse($academicClass->end_time)->format('G:i a'),
                'date' => Carbon::parse($academicClass->start_date)->format('Y-m-d'),
                'currency' => "USD",
                'ispaid' => null,
                'is_recurring' => 0,
                'repeat' => 1,
                'weekdays' => $reqClass->day,
                'end_date' => Carbon::parse($academicClass->end_date)->format('Y-m-d'),
                'seat_attendees' => null,
                'record' => 1,
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
            $responseBody = json_decode($response->getBody(), true);
            if ($responseBody['status'] == "ok") {
                $academicClass->title = 'class' . $counter;
                $academicClass->lesson_name = 'lesson' . $counter;
                $academicClass->class_id = $responseBody['class_id'];
                $academicClass->status = "scheduled";
                $course->status = "active";
                $course->update();
                $academicClass->save();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $responseBody['error'],
                ], 400);
            }
            $counter++;
        }
        $course->update();

        $course = Course::with('classes')->find($request->course_id);

        //Hyper Pay Implementation
        $user = User::findOrFail($token_user->id);
        $amount = $request->total_price;
        $brand = 'VISA'; // MASTER OR MADA

        $id = Str::random('64');

        $payment = LaravelHyperpay::addMerchantTransactionId($id)
            ->addBilling(new HyperPayBilling())
            ->checkout($trackable_data, $user, $amount, $brand, $request);

        $payment = json_decode(json_encode($payment));
        $script_url = $payment->original->script_url;
        $shopperResultUrl = $payment->original->shopperResultUrl;
        $redirect_url = $request->redirect_url;
        $course = Course::find($request->course_id);
        $classes =  AcademicClass::whereIn('id', $classes_array)->get();
        // return view('payment_form', compact('script_url', 'shopperResultUrl'));
        return response()->json([
            'status' => true,
            'message' => "Checkout prepared successfully!",
            'script_url' => $script_url,
            'shopperResultUrl' => $redirect_url . "?course_id=" . $course->id . "&classes=" . json_encode($classes_array),
            'course' => $course,
            'classes' => $classes,
        ]);
    }

    public function course_payment_retry(Request $request)
    {

        $rules = [

            'course_id' =>  'required',
            // 'total_price' =>  'required',
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

        $course = Course::find($request->course_id);
        $trackable_data = [
            'course_id' => $course->id,
            'course_code' => $course->course_code
        ];


        $user = User::findOrFail($token_user->id);
        $amount = $course->total_price;
        $brand = 'VISA'; // MASTER OR MADA

        $id = Str::random('64');
        $payment = LaravelHyperpay::addMerchantTransactionId($id)->addBilling(new HyperPayBilling())->checkout($trackable_data, $user, $amount, $brand, $request);
        $payment = json_decode(json_encode($payment));
        $script_url = $payment->original->script_url;
        $shopperResultUrl = $payment->original->shopperResultUrl;
        $redirect_url = $request->redirect_url;
        // return view('payment_form', compact('script_url', 'shopperResultUrl'));
        return response()->json([
            'status' => true,
            'message' => "Checkout prepared successfully!",
            'script_url' => $script_url,
            'shopperResultUrl' => $redirect_url . "?course_id=" . $course->id,
            'course' => $course
        ]);
    }


    public function view_class(Request $request, $class_id)
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $class = AcademicClass::find($class_id);



        /// Curl Implementation
        $apiURL = 'https://api.braincert.com/v2/getclassrecording';
        $postInput = [
            'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
            'class_id' =>  $class->class_id,
        ];

        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);

        // return $responseBody;

        if (isset($responseBody['status']) && $responseBody['status'] == "error") {

            return response()->json([
                'success' => false,
                'message' => $responseBody['error'],
            ]);
        } elseif (isset($responseBody['Recording'])) {

            return response()->json([
                'success' => false,
                'message' => $responseBody['Recording'],
            ], 400);
        } elseif (isset($responseBody[0]['status']) && $responseBody[0]['status'] == "0") {

            return response()->json([
                'success' => true,
                'message' => "Recording is available",
                'url' => $responseBody[0]['record_path'],
            ]);
        }
    }

    //*********/ Deleting class session *********
    public function request_course(Request $request)
    {
        $rules = [
            'program_id' =>  'required',
            'subject' =>  'required',
            'gender_preference' =>  'required',
            'language_preference' =>  'required',
            'course_description' =>  'required',
            'student_name' =>  'required',
            'email' =>  'required',
        ];

        if ($request->program_id == 3) {
            $rules['country_id'] = 'required';
            $rules['grade'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ], 400);
        }

        $requestedCourse = new RequestedCourse();
        $requestedCourse->program_id = $request->program_id;
        $requestedCourse->country_id = $request->country_id;
        $requestedCourse->grade = $request->grade;
        $requestedCourse->subject = $request->subject;
        $requestedCourse->gender_preference = $request->gender_preference;
        $requestedCourse->language_preference = $request->language_preference;
        $requestedCourse->course_description = $request->course_description;
        $requestedCourse->student_name = $request->student_name;
        $requestedCourse->email = $request->email;
        $requestedCourse->save();

        $admin = User::where('role_name', 'admin')->first();

        // event(new RequestCourseEvent($requestedCourse, $requestedCourse->email, "Course Request Sent Successfully!"));
        // event(new RequestCourseEvent($requestedCourse, $admin->email, "Course Request Sent Successfully!"));
        // dispatch(new RequestCourseJob($requestedCourse, $requestedCourse->email, "Course Request Sent Successfully!"));
        // dispatch(new RequestCourseJob($requestedCourse, $admin->email, "Course Request Sent Successfully!"));

        return response()->json([
            'success' => true,
            'message' => "Your request has been submitted. Our team will contact you soon!",
        ]);
    }

    public function del_session(Request $request)
    {
        $rules = [
            'session_id' =>  'required',
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

        $session = ClassSession::find($request->session_id);
        $session->delete();
        return response()->json([
            'success' => true,
            'message' => "Session has been deleted!",
            'deleted_session' => $session,
        ]);
    }

    //********* Updating class session *********
    public function update_session(Request $request)
    {
        $rules = [
            'session_id' =>  'required',
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

        $session = ClassSession::find($request->session_id);
        $session->day = $request->day;
        $session->date = $request->date;
        $session->start_time = $request->start_time;
        $session->end_time = $request->end_time;
        $timeDifference = Carbon::parse($request->start_time)->diffInMinutes(Carbon::parse($request->end_time));
        $session->total_hours = round($timeDifference / 60, 2);
        $session->update();


        return response()->json([
            'success' => true,
            'message' => "Session updated succesfully!",
            'session' => $session,
        ]);
    }

    //*********/ View Teacher Profile *********
    public function teacher_profile(Request $request)
    {
        // return $request;
        $teacher_id = $request->id;
        $average_feedback = 0;

        $teacher = User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'kudos_points')
            ->with('teacher_qualification', 'teacher_specification', 'spoken_languages', 'teacher_subjects.country')
            ->with(['feedbacks' => function ($query) use ($teacher_id) {
                $query->where('receiver_id', $teacher_id);
            }])
            // ->with('feedbacks.user')
            ->find($teacher_id);

        $total_feedbacks = UserFeedback::where('receiver_id', $teacher_id)->count();
        if ($total_feedbacks > 0) {
            $total_rating = UserFeedback::where('receiver_id', $teacher_id)->sum('rating');
            $average_feedback = $total_rating / $total_feedbacks;
        }

        $students_teaching = Course::where('teacher_id', $teacher_id)->count();
        $courses_created = Course::where('teacher_id', $teacher_id)->count();

        $expert_rating = 0;
        $expert_rating_total = UserFeedback::where('feedback_id', 1)->where('receiver_id', $teacher_id)->count();
        if ($expert_rating_total > 0) {
            $expert_rating_sum = UserFeedback::where('feedback_id', 1)->where('receiver_id', $teacher_id)->sum('rating');
            $expert_rating = $expert_rating_sum / $expert_rating_total;
        }

        $complexity_rating = 0;
        $complexity_rating_total = UserFeedback::where('feedback_id', 2)->where('receiver_id', $teacher_id)->count();
        if ($complexity_rating_total > 0) {
            $complexity_rating_sum = UserFeedback::where('feedback_id', 2)->where('receiver_id', $teacher_id)->sum('rating');
            $complexity_rating = $complexity_rating_sum / $complexity_rating_total;
        }

        $skillfull_rating = 0;
        $skillfull_rating_total = UserFeedback::where('feedback_id', 3)->where('receiver_id', $teacher_id)->count();
        if ($skillfull_rating_total > 0) {
            $skillfull_rating_sum = UserFeedback::where('feedback_id', 3)->where('receiver_id', $teacher_id)->sum('rating');
            $skillfull_rating = $skillfull_rating_sum / $skillfull_rating_total;
        }

        $onTime_rating = 0;
        $onTime_rating_total = UserFeedback::where('feedback_id', 4)->where('receiver_id', $teacher_id)->count();
        if ($onTime_rating_total > 0) {
            $onTime_rating_sum = UserFeedback::where('feedback_id', 4)->where('receiver_id', $teacher_id)->sum('rating');
            $onTime_rating = $onTime_rating_sum / $onTime_rating_total;
        }

        return response()->json([
            'success' => true,
            'teacher' => $teacher,
            'total_feedbacks' => $total_feedbacks,
            'average_rating' => $average_feedback,
            'students_teaching' => $students_teaching,
            'courses_created' => $courses_created,
            'expert_rating' => $expert_rating,
            'complexity_rating' => $complexity_rating,
            'skillfull_rating' => $skillfull_rating,
            'onTime_rating' => $onTime_rating,
        ]);
    }

    //*********/ Search Teacher *********
    public function search_tutor(Request $request)
    {

        $query = $request->search_query;
        $result = User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'created_at')
            ->with('teacher_subjects', 'teacher_subjects.subject.country', 'feedbacks')
            // ->whereHas('feedbacks',function($query){
            //     $query->
            // })
            ->where('role_name', '=', 'teacher')
            ->where('first_name', 'LIKE', "%$query%")
            ->orWhere('last_name', 'LIKE', "%$query%")
            ->orWhere('id', $query)
            ->orWhere('created_at', 'LIKE', "%$query%")
            ->get();

        $average_rating = 0;
        $teacher_id = $request->teacher_id;
        $total_feedbacks = UserFeedback::where('receiver_id', $teacher_id)->count();
        if ($total_feedbacks != 0) {
            $total_rating = UserFeedback::where('receiver_id', $teacher_id)->sum('rating');
            $average_rating = $total_rating / $total_feedbacks;
        }

        return response()->json([
            'success' => true,
            'teachers' => $result,
            // 'average_rating' => $average_rating,
        ]);
    }

    //*********/ Schedule Class *********
    public function schedule_class(Request $request)
    {
        $rules = [
            "academic_class_id" => "required",
            "title" => "required",
            "lesson_name" => "required",
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
        /// Curl Implementation
        $class = AcademicClass::find($request->academic_class_id);
        $course = Course::find($class->course_id);

        $apiURL = 'https://api.braincert.com/v2/schedule';
        $postInput = [
            'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
            'title' =>  $request->title,
            'timezone' => 90,
            'start_time' =>  Carbon::parse($class->start_time)->format('G:i a'),
            'end_time' => Carbon::parse($class->end_time)->format('G:i a'),
            'date' => Carbon::parse($class->start_date)->format('Y-m-d'),
            'currency' => "USD",
            'ispaid' => null,
            'is_recurring' => 0,
            'repeat' => 0,
            'weekdays' => $course->weekdays,
            'end_date' =>  Carbon::parse($class->end_date)->format('Y-m-d'),
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
        $responseBody = json_decode($response->getBody(), true);
        if ($responseBody['status'] == "ok") {
            $class->title = $request->title;
            $class->lesson_name = $request->lesson_name;
            $class->class_id = $responseBody['class_id'];
            $class->status = "scheduled";
            $course->status = "active";
            $course->update();
            $class->save();
            return response()->json([
                'success' => true,
                'message' => "Class scheduled successfully",
                'class' => $class,
            ]);
        } else {
            return $responseBody;
        }
    }

    //*********/ Start Class *********
    public function class_url(Request $request, $id)
    {

        $class = AcademicClass::find($id);
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($class == null) {
            return response()->json([
                'status' => false,
                'message' => 'Class not found'
            ], 400);
        }
        if ($class->status == 'canceled') {
            return response()->json([
                'status' => false,
                'message' => 'Class has been Canceled!'
            ], 400);
        }

        if ($class->teacher_id == null) {
            return response()->json([
                'status' => false,
                'message' => 'Teacher not assigned yet!'
            ], 400);
        }
        // return Carbon::parse($class->start_date);
        if (Carbon::today() < Carbon::parse($class->start_date)) {
            return response()->json([
                'status' => false,
                'message' => 'Your class is not scheduled today!'
            ], 400);
        }
        // If class tije is not met shows error
        if (Carbon::now() < Carbon::parse($class->start_time)) {
            return response()->json([
                'status' => false,
                'message' => 'Please Wait! Class has not started yet.'
            ], 400);
        }

        if ($token_user->role_name == "teacher") {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $apiURL = 'https://api.braincert.com/v2/getclasslaunch';
        $postInput = [
            'apikey' => 'xKUyaLJHtbvBUtl3otJc',
            'class_title' =>  $class->title,
            'class_id' => $class->class_id,
            'userId' => $token_user->id,
            'userName' => $token_user->first_name . " " . $token_user->last_name,
            'isTeacher' => $flag,
            'lessonName' => $class->lesson_name,
            'courseName' => "laravells",
        ];

        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody['status'] == 'error') {
            return response()->json([
                'status' => false,
                'message' => $responseBody['error'],
                'error' => $responseBody['error'],
            ], 400);
        } else {
            $class->status = "inprogress";
            $class->update();
            $course = Course::find($class->course_id);
            $course->status = "inprogress";
            $course->update();

            $classrooms = ClassRoom::where('course_id', $class->course_id)->get();
            if (count($classrooms) > 0) {
                foreach ($classrooms as $classroom) {
                    $classroom->status = "inprogress";
                    $classroom->save();
                }
            }

            // User Attendence starts
            $check_attd = Attendance::where('user_id', $token_user->id)->where('academic_class_id', $class->id)->count();
            if ($check_attd == 0) {
                $attendance = new Attendance();
                $attendance->user_id = $token_user->id;
                $attendance->academic_class_id = $class->id;
                $attendance->course_id = $course->id;
                $attendance->status = 'present';
                $attendance->save();
            }
            // User Attendence ends

            return response()->json([
                'status' => true,
                'class_url' => $responseBody['launchurl'],
            ]);
        }
    }

    //*********/ All Registered Students *********
    public function registered_students()
    {
        $students = User::withCount(['courses' => function ($q) {
            $q->where('status', 'completed');
        }])
            ->where("role_name", 'student')->get();

        return response()->json([
            'success' => true,
            'students' => $students,
        ]);
    }

    //*********/ All Registered Teachers *********
    public function registered_teachers()
    {
        $teachers = User::where("role_name", 'teacher')->get();
        return response()->json([
            'success' => true,
            'teachers' => $teachers,
        ]);
    }

    //*********/ Student Profile *********
    public function student_profile(Request $request)
    {
        $student_id = $request->id;
        $student = User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar', 'created_at')
            ->where('id',  $student_id)
            ->where('role_name', '=', 'student')
            ->first();
        // $total_courses = Course::where('user_id', $student_id)->count();

        return response()->json([
            'success' => true,
            'student' => $student,
            // 'total_courses' => $total_courses
        ]);
    }

    //*********/ Search Student *********
    public function search_student(Request $request)
    {
        $query = $request->search_query;
        $student = (User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'created_at')
            ->where('first_name', 'LIKE', "%$query%")
            ->orWhere('last_name', 'LIKE', "%$query%")
            ->orWhere('id', "%$query%")
            ->orWhere('created_at', 'LIKE', "%$query%")
            ->get()
        )->where('role_name', '=', 'student');

        return response()->json([
            'success' => true,
            'student' => $student,
        ]);
    }

    //*********/ Specific Class details *********
    public function class_detail(Request $request)
    {
        $rules = [
            "academic_class_id" => "required",
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

        $class = AcademicClass::with('student', 'teacher', 'course')->find($request->academic_class_id);
        return response()->json([
            'success' => true,
            'classes' => $class,
        ]);
    }

    //********* Classroom Dashboard *********
    public function courses(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($token_user->role_name == 'teacher') {
            $userrole = 'teacher_id';
        } elseif ($token_user->role_name == 'student') {
            $userrole = 'student_id';
        }

        if ($token_user->role_name == 'teacher') {
            // $courses = Course::where('teacher_id', $token_user->id)->get();
            $course_programs = TeacherSubject::where('user_id', $token_user->id)->distinct('program_id')->pluck('program_id');
            $programs = Program::whereIn('id', $course_programs)->get();
            $course_field_of_studies =  TeacherSubject::where('user_id', $token_user->id)->distinct('field_id')->pluck('field_id');
            $field_of_studies = FieldOfStudy::whereIn('id', $course_field_of_studies)->get();
        }
        if ($token_user->role_name == 'student') {
            $courses = Course::where('student_id', $token_user->id)->get();
            $course_programs = $courses->unique('program_id')->pluck('program_id');
            $programs = Program::whereIn('id', $course_programs)->get();
            $course_field_of_studies = $courses->unique('field_of_study')->pluck('field_of_study');
            $field_of_studies = FieldOfStudy::whereIn('id', $course_field_of_studies)->get();
        }


        $classroom = ClassRoom::where($userrole, $token_user->id)->where('status', '!=', 'payment_pending')->pluck('course_id');

        //finding the course countries
        $course_countries = course::whereIn('id', $classroom)->get('country_id')->unique();
        $countries = Country::select('id', 'name')->whereIn('id', $course_countries)->get();
        $course_countries=$countries;

        // $Countries = Countries::all();
        // $course_countries = [];
        // foreach ($countries as $country) {
        //     $Country = $Countries->where('name.common', $country->name)->first();
        //     $course_country = new stdClass();
        //     $course_country->name = $Country->name->common;
        //     $course_country->flag =  $Country->flag['flag-icon'];
        //     array_push($course_countries, $course_country);
        // }
        // course countries ended

        if (count($request->all()) >= 1) {

            if (count($request->all()) == 1) {
                $program = Program::findOrFail($request->program);
                // $countries = Country::select('id', 'name', 'emojiU')->get();
                $fieldOfStudies = FieldOfStudy::whereIn('id', $course_field_of_studies)->where('program_id', $program->id)->get();

                $newly_assigned_courses = Course::with('subject.country', 'language', 'program', 'student', 'student', 'classes')
                    ->whereIn('id', $classroom)->where('status', 'pending')->where('program_id', $program->id)
                    ->orderBy('id', 'desc')->get();
                $active_courses = Course::with('subject.country', 'language', 'program', 'student', 'student', 'classes')->whereIn('id', $classroom)->whereIn('status', ['active', 'inprogress'])->where('program_id', $program->id)->orderBy('id', 'desc')->get();
                $cancelled_courses = Course::with('subject.country', 'language', 'program', 'student', 'student', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->where('program_id', $program->id)->orderBy('id', 'desc')->get();
                $completed_courses = Course::with('subject.country', 'language', 'program', 'student', 'student', 'classes')
                    ->whereIn('id', $classroom)
                    ->where('status', 'completed')
                    ->where('program_id', $program->id)
                    ->get();



                $progress = 0;
                foreach ($active_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);
                }

                $progress = 0;
                foreach ($cancelled_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);
                }

                //adding rejected courses to completed courses
                $total_rejected_courses = RejectedCourse::whereHas('course', function ($q) use ($program) {
                    $q->where('program_id', $program->id);
                })
                    ->with('course.language', 'course.subject', 'course.student', 'course.program', 'course.classes')
                    ->where('user_id', $token_user->id)
                    ->get();

                $rejected_courses = [];
                foreach ($total_rejected_courses as $rejected_course) {
                    $rejected_course['course']['status'] = $rejected_course->status;
                    $completed_courses[] = $rejected_course['course'];
                }

                $progress = 0;
                foreach ($completed_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);
                }

                $progress = 0;
                foreach ($newly_assigned_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);
                }

                return response()->json([
                    'success' => true,
                    'programs' => $programs,
                    'field_of_studies' => $fieldOfStudies,
                    'countries' => $course_countries,
                    'newly_assigned_courses' => $newly_assigned_courses,
                    'active_courses' => $active_courses,
                    'cancelled_courses' => $cancelled_courses,
                    'completed_courses' => $completed_courses,
                ]);
            }

            if (count($request->all()) == 2) {
                // return "field of study 2";
                if ($request->has('field_of_study')) {
                    $program = Program::find($request->program);
                    $field_of_study = FieldOfStudy::find($request->field_of_study);
                    // $countries = Country::select('id', 'name', 'emojiU')->get();

                    $fieldOfStudies = FieldOfStudy::whereIn('id', $course_field_of_studies)->where('program_id', $program->id)->get();

                    $newly_assigned_courses = Course::with('subject.country', 'language', 'program', 'student', 'classes')->whereIn('id', $classroom)->where('status', 'pending')->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->orderBy('id', 'desc')->get();
                    $active_courses = Course::with('subject.country', 'language', 'program', 'student', 'classes')->whereIn('id', $classroom)->whereIn('status', ['active', 'pending'])->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->orderBy('id', 'desc')->get();
                    $cancelled_courses = Course::with('subject.country', 'language', 'program', 'student', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->orderBy('id', 'desc')->get();

                    $completed_courses = Course::with('subject.country', 'language', 'program', 'student', 'classes')
                        ->whereIn('id', $classroom)
                        ->where('status', 'completed')
                        ->where('program_id', $program->id)
                        ->where('field_of_study', $field_of_study->id)
                        ->get();



                    $progress = 0;
                    foreach ($active_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);
                    }


                    $progress = 0;
                    foreach ($cancelled_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);
                    }

                    //adding rejected courses to completed courses
                    $total_rejected_courses = RejectedCourse::whereHas('course', function ($q) use ($program, $field_of_study) {
                        $q->where('program_id', $program->id)->where('field_of_study', $field_of_study->id);
                    })
                        ->with('course', 'course.language', 'course.subject', 'course.student', 'course.program', 'course.classes')
                        ->where('user_id', $token_user->id)
                        ->get();
                    $rejected_courses = [];
                    foreach ($total_rejected_courses as $rejected_course) {
                        $rejected_course['course']['status'] = $rejected_course->status;
                        $completed_courses[] = $rejected_course['course'];
                    }

                    $progress = 0;
                    foreach ($completed_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);
                    }

                    $progress = 0;
                    foreach ($newly_assigned_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);
                    }

                    return response()->json([
                        'success' => true,
                        'programs' => $programs,
                        'field_of_studies' => $fieldOfStudies,
                        'countries' => $course_countries,
                        'newly_assigned_courses' => $newly_assigned_courses,
                        'active_courses' => $active_courses,
                        'cancelled_courses' => $cancelled_courses,
                        'completed_courses' => $completed_courses,

                    ]);
                }

                if ($request->has('country')) {
                    $program = Program::find($request->program);
                    $country = Country::find($request->country);

                    // //finding the course countries
                    // $course_countries = course::whereIn('id', $classroom)->get('country_id')->unique();
                    // $countries = Country::select('id', 'name')->whereIn('id', $course_countries)->get();

                    // $Countries = Countries::all();
                    // $course_countries = [];
                    // foreach ($countries as $country) {
                    //     $Country = $Countries->where('name.common', $country->name)->first();
                    //     $course_country = new stdClass();
                    //     $course_country->name = $Country->name->common;
                    //     $course_country->flag =  $Country->flag['flag-icon'];
                    //     array_push($course_countries, $course_country);
                    // }

                    $fieldOfStudies = FieldOfStudy::whereIn('id', $course_field_of_studies)->where('program_id', $program->id)->where('country_id', $country->id)->get();

                    $newly_assigned_courses = Course::with('subject.country', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->where('status', 'pending')->where('program_id', $program->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                    $active_courses = Course::with('subject.country', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->whereIn('status', ['active', 'inprogress'])->where('program_id', $program->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                    $cancelled_courses = Course::with('subject.country', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->where('program_id', $program->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                    $completed_courses = Course::with('subject.country', 'language', 'program', 'student', 'country', 'classes')
                        ->whereIn('id', $classroom)
                        ->where('status', 'completed')
                        ->where('program_id', $program->id)
                        ->where('country_id', $country->id)
                        ->get();

                    $progress = 0;
                    foreach ($active_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);
                    }

                    $progress = 0;
                    foreach ($cancelled_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);
                    }

                    //adding rejected courses to completed courses
                    $total_rejected_courses = RejectedCourse::whereHas('course', function ($q) use ($program, $country) {
                        $q->where('program_id', $program->id)->where('country_id', $country->id);
                    })
                        ->with('course', 'course.language', 'course.subject', 'course.student', 'course.program', 'course.classes')->where('user_id', $token_user->id)->get();
                    $rejected_courses = [];
                    foreach ($total_rejected_courses as $rejected_course) {
                        $rejected_course['course']['status'] = $rejected_course->status;
                        $completed_courses[] = $rejected_course['course'];
                    }

                    $progress = 0;
                    foreach ($completed_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);
                    }

                    $progress = 0;
                    foreach ($newly_assigned_courses as $course) {
                        $completed_classes = $course->classes->where('status', 'completed')->count();
                        $progress = ($completed_classes / $course->total_classes) * 100;
                        $course->progress = round($progress);
                    }

                    return response()->json([
                        'success' => true,
                        'programs' => $programs,
                        'field_of_studies' => $fieldOfStudies,
                        'countries' => $course_countries,
                        'newly_assigned_courses' => $newly_assigned_courses,
                        'active_courses' => $active_courses,
                        'cancelled_courses' => $cancelled_courses,
                        'completed_courses' => $completed_courses,

                    ]);
                }
            }

            if (count($request->all()) == 3) {

                $program = Program::find($request->program);
                $field_of_study = FieldOfStudy::find($request->field_of_study);
                $country = Country::find($request->country);

                // //finding the course countries
                // $course_countries = course::whereIn('id', $classroom)->get('country_id')->unique();
                // $countries = Country::select('id', 'name')->whereIn('id', $course_countries)->get();

                // $Countries = Countries::all();
                // $course_countries = [];
                // foreach ($countries as $country) {
                //     $Country = $Countries->where('name.common', $country->name)->first();
                //     $course_country = new stdClass();
                //     $course_country->name = $Country->name->common;
                //     $course_country->flag =  $Country->flag['flag-icon'];
                //     array_push($course_countries, $course_country);
                // }


                $fieldOfStudies = FieldOfStudy::whereIn('id', $course_field_of_studies)->where('program_id', $program->id)->where('country_id', $country->id)->get();

                $newly_assigned_courses = Course::with('subject.country', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->where('status', 'pending')->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                $active_courses = Course::with('subject.country', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->whereIn('status', ['active', 'inprogress'])->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                $cancelled_courses = Course::with('subject.country', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->where('country_id', $country->id)->orderBy('id', 'desc')->get();
                $completed_courses = Course::with('subject.country', 'language', 'program', 'student', 'country', 'classes')->whereIn('id', $classroom)->where('status', 'completed')->where('program_id', $program->id)->where('field_of_study', $field_of_study->id)->where('country_id', $country->id)->get();

                $progress = 0;
                foreach ($active_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);
                }

                $progress = 0;
                foreach ($cancelled_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);
                }

                //adding rejected courses to completed courses
                $total_rejected_courses = RejectedCourse::with('course', 'course.language', 'course.subject', 'course.student', 'course.program', 'course.classes')->where('user_id', $token_user->id)->get();
                $rejected_courses = [];
                foreach ($total_rejected_courses as $rejected_course) {
                    $rejected_course['course']['status'] = $rejected_course->status;
                    $completed_courses[] = $rejected_course['course'];
                }

                $progress = 0;
                foreach ($completed_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);
                }

                $progress = 0;
                foreach ($newly_assigned_courses as $course) {
                    $completed_classes = $course->classes->where('status', 'completed')->count();
                    $progress = ($completed_classes / $course->total_classes) * 100;
                    $course->progress = round($progress);
                }


                return response()->json([
                    'success' => true,
                    'programs' => $programs,
                    'field_of_studies' => $fieldOfStudies,
                    'countries' => $course_countries,
                    'newly_assigned_courses' => $newly_assigned_courses,
                    'active_courses' => $active_courses,
                    'cancelled_courses' => $cancelled_courses,
                    'completed_courses' => $completed_courses,

                ]);
            }
        } else {

            $program = Program::where('code', $request->program)->first();
            $newly_assigned_courses = Course::with('subject.country', 'language', 'program', 'student', 'classes')->where($userrole, $token_user->id)->where('status', 'pending')->orderBy('id', 'desc')->get();
            $active_courses = Course::with('subject.country', 'language', 'program', 'student', 'classes')->where($userrole, $token_user->id)->whereIn('status', ['active', 'inprogress'])->orderBy('id', 'desc')->get();
            $cancelled_courses = Course::with('subject.country', 'language', 'program', 'student', 'classes')->where($userrole, $token_user->id)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_student', 'cancelled_by_admin'])->orderBy('id', 'desc')->get();
            $completed_courses = Course::with('subject.country', 'language', 'program', 'student', 'classes')->where($userrole, $token_user->id)->where('status', 'completed')->get();

            $progress = 0;
            foreach ($active_courses as $course) {
                $completed_classes = $course->classes->where('status', 'completed')->count();
                $progress = ($completed_classes / $course->total_classes) * 100;
                $course->progress = round($progress);
            }

            $progress = 0;
            foreach ($cancelled_courses as $course) {
                $completed_classes = $course->classes->where('status', 'completed')->count();
                $progress = ($completed_classes / $course->total_classes) * 100;
                $course->progress = round($progress);
            }

            //adding rejected courses to completed courses
            $total_rejected_courses = RejectedCourse::with('course', 'course.language', 'course.subject', 'course.student', 'course.program', 'course.classes')->where('user_id', $token_user->id)->get();
            $rejected_courses = [];
            foreach ($total_rejected_courses as $rejected_course) {
                $rejected_course['course']['status'] = $rejected_course->status;
                $completed_courses[] = $rejected_course['course'];
            }

            $progress = 0;
            foreach ($completed_courses as $course) {
                $completed_classes = $course->classes->where('status', 'completed')->count();
                $progress = ($completed_classes / $course->total_classes) * 100;
                $course->progress = round($progress);
            }



            return response()->json([
                'success' => true,
                'programs' => $programs,
                'newly_assigned_courses' => $newly_assigned_courses,
                'active_courses' => $active_courses,
                'cancelled_courses' => $cancelled_courses,
                'completed_courses' => $completed_courses,
                'countries' => $course_countries,
            ]);
        }
    }

    //*********/ Search Course *********
    public function search_course(Request $request)
    {
        $string = $request->search_query;
        $course  = Course::with('student', 'language')->whereHas('field', function ($query) use ($string) {
            $query->where('name', 'like', "%$string%");
        })->orWhere('field_of_study', $request->search_query)->get();

        return response()->json([
            'success' => true,
            'course' => $course,
        ]);
    }

    //*********/ Show ALL Classes *********
    public function show_classes()
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $user_id = $token_user->id;
        $user = User::find($user_id);
        // return $user->role_name;
        if ($user->role_name == "student") {
            $classes = AcademicClass::with('teacher', 'course', 'course.subject.country')->where('student_id', $user->id)->get();
            return response()->json([
                'success' => true,
                'classes' => $classes,
            ]);
        }
        if ($user->role_name == "teacher") {
            $classes = AcademicClass::with('student', 'course', 'course.subject.country')->where('teacher_id', $user->id)->get();
            return response()->json([
                'success' => true,
                'classes' => $classes,
            ]);
        }
    }

    //*********/ Specific Course details *********
    public function course_detail(Request $request, $course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($token_user->role_name == 'teacher') {
            $userrole = 'teacher_id';
        } elseif ($token_user->role_name == 'student') {
            $userrole = 'student_id';
        }

        


        $current_date = Carbon::today()->format('Y-m-d');

        $todays_date = Carbon::now()->format('d-M-Y [l]');

        $user_id = $token_user->id;
        $current_date = Carbon::today()->format('Y-m-d');

        $course = Course::with('subject.country', 'language', 'program', 'participants.user')->find($course_id);
        $corse = Course::with('subject.country', 'language', 'program', 'classes')->find($course_id);

        //************ If class date and time passed then roll call attendence ************
        // return $corse['classes'];
        foreach ($corse['classes'] as $class) {
            $classStart = Carbon::parse($class->start_date)->format('Y-m-d');
            if (Carbon::today() >= Carbon::parse($class->start_date)) {
                // echo "passed,";

                $current_time = Carbon::now();
                $endTime = Carbon::parse($class->end_time);
                if ($current_time > $endTime) {
                    //for students
                    // return $class->participants;
                    foreach ($class->participants as $participant) {
                        $attendance = Attendance::where('user_id', $participant->student_id)
                            ->where('academic_class_id', $class->id)
                            ->first();

                        if ($attendance == null) {
                            $userAttendence = new Attendance();
                            $userAttendence->academic_class_id = $class->id;
                            $userAttendence->course_id = $class->course_id;
                            $userAttendence->user_id = $participant->student_id;
                            $userAttendence->status = 'absent';
                            $userAttendence->role_name = 'student';
                            $userAttendence->save();
                        }
                    }
                    //for teacher
                    $attendance = Attendance::where('user_id', $class->teacher_id)
                        ->where('academic_class_id', $class->id)
                        ->first();

                    if ($attendance == null) {
                        $userAttendence = new Attendance();
                        $userAttendence->academic_class_id = $class->id;
                        $userAttendence->course_id = $class->course_id;
                        $userAttendence->user_id = $class->teacher_id;
                        $userAttendence->status = 'absent';
                        $userAttendence->role_name = 'teacher';
                        $userAttendence->save();
                    }
                }
            }
        }
        // ************************************************

        $todays_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'day', 'status')
            ->with('course', 'course.subject.country', 'course.student', 'attendence', 'attendees.user')
            ->whereDate('start_date', $current_date)
            ->with('course')
            ->with(['student_attendence' => function ($q) {
                $q->where('role_name', 'student');
            }])
            ->with(['teacher_attendence' => function ($q) {
                $q->where('role_name', 'teacher');
            }])
            // ->where($userrole, $user_id)
            ->where('course_id', $course->id)
            ->get();

        $upcoming_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'day', 'status')
            ->with('course', 'course.subject.country', 'course.student', 'attendence', 'attendees.user')
            ->whereDate('start_date', '>', $current_date)
            ->with('course')
            ->with(['student_attendence' => function ($q) {
                $q->where('role_name', 'student');
            }])
            ->with(['teacher_attendence' => function ($q) {
                $q->where('role_name', 'teacher');
            }])
            ->where($userrole, $user_id)
            ->where('course_id', $course->id)
            ->get();

        $total_upcomingClasses = AcademicClass::whereDate('start_date', '>', $current_date)
            ->where($userrole, $user_id)
            ->where('course_id', $course->id)
            ->count();

        $past_classes = AcademicClass::select('id', 'class_id', 'title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'day', 'status')
            ->with('course', 'course.subject.country', 'course.student', 'attendees.user')
            ->with(['attendence' => function ($query) use ($user_id) {
                $query->where(['user_id' => $user_id]);
            }])
            ->with(['student_attendence' => function ($q) {
                $q->where('role_name', 'student');
            }])
            ->with(['teacher_attendence' => function ($q) {
                $q->where('role_name', 'teacher');
            }])
            ->whereDate('start_date', '<', $current_date)
            ->with('course')
            ->where($userrole, $user_id)
            ->where('course_id', $course->id)
            ->get();

        $total_pastClasses = AcademicClass::whereDate('start_date', '<', $current_date)
            ->where($userrole, $user_id)
            ->where('course_id', $course->id)
            ->count();

        $remaining_classes = AcademicClass::where('course_id', $course_id)->whereDate('start_date', '>', $current_date)->where('status', '!=', 'completed')->count();
        $completed_classes = AcademicClass::where('course_id', $course_id)->where('status', 'completed')->count();

        $totalClases = AcademicClass::where('course_id', $course_id)->where($userrole, $user_id)->count();
        $completedClases = AcademicClass::where('course_id', $course_id)->where('status', 'completed')->where($userrole, $user_id)->count();
        $inProgress = 0;
        if ($totalClases > 0) {
            $inProgress = ($completedClases / $totalClases) * 100;
        }

        //Add or update course to Last Activity
        $last_activity = LastActivity::updateOrCreate([
            'user_id'   => $token_user->id,
            'course_id' => $course_id,
            'field_of_study_id' => $course->field_of_study,
            'country_id' => $course->country_id,
            'program_id' => $course->program_id,
        ], [
            'user_id' => $token_user->id,
            'course_id' => $course_id,
            'role_name' => $token_user->role_name,
            'field_of_study_id' => $course->field_of_study,
            'country_id' => $course->country_id,
            'program_id' => $course->program_id,
        ]);

        $last_activity->updated_at = Carbon::now();
        $last_activity->update();


        return response()->json([
            'status' => true,
            'todays_date' =>  $todays_date,
            'total_pastClasses' => $total_pastClasses,
            'total_upcomingClasses' => $total_upcomingClasses,
            'remaining_classes' => $remaining_classes,
            'completed_classes' => $completed_classes,
            'progress' => round($inProgress),
            'course' =>  $course,
            'todays_classes' => $todays_classes,
            'upcoming_classes' => $upcoming_classes,
            'past_classes' => $past_classes,

        ]);
    }

    //*********/ Search a specific Class *********
    public function search_class(Request $request)
    {
        $rules = [
            'search' =>  'required',
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

        $apiURL = 'https://api.braincert.com/v2/listclass';
        $postInput = [
            'apikey' => 'xKUyaLJHtbvBUtl3otJc',
            'search' => $request->search,
        ];

        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);
        $responseBody = json_decode($response->getBody(), true);

        $query = $request->search;
        $class =  AcademicClass::select('id', 'title', 'lesson_name', 'class_type', 'student_id', 'teacher_id', 'course_id')
            ->with('student', 'teacher', 'course')
            ->where('title', 'LIKE', "%$query%")
            ->orWhere('lesson_name', 'LIKE', "%$query%")
            ->orWhere('class_id', 'LIKE', "%$query%")
            ->get();
        $result = array();
        $result = $responseBody['classes'];
        $result = array_merge($result, $class->toArray());
        return response()->json([
            'success' => true,
            'braincert_class' => $result,
            // 'class' => $class,
        ]);

        if ($responseBody['status'] == 'error') {
            return response()->json([
                'success' => false,
                'error' => $responseBody['errors'],

            ], 400);
        }
    }

    //*********/ Teacher: Reschedule Class *********
    public function reschedule_class(Request $request)
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $rules = [
            'academic_class_id' =>  'required',
            'start_time' =>  'required',
            'end_time' =>  'required',
            'start_date' =>  'required|date|after:today',
            'duration' =>  'required',
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


        $academic_id = $request->academic_class_id;
        $class = AcademicClass::where('id', $academic_id)->first();
        //checking date
        $currentdate = Carbon::now()->format('Y-m-d');
        $currentdate = Carbon::parse($currentdate);
        $db_date = Carbon::parse($class->start_date);
        if ($db_date >= $currentdate) {
            $dayOrNight = substr($class->start_time, 6, 9);
            $trimed_time = Str::limit($class->start_time, 5, '');
            $final_time = $trimed_time;
            // converting pm to 24 hour format
            if ($dayOrNight == "PM") {

                $time = $trimed_time;
                $time2 = "12:00:00";
                $secs = strtotime($time2) - strtotime("00:00:00");
                $final_time = date("H:i", strtotime($time) + $secs);
            }
            // converting pm to 24 hour format ends

            // calculating difference
            $crrentTime = Carbon::now()->format('Y-m-d H:i');
            $startTime = Carbon::parse($crrentTime);
            $endTime = Carbon::parse($class->start_date . ' ' . $final_time);
            $totalDuration = $endTime->diffInHours($crrentTime);
            // calculating difference ends

            $EndTime = substr($request->end_time, 0, 5);
            $StartTime = substr($request->start_time, 0, 5);

            $start_date = Carbon::parse($class->start_date);

            $start_time = date("G:i", strtotime($request->start_time));
            $end_time = date("G:i", strtotime($request->end_time));

            $classes = AcademicClass::where('id', '!=', $academic_id)
                ->where('start_date', $request->start_date)
                ->where('teacher_id', $token_user->id)
                ->where('status', '!=', 'completed')
                ->get();

            foreach ($classes as $class) {
                $db_startTime = date("G:i", strtotime($class->start_time));
                $db_endTime = date("G:i", strtotime($class->end_time));
                if (($start_time >= $db_startTime) && ($start_time <= $db_endTime) || ($end_time >= $db_startTime) && ($end_time <= $db_endTime)) {
                    return response()->json([
                        'status' => false,
                        'errors' => "Already have scheduled class at this time! please check teacher availability first",
                    ], 400);
                }
            }


            if ($totalDuration > 48) {
                // if class is scheduled by braincert
                if ($class->class_id != null) {
                    $class->start_time = $request->start_time;
                    $class->end_time = $request->end_time;
                    $class->start_date = $request->start_date;
                    $class->duration = $request->duration;
                    $class->update();

                    $reschedule_class = new RescheduleClass();
                    $reschedule_class->rescheduled_by = $token_user->id;
                    $reschedule_class->academic_class_id = $request->academic_class_id;
                    $reschedule_class->course_id = $class->course_id;
                    $reschedule_class->start_time = $request->start_time;
                    $reschedule_class->end_time = $request->end_time;
                    $reschedule_class->day = $request->day;
                    $reschedule_class->status = 'rescheduled_by_teacher';
                    $reschedule_class->save();

                    return response()->json([
                        'status' => true,
                        'message' => "Class rescheduled successfully!",
                    ]);
                } else {
                    // if class is not scheduled by braincert
                    $class->start_time = $request->start_time;
                    $class->end_time = $request->end_time;
                    $class->start_date = $request->start_date;
                    $class->class_type = $request->class_type;
                    $class->duration = $request->duration;

                    $apiURL = 'https://api.braincert.com/v2/updateclass';
                    $postInput = [
                        'apikey' => 'xKUyaLJHtbvBUtl3otJc',
                        'id' => $class->class_id,
                        'start_time' => Carbon::parse($request->start_time)->format('G:i a'),
                        'end_time' => Carbon::parse($request->end_time)->format('G:i a'),
                        'date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                    ];

                    $client = new Client();
                    $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);
                    $responseBody = json_decode($response->getBody(), true);
                    $class->class_id = $responseBody['class_id'];
                    $class->update();

                    $reschedule_class = new RescheduleClass();
                    $reschedule_class->rescheduled_by = $token_user->id;
                    $reschedule_class->academic_class_id = $request->academic_class_id;
                    $reschedule_class->course_id = $class->course_id;
                    $reschedule_class->start_time = $request->start_time;
                    $reschedule_class->end_time = $request->end_time;
                    $reschedule_class->day = $request->day;
                    $reschedule_class->status = 'rescheduled_by_teacher';
                    $reschedule_class->save();

                    return response()->json([
                        'status' => true,
                        'errors' => "Class rescheduled successfully!",
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'errors' => "You dont have time to reschdule the class",
                ], 400);
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => "Class date has been passed",
            ], 400);
        }
    }

    //************* Program Subjects *************
    public function program_subjects($program_id)
    {
        $fieldOfStudies = FieldOfStudy::where('program_id', $program_id)->get();
        $subjects_array = [];
        foreach ($fieldOfStudies as $fieldOfStudy) {
            $subjects = $subject = Subject::select('id', 'name')->where('field_id', $fieldOfStudy->id)->get();
            foreach ($subjects as $subject) {
                array_push($subjects_array, $subject);
            }
        }

        return response()->json([
            'status' => true,
            'subjects' => $subjects_array,
        ]);
    }

    //************* Teacher Kudos Points *************
    public function kudos_points(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $teacher = User::find($token_user->id);
        $feedbacks = UserFeedback::with('sender', 'feedback')->where('receiver_id', $token_user->id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Teacher kudos points',
            'kudos_points' => $teacher->kudos_points,
            'feedbacks' => $feedbacks,
        ]);
    }

    public function addClass()
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
    }

    public function local_to_utc(Request $request)
    {
        $rules = [
            'local_time' =>  'required',
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

        $ip = request()->getClientIp();
        $user = \Location::get($ip);

        // $user_date = $user_date = date('H:i A', strtotime($request->local_time));

        # convert user date to utc date
        $utc_date = Carbon::createFromFormat('Y-m-d H:i A', $request->local_time, ($user->timezone));
        $utc_date->setTimezone('UTC');

        # check the utc date
        $utc = $utc_date->format('Y-m-d g:i A');

        return response()->json([
            'status' => true,
            'message' => 'Converting User time to UTC time',
            'user_date' =>  $request->local_time,
            'UTC' => $utc,
        ]);
    }

    public function utc_to_local(Request $request)
    {
        $rules = [
            'utc_time' =>  'required',
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

        $ip = request()->getClientIp();
        $user = \Location::get($ip);

        #using utc date convert date to user date
        $user_date = Carbon::createFromFormat('Y-m-d H:i A', $request->utc_time, 'UTC');
        $user_date->setTimezone($user->timezone);

        # check the user date
        $localtime = $user_date->format('Y-m-d g:i A');

        return response()->json([
            'status' => true,
            'message' => 'Converting User time to UTC time',
            'UTC time' => $request->utc_time,
            'localtime' => $localtime,
        ]);
    }

    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }
}
