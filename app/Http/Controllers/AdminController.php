<?php

namespace App\Http\Controllers;

use App\Events\AssignTeacherEvent;
use App\Events\BlockUserEvent;
use App\Events\CancelCourse;
use App\Events\MeetingScheduledEvent;
use App\Events\RefundCourseEvent;
use App\Events\TeacherStatusEvent;
use App\Events\UnBlockUserEvent;
use App\Models\RequestedCourse;
use App\Models\AcademicClass;
use App\Models\CanceledCourse;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Newsletter;
use App\Models\RescheduleClass;
use App\Models\Role;
use App\Models\Testimonial;
use App\User;
use App\Models\UserFeedback;
use App\Models\UserTestimonial;
use App\Subject;
use App\FieldOfStudy;
use App\Jobs\AssignTeacherJob;
use App\Jobs\BlockUserJob;
use App\Jobs\MeetingScheduledJob;
use App\Jobs\RefundCourseJob;
use App\Jobs\TeacherStatusJob;
use App\Jobs\UnBlockUserJob;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\NoTeacherCourse;
use App\Models\Order;
use App\Models\RefundCourse;
use App\Models\RejectedCourse;
use App\Models\UserAssignment;
use App\Program;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use stdClass;
use App\TeacherInterviewRequest;
use App\TeacherSubject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    //********/ Change the teacher for some Course ********
    public function change_teacher(Request $request)
    {

        $rules = [
            'teacher_id' =>  'required|integer',
            'course_id' =>  'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ]);
        }

        $course = Course::find($request->course_id);
        $course->teacher_id = $request->teacher_id;
        $classes = AcademicClass::where("course_id", $course->id)->where('status', '=', null)->get();

        foreach ($classes as $class) {
            $cls = AcademicClass::find($class->id);
            $cls->teacher_id = $request->teacher_id;
            $cls->update();
        }
        $course->update();

        return response()->json([
            'status' => true,
            'message' =>  "Teacher Assigned to course Successfully",
        ]);
    }

    public function warn_teacher(Request $request)
    {

        $rules = [
            'teacher_id' =>  'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => false,
                'errors' => $errors,
            ]);
        }

        $teacher = User::find($request->teacher_id);
        if ($teacher->ban <= 2) {
            $teacher->ban = $teacher->ban + 1;
            $teacher->update();

            return response()->json([
                'status' => true,
                'message' => "Warning has been given to teacher!",
            ]);
        } else {
            $teacher->status = "disabled";
            return response()->json([
                'status' => false,
                'message' => "Warning Limit Exceeded! Account has been banned",
            ]);
        }
    }

    public function teacher_performance(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required'
        ]);

        $average_rating = 5;
        $user = User::find($request->teacher_id);
        $rating_sum = UserFeedback::where('receiver_id', $user->id)->sum('rating');
        $total_reviews = UserFeedback::where('receiver_id', $user->id)->count();
        if ($total_reviews > 0) {
            $average_rating = $rating_sum / $total_reviews;
        }


        return response()->json([
            'status' => true,
            'message' => "Teacher Performance",
            'average_rating' => $average_rating,
        ]);
    }

    public function block_user(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);
        $user->status = 'inactive';
        $user->update();

        $admin = User::where('role_name', 'admin')->first();
        $user_data = $user;
        event(new BlockUserEvent($user->id, $user, $user_data, "You have been blocked!"));
        event(new BlockUserEvent($admin->id, $admin, $user_data, "User Blocked Successfully!"));
        dispatch(new BlockUserJob($user->id, $user, $user_data, "You have been blocked!"));
        dispatch(new BlockUserJob($admin->id, $admin, $user_data, "User Blocked Successfully!"));

        return response()->json([
            'status' => true,
            'message' => "User Blocked Successfully",
            'user' => $user,
        ]);
    }

    public function unblock_user(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);
        $user->status = 'active';
        $user->update();

        $admin = User::where('role_name', 'admin')->first();
        $user_data = $user;
        event(new UnBlockUserEvent($user->id, $user, $user_data, "You have been Un-blocked!"));
        event(new UnBlockUserEvent($admin->id, $admin, $user_data, "User Un-Blocked Successfully!"));
        dispatch(new UnBlockUserJob($user->id, $user, $user_data, "You have been Un-blocked!"));
        dispatch(new UnBlockUserJob($admin->id, $admin, $user_data, "User Un-Blocked Successfully!"));

        return response()->json([
            'status' => true,
            'message' => "User UnBlocked Successfully",
            'user' => $user,
        ]);
    }

    public function add_role(Request $request)
    {
        $rules = [
            'name' =>  'required|unique:roles,name',
            'caption' =>  'required',
            'is_admin' =>  'required|bool',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => false,
                'errors' => $errors,
            ]);
        }
        $role = new Role();
        $role->name = $request->name;
        $role->caption = $request->caption;
        $role->is_admin = $request->is_admin;
        $role->save();

        return response()->json([
            'status' => true,
            'message' => "Role added Successfully",
            'role' => $role,
        ]);
    }

    public function update_role(Request $request, $role_id)
    {

        $role = Role::find($role_id);
        $userUpdated = $role->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "Role Updated Successfully",
            'role' =>  $role,
        ]);
    }

    public function delete_role($role_id)
    {
        $role = Role::find($role_id);
        $role->delete();

        return response()->json([
            'status' => true,
            'message' => "Role Deleted Successfully",
            'role' =>  $role,
        ]);
    }

    public function roles()
    {
        $roles = Role::all();

        return response()->json([
            'status' => true,
            'message' => "Role Deleted Successfully",
            'roles' => $roles,
        ]);
    }

    public function booked_courses()
    {
        $courses = Course::all();

        return response()->json([
            'status' => true,
            'message' => "All courses",
            'courses' => $courses,
        ]);
    }

    public function course_detail($id)
    {
        $course = Course::with('teacher', 'program', 'field', 'student')
            ->withCount('students')
            ->withCount(['remaining_classes' => function ($q) {
                $q->where('status', '!=', 'completed');
            }])
            ->withCount(['completed_classes' => function ($q) {
                $q->where('status', 'completed');
            }])
            ->findOrFail($id);

        // $rating =

        $completed_classes = [];
        $remaining_classes = [];

        $course1 = Course::with('classes.attendees.student')->findOrFail($id);

        foreach ($course1['classes'] as $class) {
            if ($class->status == 'completed') {
                $class->topic;
                $class->attendees;
                array_push($completed_classes, $class);
            } else {
                $class->attendees;
                $class->topic;
                array_push($remaining_classes, $class);
            }
        }

        $progress = ($course->completed_classes_count / $course->total_classes) * 100;

        $average_rating = 5.0;
        $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
        $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
        if ($total_reviews > 0) {
            $average_rating = $rating_sum / $total_reviews;
        }

        $reassigned_classes = AcademicClass::where(['course_id' => $course->id, 'status' => 'reassigned'])->get();
        $course->course_progress = $progress;
        $course['teacher']->teacher_rating = $average_rating;

        return response()->json([
            'status' => true,
            'message' => "Course Detail!",
            // 'course_progress' => $progress,
            // 'teacher_rating' => $average_rating,
            'course' => $course,
            // 'reassigned_classes' => $reassigned_classes,
            'remaining_classes' => $remaining_classes,
            'completed_classes' => $completed_classes,
        ]);
    }

    public function teacher_course_detail($teacher_id, $course_id)
    {
        $course = Course::with('teacher', 'program', 'field')
            ->withCount('students')
            ->withCount(['remaining_classes' => function ($q) {
                $q->where('status', '!=', 'reassigned');
            }])
            ->withCount(['completed_classes' => function ($q) {
                $q->where('status', 'completed');
            }])
            ->where('id', $course_id)->where('teacher_id', $teacher_id)->first();

        $rescheduled_classes = RescheduleClass::where('course_id', $course->id)->get();
        $course->rescheduled_classes_count = count($rescheduled_classes);
        // $rating =

        $completed_classes = [];
        $remaining_classes = [];

        $course1 = Course::with('classes.attendees.student')->findOrFail($course_id);

        foreach ($course1['classes'] as $class) {
            if ($class->status == 'completed') {
                $class->topic;
                array_push($completed_classes, $class);
            } else {
                $class->topic;
                array_push($remaining_classes, $class);
            }
        }

        $progress = ($course->completed_classes_count / $course->total_classes) * 100;

        $average_rating = 5.0;
        $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
        $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
        if ($total_reviews > 0) {
            $average_rating = $rating_sum / $total_reviews;
        }

        $course->course_progress = $progress;
        $course['teacher']->teacher_rating = $average_rating;

        return response()->json([
            'status' => true,
            'message' => "Course Detail!",
            // 'course_progress' => $progress,
            // 'teacher_rating' => $average_rating,
            'course' => $course,
            'remaining_classes' => $remaining_classes,
            'completed_classes' => $completed_classes,
        ]);
    }

    public function courses_classes()
    {
        $courses = Course::with('classes')->where('status', 'active')->get();

        return response()->json([
            'status' => true,
            'message' => "Courses and classes!",
            'courses' => $courses,
        ]);
    }

    public function course_teachers()
    {
        $teachers = User::with(['course' => function ($q) {
            $q->where('status', 'active');
        }])->where('role_name', 'teacher')->get();

        return response()->json([
            'status' => true,
            'message' => "Teachers with courses!",
            'teacher' => $teachers,
        ]);
    }

    public function teacher_canceledcourses()
    {
        $courses = CanceledCourse::with('teacher', 'student', 'course')
            ->where('cancelled_by', 'teacher')
            ->get();

        //calculating the ratings
        foreach ($courses as $course) {
            #for student
            $student_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->student_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->student_id)->count();
            if ($total_reviews > 0) {
                $student_rating = $rating_sum / $total_reviews;
            }
            $course->student_rating = $student_rating;

            #for teacher
            $teacher_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
            if ($total_reviews > 0) {
                $teacher_rating = $rating_sum / $total_reviews;
            }
            $course->teacher_rating = $teacher_rating;
        }

        return response()->json([
            'status' => true,
            'message' => "Courses Canceled by teacher!",
            'canceled_courses' => $courses,
        ]);
    }

    public function student_canceledcourses()
    {
        $courses = CanceledCourse::with('teacher', 'student', 'course')
            ->where('cancelled_by', 'student')
            ->get();

        //calculating the ratings
        foreach ($courses as $course) {
            #for student
            $student_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->student_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->student_id)->count();
            if ($total_reviews > 0) {
                $student_rating = $rating_sum / $total_reviews;
            }
            $course->student_rating = $student_rating;

            #for teacher
            $teacher_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
            if ($total_reviews > 0) {
                $teacher_rating = $rating_sum / $total_reviews;
            }
            $course->teacher_rating = $teacher_rating;
        }

        return response()->json([
            'status' => true,
            'message' => "Courses Canceled by student!",
            'canceled_courses' => $courses,
        ]);
    }

    public function teachers_schedule(Request $request)
    {
        $now = Carbon::now();
        $weekStartDate = $now->format('Y-m-d');
        $weekEndDate = $now->addDays(7)->format('Y-m-d');

        // $teachers = User::with(['teacher_courses.course', 'teacher_courses.course.attendence', 'teacher_courses' => function ($q) {
        //     $q->where('status', 'active');
        //     $q->with(['classes' => function ($qu) {
        //         $qu->where('status', 'scheduled');
        //     }]);
        // }])
        //     ->where('role_name', 'teacher')->get();
        // .course', 'teacher_courses.student', 'teacher_courses.teacher





        // $teachers = User::with('teacher_courses')->has('teacher_courses')->get();

        // $counter = 0;
        // foreach ($teachers as $teacher) {
        //     $counter++;
        //     // echo $counter;
        //     unset($teacher['teacher_courses']);
        //     $courses = $teacher->teacher_courses->whereIn('status', ['active', 'inprogress']);
        //     echo count($courses) . ",";
        //     if (count($courses) == 0) {
        //         echo "empty";
        //         unset($teacher);
        //     } else {
        //         $tutors = [];
        //         unset($teacher['teacher_courses']);
        //         $tutor_courses = [];

        //         foreach ($courses as $course) {
        //             if ($course->status == 'active' || $course->status == 'inprogress') {
        //                 array_push($tutor_courses, $course);
        //                 $course->course->
        //                 $course->student;
        //                 $course->teacher;
        //                 // $teacher->tutor_courses[] = $course;
        //             }
        //         }
        //         $teacher->tutor_courses =  $tutor_courses;
        //         $teacher->tutor_courses =  $tutor_courses;
        //         array_push($tutors, $teacher);
        //     }
        // }

        $weekDays = [];

        if ($request != '') {
            $weekStartDate = Carbon::parse($request->start_date)->format('Y-m-d');
            $weekEndDate = Carbon::parse($weekStartDate)->addDays(7)->format('Y-m-d');
        }

        //Showing Weekdays
        for ($i = 0; $i < 7; $i++) {
            $day = Carbon::parse($weekStartDate)->addDay($i)->format('l');
            $date = Carbon::parse($weekStartDate)->addDay($i)->format('Y-m-d');
            //push the current day and plus the mount of $i 

            array_push($weekDays, [
                $day => $date,
            ]);
        }
        // return $weekDays;




        $teachers = Course::where('teacher_id', '!=', null)->pluck('teacher_id')->unique();
        $tutors = [];


        foreach ($teachers as $teacher) {
            //Declaration
            $monday_classes = [];
            $tuesday_classes = [];
            $wednesday_classes = [];
            $thursday_classes = [];
            $friday_classes = [];
            $saturday_classes = [];
            $sunday_classes = [];

            $Teacher = User::findOrFail($teacher);

            $classes = AcademicClass::with('course', 'student')
                ->where('teacher_id', $teacher)
                ->where('start_date', '>=', $weekStartDate)
                ->where('start_date', '<=', $weekEndDate)
                ->whereIn('status', ['scheduled'])
                // ->orderBy('start_time')
                ->get();


            $completed_class = AcademicClass::where('status', 'completed')->where('teacher_id', $teacher)->count();
            $pending_classes = AcademicClass::where('status', '!=', 'completed')->where('teacher_id', $teacher)->count();
            $Teacher->completed_class = $completed_class;
            $Teacher->pending_classes = $pending_classes;
            // $Teacher->classes = $classes;

            //getting per day classes
            foreach ($classes as $class) {
                switch ($class->day) {
                    case ('2'):
                        array_push($monday_classes, $class);
                        break;
                    case ('3'):
                        array_push($tuesday_classes, $class);
                        break;
                    case ('4'):
                        array_push($wednesday_classes, $class);
                        break;
                    case ('5'):
                        array_push($thursday_classes, $class);
                        break;
                    case ('6'):
                        array_push($friday_classes, $class);
                        break;
                    case ('7'):
                        break;
                        array_push($saturday_classes, $class);
                    default:
                        array_push($sunday_classes, $class);
                }
            }

            $scheduled_classes = [
                'monday' => $monday_classes,
                'tuesday' => $tuesday_classes,
                'wednesday' => $wednesday_classes,
                'thursday' => $thursday_classes,
                'friday' => $friday_classes,
                'saturday' => $saturday_classes,
                'sunday' => $sunday_classes,
            ];

            $Teacher->scheduled_classes = $scheduled_classes;
            // $Teacher->tuesday = $tuesday_classes;
            // $Teacher->wednesday = $wednesday_classes;
            // $Teacher->thursday = $thursday_classes;
            // $Teacher->friday = $friday_classes;
            // $Teacher->saturday = $saturday_classes;
            // $Teacher->sunday = $sunday_classes;

            if (count($classes) > 0) {
                array_push($tutors, $Teacher);
            }
        }




        return response()->json([
            'status' => true,
            'message' => "Teachers Classes Schedule",
            'weekdays' => $weekDays,
            'teachers' => $this->paginate($tutors, $request->per_page ?? 10),
        ]);
    }

    public function students_schedule()
    {
        $students = User::where('role_name', 'student')->get();
        $object = new stdClass();
        $counter = 0;
        foreach ($students as $student) {
            $counter++;
            // $courses = ClassRoom::where('student_id', $student->id)->get();
            $object->student =  $student;
            // if (count($courses) > 0) {
            //     foreach ($courses as $course) {
            //         $object->course = $course;
            //         $classes = AcademicClass::where('course_id', $course->course_id)->where('student_id', $student->id)->where('status', 'scheduled')->get();
            //         $object->classes = $classes;
            //     }
            // } else {
            //     $object->course = null;
            //     $object->classes = [];
            // }
        }

        return response()->json([
            'status' => true,
            'message' => "Student's Classes Schedule",
            'total_students' => count($students),
            'result' => $object,
        ]);
    }

    public function newsletter(Request $request)
    {
        $newsletters = Newsletter::Paginate($request->per_page ?? 10);

        return response()->json([
            'status' => true,
            'message' => "All Newsletters",
            'newsletters' => $newsletters,
        ]);
    }

    public function del_newsletter($id)
    {
        $newsletter = Newsletter::find($id);
        $newsletter->delete();

        return response()->json([
            'status' => true,
            'message' => "Newsletter Deleted Successfully!",
            'newsletter' => $newsletter,
        ]);
    }



    public function course_feedbacks()
    {
        $feedbacks = UserFeedback::whereHas('sender', function ($q) {
            $q->where('role_name', 'student');
        })
            ->get();


        return response()->json([
            'status' => true,
            'message' => "Students Feedbacks",
            'user_feedbacks' => $feedbacks,
        ]);
    }

    public function platform_feedbacks(Request $request)
    {
        $testimonials = UserTestimonial::with('sender', 'testimonial')->get();

        // if he is student
        if ($request->feedback_by == 'student') {


            $testimonials = UserTestimonial::whereHas('sender', function ($q) {
                $q->where('role_name', 'student');
            })->with('testimonial', 'sender')->get();
            //Name FIlter
            if ($request->has('name')) {
                $name = $request->name;
                $testimonials = UserTestimonial::whereHas('sender', function ($q) use ($name) {
                    $q->where('role_name', 'student')
                        ->where(function ($qe) use ($name) {
                            $qe->where('first_name', 'LIKE', "%$name%")
                                ->orWhere('last_name', 'LIKE', "%$name%");
                        });
                })->with('testimonial', 'sender')->get();
            }


            //overall average stars for teacher
            $five_stars = $testimonials->where('rating', 5)->count();
            $four_stars = $testimonials->where('rating', 4)->count();
            $three_stars = $testimonials->where('rating', 3)->count();
            $two_stars = $testimonials->where('rating', 2)->count();
            $one_stars = $testimonials->where('rating', 1)->count();

            $overall_stars = array(

                array(
                    "title" => "5",
                    "value" => $five_stars,
                ),

                array(
                    "title" => "4",
                    "value" => $four_stars,
                ),

                array(
                    "title" => "3",
                    "value" => $three_stars,
                ),

                array(
                    "title" => "2",
                    "value" => $two_stars,
                ),

                array(
                    "title" => "1",
                    "value" => $one_stars,
                ),
            );

            //overall average feedback
            $feedback_id_1 = 5;
            $feedback_id_2 = 5;
            $feedback_id_3 = 5;
            $feedback_id_4 = 5;
            $feedback_id_5 = 5;
            $feedback_id_6 = 5;
            $feedback_rating_1 = $testimonials->where('testimonial_id', 1)->sum('rating');
            $feedback_count_1 = $testimonials->where('testimonial_id', 1)->count();
            if ($feedback_count_1) {
                $feedback_id_1 = $feedback_rating_1 / $feedback_count_1;
            }

            $feedback_rating_2 = $testimonials->where('testimonial_id', 2)->sum('rating');
            $feedback_count_2 = $testimonials->where('testimonial_id', 2)->count();
            if ($feedback_count_2) {
                $feedback_id_2 = $feedback_rating_2 / $feedback_count_2;
            }
            $feedback_rating_3 = $testimonials->where('testimonial_id', 3)->sum('rating');
            $feedback_count_3 = $testimonials->where('testimonial_id', 3)->count();
            if ($feedback_count_3) {
                $feedback_id_3 = $feedback_rating_3 / $feedback_count_3;
            }

            $feedback_rating_4 = $testimonials->where('testimonial_id', 4)->sum('rating');
            $feedback_count_4 = $testimonials->where('testimonial_id', 4)->count();
            if ($feedback_count_4) {
                $feedback_id_4 = $feedback_rating_4 / $feedback_count_4;
            }

            $feedback_rating_5 = $testimonials->where('testimonial_id', 5)->sum('rating');
            $feedback_count_5 = $testimonials->where('testimonial_id', 5)->count();
            if ($feedback_count_5) {
                $feedback_id_5 = $feedback_rating_5 / $feedback_count_5;
            }
            $feedback_rating_6 = $testimonials->where('testimonial_id', 6)->sum('rating');
            $feedback_count_6 = $testimonials->where('testimonial_id', 6)->count();
            if ($feedback_count_6) {
                $feedback_id_5 = $feedback_rating_5 / $feedback_count_6;
            }


            $overall_feedback = array(

                array(
                    "title" => 'This site is intuative and easy to use',
                    "value" => $feedback_id_1,
                ),

                array(
                    "title" => 'This site addresses my educational needs.',
                    "value" => $feedback_id_2,
                ),

                array(
                    "title" => 'Flexibility of choosing my courses.',
                    "value" => $feedback_id_3,
                ),

                array(
                    "title" => 'Flexibility of creating my class schedule.',
                    "value" => $feedback_id_4,
                ),
                array(
                    "title" => 'pricing competitiveness.',
                    "value" => $feedback_id_5,
                ),
                array(
                    "title" => 'Support team responsiveness.',
                    "value" => $feedback_id_6,
                ),

            );


            $feedbacks = [];
            $user_feedbacks = $testimonials->groupBy('sender_id');
            $flag = 0;
            //finding average rating of feedback
            $average_rating = 5;
            foreach ($user_feedbacks as $user_feedback) {
                // return $user_feedback;
                $counter = 0;
                $rating_sum = $user_feedback->where('sender_id', $user_feedback[0]->sender_id)->sum('rating');
                $total_reviews = $user_feedback->where('sender_id', $user_feedback[0]->sender_id)->count();
                if ($total_reviews > 0) {
                    $average_rating = $rating_sum / $total_reviews;
                }


                foreach ($user_feedback as $feedback) {
                    $counter++;
                    if ($counter == 1) {
                        array_push($feedbacks, [
                            'status' => $feedback->status,
                            'review' => $feedback->review,
                            'sender' => $feedback->sender,
                        ]);
                    }
                    // $feedbacks[$flag][$feedback->feedback->name] = $feedback->rating; //feedback rating
                    $feedbacks[$flag]['average_rating'] = $average_rating; //feedback average rating

                    $feedbacks[$flag]['feedbacks'][] = array(
                        "testimonial_id" => $feedback->testimonial->id,
                        "title" => $feedback->testimonial->name,
                        "value" => $feedback->rating,
                    );
                }
                $flag++;
            }

            $feedbacksArray = $feedbacks;
            $feedbacks = [];
            if ($request->has('trait')) {
                $stars = explode(",", $request->stars);
                foreach ($feedbacksArray as $feedback) {
                    foreach ($feedback['feedbacks'] as $feed) {
                        // return $feed;
                        foreach ($stars as $star) {
                            if ($feed['testimonial_id'] == $request->trait && $feed['value'] == $star) {
                                array_push($feedbacks, $feedback);
                                // break;
                            }
                        }
                    }
                }
            }

            // return $feedbacksArray;
            return response()->json([
                'status' => true,
                'message' => "Student Testimonials",
                'reviews_count' => count($user_feedbacks),
                'overall_stars' => $overall_stars,
                'overall_feedback' => $overall_feedback,
                'user_testimonials' => $feedbacks,
            ]);
        }

        // if he is teacher
        if ($request->feedback_by == 'teacher') {
            $testimonials = UserTestimonial::whereHas('sender', function ($q) {
                $q->where('role_name', 'teacher');
            })->with('testimonial', 'sender')->get();

            //overall average stars for teacher
            $five_stars = $testimonials->where('rating', 5)->count();
            $four_stars = $testimonials->where('rating', 4)->count();
            $three_stars = $testimonials->where('rating', 3)->count();
            $two_stars = $testimonials->where('rating', 2)->count();
            $one_stars = $testimonials->where('rating', 1)->count();

            $overall_stars = array(

                array(
                    "title" => "5",
                    "value" => $five_stars,
                ),

                array(
                    "title" => "4",
                    "value" => $four_stars,
                ),

                array(
                    "title" => "3",
                    "value" => $three_stars,
                ),

                array(
                    "title" => "2",
                    "value" => $two_stars,
                ),

                array(
                    "title" => "1",
                    "value" => $one_stars,
                ),
            );

            //overall average feedback
            $feedback_id_1 = 5;
            $feedback_id_2 = 5;
            $feedback_id_3 = 5;
            $feedback_id_4 = 5;
            $feedback_id_5 = 5;
            $feedback_rating_1 = $testimonials->where('testimonial_id', 7)->sum('rating');
            $feedback_count_1 = $testimonials->where('testimonial_id', 7)->count();
            if ($feedback_count_1) {
                $feedback_id_1 = $feedback_rating_1 / $feedback_count_1;
            }

            $feedback_rating_2 = $testimonials->where('testimonial_id', 8)->sum('rating');
            $feedback_count_2 = $testimonials->where('testimonial_id', 8)->count();
            if ($feedback_count_2) {
                $feedback_id_2 = $feedback_rating_2 / $feedback_count_2;
            }
            $feedback_rating_3 = $testimonials->where('testimonial_id', 9)->sum('rating');
            $feedback_count_3 = $testimonials->where('testimonial_id', 9)->count();
            if ($feedback_count_3) {
                $feedback_id_3 = $feedback_rating_3 / $feedback_count_3;
            }

            $feedback_rating_4 = $testimonials->where('testimonial_id', 10)->sum('rating');
            $feedback_count_4 = $testimonials->where('testimonial_id', 10)->count();
            if ($feedback_count_4) {
                $feedback_id_4 = $feedback_rating_4 / $feedback_count_4;
            }

            $feedback_rating_5 = $testimonials->where('testimonial_id', 11)->sum('rating');
            $feedback_count_5 = $testimonials->where('testimonial_id', 11)->count();
            if ($feedback_count_5) {
                $feedback_id_5 = $feedback_rating_5 / $feedback_count_5;
            }


            $overall_feedback = array(

                array(
                    "title" => 'MEtutors platform is intuitive and easy to use',
                    "value" => $feedback_id_1,
                ),

                array(
                    "title" => 'MEtutors meets my teaching requirements.',
                    "value" => $feedback_id_2,
                ),

                array(
                    "title" => 'Flexibility of managing my students and classes.',
                    "value" => $feedback_id_3,
                ),

                array(
                    "title" => 'Support team is responsible and responsive.',
                    "value" => $feedback_id_4,
                ),
                array(
                    "title" => 'MEtutors pays fairly compared to industry standards.',
                    "value" => $feedback_id_5,
                ),

            );


            $feedbacks = [];
            $user_feedbacks = $testimonials->groupBy('sender_id');
            $flag = 0;
            //finding average rating of feedback
            $average_rating = 5;
            foreach ($user_feedbacks as $user_feedback) {
                // return $user_feedback;
                $counter = 0;
                $rating_sum = $user_feedback->where('sender_id', $user_feedback[0]->sender_id)->sum('rating');
                $total_reviews = $user_feedback->where('sender_id', $user_feedback[0]->sender_id)->count();
                if ($total_reviews > 0) {
                    $average_rating = $rating_sum / $total_reviews;
                }


                foreach ($user_feedback as $feedback) {
                    $counter++;
                    if ($counter == 1) {
                        array_push($feedbacks, [
                            'status' => $feedback->status,
                            'review' => $feedback->review,
                            'sender' => $feedback->sender,
                        ]);
                    }
                    // $feedbacks[$flag][$feedback->feedback->name] = $feedback->rating; //feedback rating
                    $feedbacks[$flag]['average_rating'] = $average_rating; //feedback average rating

                    $feedbacks[$flag]['feedbacks'][] = array(
                        "title" => $feedback->testimonial->name,
                        "value" => $feedback->rating,
                    );
                }
                $flag++;
            }
            return response()->json([
                'status' => true,
                'message' => "Teachers Testimonials!",
                'reviews_count' => count($user_feedbacks),
                'overall_stars' => $overall_stars,
                'overall_feedback' => $overall_feedback,
                'user_testimonials' => $this->paginate($feedbacks, $request->per_page ?? 10),
            ]);
        }
    }


    public function edit_testimonial(Request $request, $user_id)
    {
        $testimonials = UserTestimonial::where('sender_id', $user_id)->get();

        $token_user = User::find($user_id);

        if ($token_user->role_name == 'teacher') {
            $params = Testimonial::select('id', 'name')->where('role_name', 'teacher')->get();
        }
        if ($token_user->role_name == 'student') {
            $params = Testimonial::select('id', 'name')->where('role_name', 'student')->get();
        }
        $counter = 0;

        foreach ($params as $param) {


            $feedback = $testimonials->where('testimonial_id', $param->id);

            // print_r($feedback);
            $param->rating = $feedback[$counter]->rating;
            $counter++;
        }

        return response()->json([
            'status' => true,
            'message' => "Required Params for Platform Feedback!",
            'review' => $testimonials[0]->review,
            'params' => $params,
        ]);
    }


    public function update_testimonial(Request $request, $user_id)
    {
        $testimonials = UserTestimonial::where('sender_id', $user_id)->get();

        $token_user = User::find($user_id);

        $decoded_feedbacks = json_decode(json_encode($request->feedbacks));


        foreach ($decoded_feedbacks as $feedback) {
            $platform = UserTestimonial::where('sender_id', $token_user->id)->where('testimonial_id', $feedback->testimonial_id)->first();
            $platform->review = $request->review;
            $platform->rating = $feedback->rating;
            $platform->update();
        }

        $user_feedbacks = UserTestimonial::with('sender', 'testimonial')->where('sender_id', $token_user->id)->get();

        $flag = 0;
        $counter = 0;
        //finding average rating of feedback
        $average_rating = 5;
        $feedbacks = array();



        foreach ($user_feedbacks as $feedback) {
            $counter++;
            if ($counter == 1) {

                $feedbacks['status'] = $feedback->status;
                $feedbacks['review'] = $feedback->review;
                $feedbacks['sender'] = $feedback->sender;
            }
            // $feedbacks[$flag][$feedback->feedback->name] = $feedback->rating; //feedback rating
            $feedbacks['average_rating'] = $average_rating; //feedback average rating

            $feedbacks['feedbacks'][] = array(
                "title" => $feedback->testimonial->name,
                "value" => $feedback->rating,
            );
        }



        return response()->json([
            'status' => true,
            'message' => "Your Feedback Updated Successfully!",
            'feedbacks' => $feedbacks,
        ]);
    }

    public function del_coursefeedback(Request $request)
    {
        $rules = [
            'sender_id' =>  'required|integer',
            'course_id' =>  'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ]);
        }
        $user_feedbacks = UserFeedback::where('sender_id', $request->sender_id)->where('course_id', $request->course_id)->get();

        foreach ($user_feedbacks as $user_feedback) {
            $user_feedback->delete();
        }

        return response()->json([
            'status' => true,
            'message' => "User Feedback deleted Successfully!",
        ]);
    }

    public function del_usertestimonial($sender_id)
    {
        $user_testimonial = UserTestimonial::where('sender_id', $sender_id)->get();

        foreach ($user_testimonial as $user_test) {
            $user_test->delete();
        }

        return response()->json([
            'status' => true,
            'message' => "User Testimonial deleted Successfully!",
        ]);
    }

    public function teacher_ratings()
    {
        $teachers = User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar', 'kudos_points')->where('role_name', 'teacher')->get();

        foreach ($teachers as $teacher) {
            $rating_sum = 0;
            $average_rating = 0;
            $rating_sum = UserFeedback::where('reciever_id', $teacher->id)->sum('rating');
            if ($rating_sum > 0) {
                $total_reviews = UserFeedback::where('reciever_id', $teacher->id)->count();
                $average_rating = $rating_sum / $total_reviews;
            }

            $teacher->average_rating = $average_rating;
        }

        return response()->json([
            'status' => true,
            'message' => "Teachers rating!",
            'teachers' => $teachers,
        ]);
    }

    public function reassign_teacher(Request $request)
    {
        // $token_1 = JWTAuth::getToken();
        // $token_user = JWTAuth::toUser($token_1);

        $rules = [
            'course_id' =>  'required|integer',
            'teacher_id' =>  'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ]);
        }

        $course = Course::findOrFail($request->course_id);
        $classes = AcademicClass::where('course_id', $request->course_id)->where('status', 'canceled')->get();

        $counter = 1;
        foreach ($classes as  $class) {

            if ($counter == 1) {
                $class->title = "Introduction|class1";
                $class->lesson_name = "introduction";
            } else {
                $class->title = "class" . $counter;
                $class->lesson_name = "lesson" . $counter;
            }

            /// Curl Implementation
            $apiURL = 'https://api.braincert.com/v2/schedule';
            $postInput = [
                'apikey' => 'xKUyaLJHtbvBUtl3otJc',
                'title' =>  'Introduction|class1',
                'timezone' => 90,
                'start_time' => Carbon::parse($class->start_time)->format('G:i a'),
                'end_time' => Carbon::parse($class->end_time)->format('G:i a'),
                'date' => Carbon::parse($class->start_date)->format('Y-m-d'),
                'currency' => "USD",
                'ispaid' => null,
                'is_recurring' => 0,
                'repeat' => 0,
                'weekdays' => $course->weekdays,
                'end_date' => Carbon::parse($class->end_date)->format('Y-m-d'),
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
                $class->class_id = $responseBody['class_id'];
                $class->status = "scheduled";
                $class->teacher_id = $request->teacher_id;
                $class->update();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $responseBody['error'],
                ], 400);
            }
            $counter++;
        }

        $class_rooms = ClassRoom::where('course_id', $request->course_id)->get();
        foreach ($class_rooms as  $class_room) {
            $class_room->teacher_id = $request->teacher_id;
            $class_room->status = 'reassigned_by_admin';
            $class_room->update();
        }

        $course = Course::find($request->course_id);
        $course->teacher_id = $request->teacher_id;
        $course->status = 'reassigned_by_admin';
        $course->update();

        $course = Course::with('classes')->find($request->course_id);
        $student = User::findOrFail($course->student_id);
        $teacher = User::findOrFail($course->teacher_id);

        // //Emails and notifications
        // event(new AssignTeacherEvent($student->id, $student, $course, "Teacher has been assigned to course"));
        // event(new AssignTeacherEvent($teacher->id, $teacher, $course, "You have been assigned a new course"));
        // dispatch(new AssignTeacherJob($student->id, $student, $course, "Teacher has been assigned to course"));
        // dispatch(new AssignTeacherJob($teacher->id, $teacher, $course, "You have been assigned a new course"));

        return response()->json([
            'status' => true,
            'message' => "Teacher Assigned Successfully!",
            'course' => $course,
        ]);
    }

    public function reassignTeacher(Request $request)
    {
        $rules = [
            'course_id' =>  'required|integer',
            'teacher_id' =>  'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ]);
        }

        $classes = AcademicClass::where('course_id', $request->course_id)->where('status', 'rejected')->get();
        $course = Course::find($request->course_id);

        $counter = 1;
        foreach ($classes as  $class) {

            if ($counter == 1) {
                $class->title = "Introduction|class1";
                $class->lesson_name = "introduction";
            } else {
                $class->title = "class" . $counter;
                $class->lesson_name = "lesson" . $counter;
            }



            /// Curl Implementation
            $apiURL = 'https://api.braincert.com/v2/schedule';
            $postInput = [
                'apikey' => 'xKUyaLJHtbvBUtl3otJc',
                'title' =>  'Introduction|class1',
                'timezone' => 90,
                'start_time' => Carbon::parse($class->start_time)->format('G:i a'),
                'end_time' => Carbon::parse($class->end_time)->format('G:i a'),
                'date' => Carbon::parse($class->start_date)->format('Y-m-d'),
                'currency' => "USD",
                'ispaid' => null,
                'is_recurring' => 0,
                'repeat' => 0,
                'weekdays' => $course->weekdays,
                'end_date' => Carbon::parse($class->end_date)->format('Y-m-d'),
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
                $class->class_id = $responseBody['class_id'];
                $class->status = "scheduled";
                $class->teacher_id = $request->teacher_id;
                $class->update();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $responseBody['error'],
                ], 400);
            }
            $counter++;
        }

        $class_rooms = ClassRoom::where('course_id', $request->course_id)->get();
        foreach ($class_rooms as  $class_room) {
            $class_room->teacher_id = $request->teacher_id;
            $class_room->status = 'reassigned_by_admin';
            $class_room->update();
        }


        $course->teacher_id = $request->teacher_id;
        $course->status = 'reassigned_by_admin';
        $course->update();

        $course = Course::with('classes')->find($request->course_id);

        return response()->json([
            'status' => true,
            'message' => "Teacher Assigned Successfully!",
            'course' => $course,
        ]);
    }

    public function teachers(Request $request)
    {


        $interview = TeacherInterviewRequest::pluck("user_id");
        // if (isset($request->per_page)) {
        $teachers = User::with('country', 'teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject', 'teacher_interview_request')->whereIn('id', $interview)->get();
        // } else {
        //     $teachers = User::with('country', 'teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject', 'teacher_interview_request')->whereIn('id', $interview)->paginate(10);
        // }

        $all_teachers = User::whereIn('id', $interview)->count();
        $active_teachers = User::where('status', 'active')
            ->where('role_name', 'teacher')
            ->where('admin_approval', 'approved')
            ->whereIn('id', $interview)
            ->count();

        $inactive_teachers = User::where('status', 'inactive')
            ->where('role_name', 'teacher')
            ->where('admin_approval', 'approved')
            ->whereIn('id', $interview)
            ->count();

        $pending_teachers = User::where('status', 'pending')
            ->where('role_name', 'teacher')
            ->whereIn('id', $interview)
            ->count();

        $suspended_teachers = User::where('status', 'suspended')
            ->where('role_name', 'teacher')
            ->whereIn('id', $interview)
            ->count();

        $message = 'All teachers!';

        // if ($request->has('inactive')) {
        //     $teachers = User::where('role_name', 'teacher')->where('status', 'pending')->get();
        //     $message = 'All Inactive teachers!';
        // }
        // if ($request->has('active')) {
        //     $teachers = User::where('role_name', 'teacher')->where('status', 'active')->get();
        //     $message = 'All Active teachers!';
        // }
        // if ($request->has('suspended')) {
        //     $teachers = User::where('role_name', 'teacher')->where('status', 'inactive')->get();
        //     $message = 'All Suspended teachers!';
        // }

        if ($request->has('search')) {
            // return "dfs";
            $teachers = User::with('country', 'teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject', 'teacher_interview_request')
                ->whereIn('id', $interview)
                ->where(function ($query) use ($request) {
                    $query->where('first_name', 'LIKE', "%$request->search%")
                        ->orWhere('last_name', 'LIKE', "%$request->search%")
                        ->orWhere('id_number', 'LIKE', $request->search)
                        ->orWhere('status', 'LIKE', "%$request->search%")
                        ->orWhere('middle_name', 'LIKE', "%$request->search%")
                        ->orWhere('nationality', 'LIKE', "%$request->search%");
                })->get();
        }




        return response()->json([
            'status' => true,
            'message' => $message,
            'teachers' => $this->paginate($teachers, $request->per_page ?? 10),
            'all_teachers' => $all_teachers,
            'active_teachers' => $active_teachers,
            'inactive_teachers' => $inactive_teachers,
            'pending_teachers' => $pending_teachers,
            'suspended_teachers' => $suspended_teachers,
        ]);
    }

    public function rejected_teachers(Request $request)
    {


        if ($request->has('search')) {

            $teachers = User::with('teacher_interview_request')
                ->where('role_name', 'teacher')
                ->where('status', 'rejected')
                ->where(function ($query) use ($request) {
                    $query->where('first_name', 'LIKE', "%$request->search%")
                        ->orWhere('last_name', 'LIKE', "%$request->search%")
                        ->orWhere('id_number', 'LIKE', $request->search)
                        ->orWhere('status', 'LIKE', "%$request->search%")
                        ->orWhere('middle_name', 'LIKE', "%$request->search%")
                        ->orWhere('nationality', 'LIKE', "%$request->search%");
                })
                ->get();
        } else {

            $teachers = User::with('teacher_interview_request')
                ->where('role_name', 'teacher')
                ->where('status', 'rejected')
                ->get();
        }

        return response()->json([
            'status' => true,
            'message' => "Rejected Teachers!",
            'teachers_count' => count($teachers),
            'teachers' => $this->paginate($teachers, $request->per_page ?? 10),

        ]);
    }

    public function suspended_teachers(Request $request)
    {

        // if (isset($request->per_page)) {

        $teachers = User::with('teacher_interview_request')
            ->where('role_name', 'teacher')
            ->where('status', 'suspended')
            ->get();
        // } else {

        //     $teachers = User::with('teacher_interview_request')->where('role_name', 'teacher')->where('status', 'suspended')->paginate(10);
        // }


        return response()->json([
            'status' => true,
            'message' => "Suspended Teachers!",
            'teachers_count' => count($teachers),
            'teachers' => $this->paginate($teachers, $request->per_page ?? 10),

        ]);
    }

    public function pending_teachers(Request $request)
    {

        $interview = TeacherInterviewRequest::where('status', 'pending')->pluck("user_id");

        if ($request->has('search')) {

            $pending_teachers = User::with('teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject', 'teacher_interview_request')
                ->whereIn('id', $interview)
                ->where(function ($query) use ($request) {
                    $query->where('first_name', 'LIKE', "%$request->search%")
                        ->orWhere('last_name', 'LIKE', "%$request->search%")
                        ->orWhere('id_number', 'LIKE', $request->search)
                        ->orWhere('status', 'LIKE', "%$request->search%")
                        ->orWhere('middle_name', 'LIKE', "%$request->search%")
                        ->orWhere('nationality', 'LIKE', "%$request->search%");
                })
                ->get();

            $rejected_teachers = User::with('teacher_interview_request')
                ->where('role_name', 'teacher')
                ->where('status', 'rejected')
                ->where(function ($query) use ($request) {
                    $query->where('first_name', 'LIKE', "%$request->search%")
                        ->orWhere('last_name', 'LIKE', "%$request->search%")
                        ->orWhere('id_number', 'LIKE', $request->search)
                        ->orWhere('status', 'LIKE', "%$request->search%")
                        ->orWhere('middle_name', 'LIKE', "%$request->search%")
                        ->orWhere('nationality', 'LIKE', "%$request->search%");
                })
                ->get();
        } else {

            $pending_teachers = User::with('teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject', 'teacher_interview_request')
                ->whereIn('id', $interview)
                ->get();

            $rejected_teachers = User::with('teacher_interview_request')
                ->where('role_name', 'teacher')
                ->where('status', 'rejected')
                ->get();
        }


        return response()->json([
            'status' => true,
            'message' => "Rejected Teachers!",
            'pending_teachers_count' => count($pending_teachers),
            'pending_teachers' => $this->paginate($pending_teachers, $request->per_page ?? 10),
            'rejected_teachers_count' => count($rejected_teachers),
            'rejected_teachers' => $this->paginate($rejected_teachers, $request->per_page ?? 10),

        ]);
    }

    public function current_teachers(Request $request)
    {


        if ($request->has('search')) {

            $teachers = User::with('teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject')
                ->where('role_name', 'teacher')
                ->whereIn('status', ['active', 'inactive'])
                ->where(function ($query) use ($request) {
                    $query->where('first_name', 'LIKE', "%$request->search%")
                        ->orWhere('last_name', 'LIKE', "%$request->search%")
                        ->orWhere('id_number', 'LIKE', $request->search)
                        ->orWhere('status', 'LIKE', "%$request->search%")
                        ->orWhere('middle_name', 'LIKE', "%$request->search%")
                        ->orWhere('nationality', 'LIKE', "%$request->search%");
                })
                ->get();
        } else {

            $teachers = User::with('teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject')
                ->where('role_name', 'teacher')
                ->whereIn('status', ['active', 'inactive'])
                ->get();
        }





        $pluckedteachers = $teachers->pluck('id');

        if ($request->has('status') && $request->status == "active") {

            if ($request->has('search')) {
                $teachers = User::with('teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject')
                    ->where('role_name', 'teacher')
                    ->where('status', 'active')
                    ->where(function ($query) use ($request) {
                        $query->where('first_name', 'LIKE', "%$request->search%")
                            ->orWhere('last_name', 'LIKE', "%$request->search%")
                            ->orWhere('id_number', 'LIKE', $request->search)
                            ->orWhere('status', 'LIKE', "%$request->search%")
                            ->orWhere('middle_name', 'LIKE', "%$request->search%")
                            ->orWhere('nationality', 'LIKE', "%$request->search%");
                    })
                    ->get();
            } else {
                $teachers = User::with('teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject')
                    ->where('role_name', 'teacher')
                    ->where('status', 'active')
                    ->get();
            }




            $pluckedteachers = $teachers->pluck('id');
        }
        if ($request->has('status') && $request->status == "inactive") {
            if ($request->has('search')) {
                $teachers = User::with('teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject')
                    ->where('role_name', 'teacher')
                    ->where('status', 'inactive')
                    ->where(function ($query) use ($request) {
                        $query->where('first_name', 'LIKE', "%$request->search%")
                            ->orWhere('last_name', 'LIKE', "%$request->search%")
                            ->orWhere('id_number', 'LIKE', $request->search)
                            ->orWhere('status', 'LIKE', "%$request->search%")
                            ->orWhere('middle_name', 'LIKE', "%$request->search%")
                            ->orWhere('nationality', 'LIKE', "%$request->search%");
                    })
                    ->get();
            } else {
                $teachers = User::with('teacherQualifications', 'teacherSpecifications', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject')
                    ->where('role_name', 'teacher')
                    ->where('status', 'inactive')
                    ->get();
            }




            $pluckedteachers = $teachers->pluck('id');
        }

        $courses = Course::all();
        $running_courses = $courses->whereIn('status', ['pending', 'active', 'inprogress'])->whereIn('teacher_id', $pluckedteachers);
        $running_teachers = $running_courses->unique('teacher_id');
        $runing_teachers = [];
        $engaged_teachers = [];
        foreach ($running_teachers as $teacher) {
            array_push($runing_teachers, $teacher->teacher_id);
            array_push($engaged_teachers, $teacher);
        }


        $available_teachers = [];
        foreach ($teachers as $teacher) {
            if (!(in_array($teacher->id, $runing_teachers))) {
                array_push($available_teachers, $teacher);
            }
        }

        $inactive_teachers = User::where('role_name', 'teacher')->where('status', 'inactive')->get();

        //calculating the ratings
        foreach ($teachers as $teacher) {
            #for teacher
            $teacher_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $teacher->id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $teacher->id)->count();
            if ($total_reviews > 0) {
                $teacher_rating = $rating_sum / $total_reviews;
            }

            $bookings = Course::where('teacher_id', $teacher->id)->get();

            $teacher->teacher_rating = $teacher_rating;
            $teacher->bookings = count($bookings);
            $teacher->amount = $bookings->sum('total_price');
        }


        if ($request->has('status')) {
            // if ($request->has('active') || $request->has('inactive')) {
            return response()->json([
                'status' => true,
                'message' => "Current Teachers",
                'total' => count($teachers),
                'available' => count($available_teachers),
                'engaged' => count($engaged_teachers),
                // 'inactive' => count($inactive_teachers),
                'teachers' => $this->paginate($teachers, $request->per_page ?? 10),

            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Current Teachers",
                'total' => count($teachers),
                'available' => count($available_teachers),
                'engaged' => count($engaged_teachers),
                'inactive' => count($inactive_teachers),
                'teachers' => $this->paginate($teachers, $request->per_page ?? 10),

            ]);
        }
    }


    public function schedule_meeting(Request $request)
    {

        $rules = [
            'interview_request_id' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
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

        // return $request->all();

        $int = TeacherInterviewRequest::find($request->interview_request_id);


        $apiURL = 'https://api.braincert.com/v2/schedule';
        $postInput = [
            'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
            'title' =>  'Interview with teacher',
            'timezone' => 90,
            'start_time' => Carbon::parse($request->start_time)->format('g:ia'),
            'end_time' => Carbon::parse($request->end_time)->format('g:ia'),
            'date' => Carbon::parse($request->date)->format('Y-m-d'),
            'currency' => "USD",
            'record' => 0,
            'isRecordingLayout ' => 1,
            'isVideo  ' => 1,
            'isBoard ' => 0,
            'isScreenshare ' => 1,
            'isPrivateChat  ' => 0,
            'format ' => "json",
        ];

        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);
        if ($responseBody['status'] == "ok") {

            $int->meeting_id = $responseBody['class_id'];
            $int->status = "scheduled";
            $int->time_for_interview = $request->start_time;
            $int->date_for_interview = $request->date;

            $int->update();

            $interviewRequest = TeacherInterviewRequest::with('user')->find($request->interview_request_id);
            $admin = User::where('role_name', 'admin')->first();
            $user = $interviewRequest['user'];
            $admin_message = "Meeting Scheduled Successfully!";
            $teacher_message = "Your meeting has been scheduled";


            //*********** Sending Email to teacher  ************\\
            $user_email = $user->email;
            $custom_message = "Your meeting has been scheduled";
            $to_email = $user_email;

            $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'interview_request' => $interviewRequest, 'user' => $user);

            Mail::send('email.meeting_scheduled', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('Interview Request!');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
            //******** Email ends **********//

            //*********** Sending Email to admin  ************\\
            $user_email = $admin->email;
            $custom_message = "Meeting Scheduled Successfully!";
            $to_email = $user_email;

            $data = array('email' =>  $user_email, 'custom_message' =>  $custom_message, 'interview_request' => $interviewRequest, 'user' => $admin);

            Mail::send('email.meeting_scheduled', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('Interview Request!');
                $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
            });
            //******** Email ends **********//

            // //Emails and Notifications
            // event(new MeetingScheduledEvent($user->id, $user, $teacher_message, $interviewRequest));
            // event(new MeetingScheduledEvent($admin->id, $admin, $admin_message, $interviewRequest));
            // dispatch(new MeetingScheduledJob($user->id, $user, $teacher_message, $interviewRequest));
            // dispatch(new MeetingScheduledJob($admin->id, $admin, $admin_message, $interviewRequest));

            return response()->json([
                'success' => true,
                'message' => "Meeting Scheduled Successfully",

            ]);
        } else {

            return response()->json([
                'success' => true,
                'message' => $responseBody,

            ], 400);
        }

        return $int;
    }


    public function join_meeting(Request $request, $id)
    {

        $int = TeacherInterviewRequest::find($request->interview_request_id);


        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($int == null) {
            return response()->json([
                'status' => false,
                'message' => 'meeting not found'
            ], 400);
        }

        if ($token_user->role_name == "admin") {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $apiURL = 'https://api.braincert.com/v2/getclasslaunch';
        $postInput = [
            'apikey' => 'xKUyaLJHtbvBUtl3otJc',
            'class_title' =>  'Admin Interview',
            'class_id' => $int->meeting_id,
            'userId' => $token_user->id,
            'userName' => $token_user->first_name . " " . $token_user->last_name,
            'isTeacher' => $flag,
            'lessonName' => 'interview',
            'courseName' => "hiring",
        ];

        $client = new Client();
        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody['status'] == 'error') {
            return response()->json([
                'status' => false,
                'message' => $responseBody['error'] == 'Class has ended' ? 'Interview has ended' :  $responseBody['error'],
                'error' => $responseBody['error'],
            ], 400);
        } else {


            return response()->json([
                'status' => true,
                'meeting_url' => $responseBody['launchurl'],
            ]);
        }
    }

    public function subject_teachers($subject_id)
    {
        $subject_teachers = TeacherSubject::where('subject_id', $subject_id)->pluck("user_id");

        $teachers = User::select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar')->whereIn('id', $subject_teachers)->get();

        return response()->json([
            'status' => true,
            'message' => "Active Teachers related to Subject!",
            'teachers_count' => count($teachers),
            'teachers' => $teachers,
        ]);
    }

    public function subject_activeclasses($subject_id)
    {
        // $subject_courses = Course::whereHas('classes', function ($q) {
        //     $q->where('status', 'inprogress');
        // })->with('classes', function ($q) {
        //     $q->where('status', 'inprogress');
        // })->where('subject_id', $subject_id)->get();

        $subject_courses = Course::where('subject_id', $subject_id)->pluck('id');

        $classes = AcademicClass::where('status', 'inprogress')->whereIn('course_id', $subject_courses)->get();

        return response()->json([
            'status' => true,
            'message' => "Active Classes related to Subject!",
            'classes_count' => count($classes),
            'classes' => $classes,
        ]);
    }

    public function subject_upcomingclasses($subject_id)
    {
        $subject_courses = Course::where('subject_id', $subject_id)->pluck('id');

        $classes = AcademicClass::where('status', 'scheduled')->whereIn('course_id', $subject_courses)->get();

        return response()->json([
            'status' => true,
            'message' => "Upcoming Classes related to Subject!",
            'classes_count' => count($classes),
            'classes' => $classes,
        ]);
    }

    public function subject_canceledclasses($subject_id)
    {
        // $subject_canceled_classes = Course::whereHas('canceled_classes')->with('canceled_classes')->where('subject_id', $subject_id)->get();

        $subject_courses = Course::where('subject_id', $subject_id)->pluck('id');

        $classes = CanceledCourse::whereIn('course_id', $subject_courses)->get();
        return response()->json([
            'status' => true,
            'message' => "Canceled Classes related to Subject!",
            'classes_count' => count($classes),
            'classes' => $classes,
        ]);
    }

    public function subject_rescheduledclasses($subject_id)
    {
        $subject_courses = Course::where('subject_id', $subject_id)
            ->pluck('id');

        $classes = RescheduleClass::whereIn('course_id', $subject_courses)->get();
        return response()->json([
            'status' => true,
            'message' => "Rescheduled Classes related to Subject!",
            'classes_count' => count($classes),
            'classes' => $classes,
        ]);
    }

    public function program_subjects(Request $request, $program_id)
    {

        $subjects = Subject::with('program', 'country', 'field')->where('program_id', $program_id)->get();
        $field_of_studies = FieldOfStudy::with('program', 'country')->where('program_id', $program_id)->get();

        if ($program_id == 3) {
            $rules = [
                'country_id' => 'required',
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

            $country_id = $request->country_id;
            $subjects = Subject::with('program', 'country', 'field')->where('program_id', $program_id)->where('country_id', $country_id)->get();
            $field_of_studies = FieldOfStudy::with('program', 'country')->where('program_id', $program_id)->where('country_id', $country_id)->get();
        }

        return response()->json([
            'status' => true,
            'program' => $subjects[0]->program,
            'subjects' => $subjects,
            'field_of_studies' => $field_of_studies,
        ]);
    }

    public function workforce_capacity(Request $request)
    {
        $subjects_array = [];
        $courses_subjects = Course::pluck('subject_id')->all();
        $courses = Course::all();
        $final_subjects = [];
        //----- Workforce Filters starts -------
        $current_date = Carbon::now()->format('Y-m-d');
        if ($request->has('week')) {
            $endDate = Carbon::today()->subWeek($request->week);
            $courses_subjects = Course::whereBetween('created_at', [$endDate, $current_date])->pluck('subject_id')->all();
            $courses = Course::whereBetween('created_at', [$endDate, $current_date])->get();
        }
        if ($request->has('month')) {
            $endDate = Carbon::today()->subMonth($request->month);
            $courses_subjects = Course::whereBetween('created_at', [$endDate, $current_date])->pluck('subject_id')->all();
            $courses = Course::whereBetween('created_at', [$endDate, $current_date])->get();
        }
        if ($request->has('year')) {
            $endDate = Carbon::today()->subYear($request->year);
            $courses_subjects = Course::whereBetween('created_at', [$endDate, $current_date])->pluck('subject_id')->all();
            $courses = Course::whereBetween('created_at', [$endDate, $current_date])->get();
        }
        if ($request->has('teacher')) {
            $courses_subjects = Course::where('teacher_id', $request->teacher)->pluck('subject_id')->all();
            $courses = Course::where('teacher_id', $request->teacher)->get();
        }
        // if ($request->has('date')) {
        //     $date = Carbon::parse($request->date);
        //     $courses_subjects = Course::where('created_at',  $date)->pluck('subject_id')->all();
        //     return $courses = Course::where('created_at', 'LIKE', '%$date%')->get();
        // }
        //------- Workforce Filters ends -------

        //Unique subjects
        foreach ($courses_subjects as $subject) {
            if (!(in_array($subject, $subjects_array))) {
                array_push($subjects_array, $subject);
            }
        }

        // return $subjects_array;

        foreach (array_unique($subjects_array) as $subject) {
            $hired_teachers = 0;
            // array_push($subjects_array, $subject);

            $hired_teachers = $courses->where('subject_id', $subject)->pluck('teacher_id')->unique()->count();

            $Subject = Subject::with('program', 'field')
                ->withCount(['available_teachers' => function ($q) use ($subject) {
                    $q->where('subject_id', $subject);
                }])
                ->find($subject);

            //Seach Query
            if ($request->has('search') && $request->search != '') {

                $Subject = Subject::with('program', 'field')
                    ->whereHas('program', function ($q) use ($request) {
                        // $q->where(function ($qu) use ($request) {
                        $q->where('name', 'LIKE', "%$request->search%")
                            ->orWhere('description', 'LIKE', "%$request->search%")
                            ->orWhere('code', $request->search);
                        // })->get();
                    })
                    ->OrWhereHas('field', function ($q) use ($request) {
                        // $q->where(function ($qu) use ($request) {
                        $q->where('name', 'LIKE', "%$request->search%")
                            ->orWhere('country_id', $request->search)
                            ->orWhere('status', 'LIKE', "%$request->search%")
                            ->orWhere('program_id', $request->search);
                        // })->get();
                    })
                    ->withCount(['available_teachers' => function ($q) use ($subject) {
                        $q->where('subject_id', $subject);
                    }])
                    ->find($subject);
            }

            $capacity = 100;
            if ($Subject->available_teachers_count > 0 && $hired_teachers > 0) {
                $capacity = 100 - (($hired_teachers / $Subject->available_teachers_count) * 100);
            }




            array_push($final_subjects, [
                "subject" => $Subject,
                'total_bookings' => $courses->where('subject_id', $subject)->count(),
                'total_revenue' => $courses->where('subject_id', $subject)->sum('total_price'),
                'hired_tutors' => $hired_teachers,
                'capacity' => $capacity,
            ]);
        }

        // $teacher_subjects = TeacherSubject::whereIn('subject_id', $subjects_array)->get();

        return response()->json([
            'status' => true,
            'total_subjects' => count($final_subjects),
            // 'subjects' => $subjects_array,
            'subjects' => $this->paginate($final_subjects, $request->per_page ?? 10),
            // 'subjects' => $final_subjects,
        ]);
    }

    public function available_teachers($subject_id)
    {
        $subject = Subject::with(['available_teachers' => function ($q) use ($subject_id) {
            $q->where('subject_id', $subject_id);
        }])
            ->find($subject_id);

        $hired_teachers = Course::where('subject_id', $subject_id)->whereIn('status', ['active', 'pending', 'reassigned', 'inprogress'])->pluck('teacher_id')->toArray();
        $teachers = $subject['available_teachers']->pluck('user_id');

        $teachers_available = [];
        foreach ($teachers as $teacher) {
            if (!(in_array($teacher, $hired_teachers))) {
                array_push($teachers_available, $teacher);
            }
        }
        $available_teachers = User::whereIn('id', $teachers_available)->get();

        return response()->json([
            'status' => true,
            'message' => 'Available teachers!',
            'teachers' => $available_teachers,
        ]);
    }

    public function hired_teachers($subject_id)
    {
        $hired_teachers = Course::where('subject_id', $subject_id)->whereIn('status', ['active', 'pending', 'reassigned', 'inprogress'])->pluck('teacher_id');
        $teachers = User::whereIn('id', $hired_teachers)->get();

        return response()->json([
            'status' => true,
            'message' => 'Hired teachers!',
            'teachers' => $teachers,
        ]);
    }

    public function subject_bookings($subject_id)
    {
        $subject = Subject::with(['courses'  => function ($q) {
            $q->with('teacher', 'student', 'program', 'field', 'subject');
            // $q->where('status', 'active')->with('teacher');
        }])
            ->findorfail($subject_id);

        $subject->total_bookings = $subject->courses->count();

        return response()->json([
            'status' => true,
            'message' => "All Bookings of given Subject!",
            'subject' => $subject,
        ]);
    }

    public function classroom(Request $request)
    {
        $courses = Course::with('student', 'teacher', 'program')
            ->orderBy('id', 'desc')->get();

        if ($request->has('status') && $request->status == "running") {
            $courses = Course::with('student', 'teacher', 'program')->where('status', 'inprogress')->orderBy('id', 'desc')->get();
        }
        if ($request->has('status') && $request->status == "completed") {
            $courses = Course::with('student', 'teacher', 'program')->where('status', 'completed')->orderBy('id', 'desc')->get();
        }


        //calculating the ratings
        foreach ($courses as $course) {
            #for student
            $student_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->student_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->student_id)->count();
            if ($total_reviews > 0) {
                $student_rating = $rating_sum / $total_reviews;
            }
            $course->student_rating = $student_rating;

            #for teacher
            $teacher_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
            if ($total_reviews > 0) {
                $teacher_rating = $rating_sum / $total_reviews;
            }
            $course->teacher_rating = $teacher_rating;
        }

        $completed_courses = $courses->where('status', 'completed')->count();
        $running_courses = $courses->where('status', 'inprogress')->count();
        $cancelled_courses = CanceledCourse::all();

        if ($request->has('status')) {
            return response()->json([
                'status' => true,
                'message' => "Bookings",
                'courses' => $this->paginate($courses, $request->per_page ?? 10),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => "Bookings",
            'all_courses' => count($courses),
            'completed_courses' => $completed_courses,
            'running_courses' => $running_courses,
            'cancelled_courses' => count($cancelled_courses),
            'courses' => $this->paginate($courses, $request->per_page ?? 10),
        ]);
    }

    public function student_feedbacks($course_id)
    {
        $course = Course::findOrFail($course_id);
        $user_feedbacks = UserFeedback::with('feedback', 'sender', 'reciever')
            ->where('receiver_id', $course->teacher_id)
            ->where('sender_id', '!=', $course->teacher_id)
            ->where('course_id', $course->id)
            ->get();

        $feedbacks_count = $user_feedbacks->pluck('sender_id')->unique()->count();

        //overall average stars for teacher
        $five_stars = $user_feedbacks->where('rating', 5)->count();
        $four_stars = $user_feedbacks->where('rating', 4)->count();
        $three_stars = $user_feedbacks->where('rating', 3)->count();
        $two_stars = $user_feedbacks->where('rating', 2)->count();
        $one_stars = $user_feedbacks->where('rating', 1)->count();

        //overall average feedback
        $feedback_id_1 = 5;
        $feedback_id_2 = 5;
        $feedback_id_3 = 5;
        $feedback_id_4 = 5;
        $feedback_rating_1 = $user_feedbacks->where('feedback_id', 1)->sum('rating');
        $feedback_count_1 = $user_feedbacks->where('feedback_id', 1)->count();
        if ($feedback_count_1) {
            $feedback_id_1 = $feedback_rating_1 / $feedback_count_1;
        }

        $feedback_rating_2 = $user_feedbacks->where('feedback_id', 2)->sum('rating');
        $feedback_count_2 = $user_feedbacks->where('feedback_id', 2)->count();
        if ($feedback_count_2) {
            $feedback_id_2 = $feedback_rating_2 / $feedback_count_2;
        }
        $feedback_rating_3 = $user_feedbacks->where('feedback_id', 3)->sum('rating');
        $feedback_count_3 = $user_feedbacks->where('feedback_id', 3)->count();
        if ($feedback_count_3) {
            $feedback_id_3 = $feedback_rating_3 / $feedback_count_3;
        }

        $feedback_rating_4 = $user_feedbacks->where('feedback_id', 4)->sum('rating');
        $feedback_count_4 = $user_feedbacks->where('feedback_id', 4)->count();
        if ($feedback_count_4) {
            $feedback_id_4 = $feedback_rating_4 / $feedback_count_4;
        }

        $feedbacks = [];
        $user_feedbacks = $user_feedbacks->groupBy('sender_id');
        $flag = 0;
        //finding average rating of feedback
        $average_rating = 5;
        foreach ($user_feedbacks as $user_feedback) {
            $counter = 0;
            $rating_sum = $user_feedback->where('sender_id', $user_feedback[0]->sender_id)->sum('rating');
            $total_reviews = $user_feedback->where('sender_id', $user_feedback[0]->sender_id)->count();
            if ($total_reviews > 0) {
                $average_rating = $rating_sum / $total_reviews;
            }


            foreach ($user_feedback as $feedback) {
                $counter++;
                if ($counter == 1) {
                    array_push($feedbacks, [
                        'review' => $feedback->review,
                        'sender' => $feedback->sender,
                    ]);
                }
                // $feedbacks[$flag][$feedback->feedback->name] = $feedback->rating; //feedback rating
                $feedbacks[$flag]['average_rating'] = $average_rating; //feedback average rating

                $feedbacks[$flag]['feedbacks'][] = array(

                    "title" => $feedback->feedback->name,
                    "value" => $feedback->rating,
                );
            }
            $flag++;
        }

        $overall_feedback = array(

            array(
                "title" => 'Expert in the subject',
                "value" => $feedback_id_1,
            ),

            array(
                "title" => 'Present Complex Topics clearly and easily',
                "value" => $feedback_id_2,
            ),

            array(
                "title" => 'Skillfull in engaging students',
                "value" => $feedback_id_3,
            ),

            array(
                "title" => 'Always on time',
                "value" => $feedback_id_4,
            ),


        );

        $overall_stars = array(

            array(
                "title" => "5",
                "value" => $five_stars,
            ),

            array(
                "title" => "4",
                "value" => $four_stars,
            ),

            array(
                "title" => "3",
                "value" => $three_stars,
            ),

            array(
                "title" => "2",
                "value" => $two_stars,
            ),

            array(
                "title" => "1",
                "value" => $one_stars,
            ),
        );

        $feedback_rating_plus = $feedback_id_1 + $feedback_id_2 + $feedback_id_3 + $feedback_id_4;

        $feedback_rating = $feedback_rating_plus / 4;

        return response()->json([
            'status' => true,
            'message' => "Student feedback on Course!",
            'feedbacks_count' => $feedbacks_count,
            'feedback_rating' => $feedback_rating,
            'overall_feedback' => $overall_feedback,
            'overall_stars' => $overall_stars,
            'student_feedbacks' => $feedbacks,
        ]);
    }

    public function previous_teachers($course_id)
    {
        $canceled_courses = CanceledCourse::with('teacher')->where('course_id', $course_id)->get();
        $previous_teachers = [];
        foreach ($canceled_courses as $teacher) {
            array_push($previous_teachers, $teacher['teacher']);
        }

        $previous_teachers = array_unique($previous_teachers);
        return response()->json([
            'status' => true,
            'message' => "Previous teachers!",
            'previous_teachers' => $previous_teachers,
        ]);
    }

    public function cancelledCourses(Request $request)
    {


        $courses = CanceledCourse::with('teacher', 'student', 'course')
            ->get();
        $by_teachers =  $courses->where('cancelled_by', 'teacher')->count();
        $by_students =  $courses->where('cancelled_by', 'student')->count();
        $by_admins =  $courses->where('cancelled_by', 'admin')->count();

        if ($request->has('cancelled_by') && $request->cancelled_by == "student") {

            $courses = CanceledCourse::with('teacher', 'student', 'course')
                ->where('cancelled_by', 'student')
                ->get();
        }
        if ($request->has('cancelled_by') && $request->cancelled_by == "teacher") {
            $courses = CanceledCourse::with('teacher', 'student', 'course')
                ->where('cancelled_by', 'teacher')
                ->get();
        }
        if ($request->has('cancelled_by') && $request->cancelled_by == "admin") {
            $courses = CanceledCourse::with('teacher', 'student', 'course')
                ->where('cancelled_by', 'admin')
                ->get();
        }

        //calculating the ratings
        foreach ($courses as $course) {
            #for student
            $student_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->student_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->student_id)->count();
            if ($total_reviews > 0) {
                $student_rating = $rating_sum / $total_reviews;
            }
            $course->student_rating = $student_rating;

            #for teacher
            $teacher_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $course->teacher_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $course->teacher_id)->count();
            if ($total_reviews > 0) {
                $teacher_rating = $rating_sum / $total_reviews;
            }
            $course->teacher_rating = $teacher_rating;
        }


        if ($request->has('cancelled_by')) {
            return response()->json([
                'status' => true,
                'message' => "Cancelled Courses!",
                'cancelled_courses' => $this->paginate($courses, $request->per_page ?? 10),
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Cancelled Courses!",
                'total' => count($courses),
                'by_teachers' => $by_teachers,
                'by_students' => $by_students,
                'by_admins' => $by_admins,
                'cancelled_courses' => $this->paginate($courses, $request->per_page ?? 10),
            ]);
        }
    }

    public function teacher_status(Request $request)
    {
        $rules = [
            'teacher_id' =>  'required|integer',
            'status' =>  'required|string',
            'reason' =>  'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ]);
        }

        $courses = Course::where('teacher_id', $request->teacher_id)
            ->whereIn('status', ['active', 'inprogress'])
            ->get();

        //if teacher has active courses
        if (count($courses) > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Access Denied! Please assign these courses to someone else.',
                'courses' => $courses,
            ], 400);
        } else {
            $teacher = User::findOrFail($request->teacher_id);
            $teacher->status = $request->status;
            $teacher->update();
            $admin_message = "Teacher Status Changed Successfully";
            $teacher_message = "Your Status Changed have been Successfully";
            $admin = User::where('role_name', 'admin')->first();



            event(new TeacherStatusEvent($admin->id, $admin, $admin_message));
            event(new TeacherStatusEvent($teacher->id, $teacher, $teacher_message));
            dispatch(new TeacherStatusJob($admin->id, $admin, $admin_message));
            dispatch(new TeacherStatusJob($teacher->id, $teacher, $teacher_message));
            return response()->json([
                'status' => true,
                'message' => 'Status updated successfully! ',
                'teacher' => $teacher,
            ]);
        }
    }

    public function students(Request $request)
    {
        $courses = ClassRoom::all();

        //all Students having courses
        $students =  $courses->pluck('student_id')->unique();
        $enrolled_students = [];

        foreach ($students as $student) {
            array_push($enrolled_students, $student);
        }

        //total Students

        if ($request->has('search')) {

            $registered_students = User::where('role_name', 'student')
                ->where(function ($query) use ($request) {
                    $query->where('first_name', 'LIKE', "%$request->search%")
                        ->orWhere('last_name', 'LIKE', "%$request->search%")
                        ->orWhere('id_number', 'LIKE', $request->search)
                        ->orWhere('status', 'LIKE', "%$request->search%")
                        ->orWhere('middle_name', 'LIKE', "%$request->search%")
                        ->orWhere('nationality', 'LIKE', "%$request->search%");
                })->get();
        } else {

            $registered_students = User::where('role_name', 'student')->get();
        }



        $students_array = [];

        foreach ($registered_students as $student) {
            $prices = 0;
            if (!(in_array($student->id, $enrolled_students))) {
                array_push($students_array, $student);
            }
            //Student's average rating
            $average_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $student->id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $student->id)->count();
            if ($total_reviews > 0) {
                $average_rating = $rating_sum / $total_reviews;
            }
            $student->average_rating =  $average_rating;
            //finding student courses
            $student_courses = ClassRoom::with('course')->where('student_id', $student->id)->get();
            if (count($student_courses) == 0) {
                $student->total_spendings = 0;
            } else {
                foreach ($student_courses as  $student_course) {
                    if (isset($student_course->course)) {
                        $prices = $prices + $student_course->course->total_price;
                    }
                }
                $student->total_spendings = $prices;
            }
            $student->total_bookings = count($student_courses);
        }


        $active_students = $registered_students->where('status', 'active');
        $suspended_students = $registered_students->where('status', 'inactive');

        if (isset($request->status) && $request->status == "active") {

            $enrolled_students = [];
            //total enrolled active students
            foreach ($students as $student) {
                $user = User::find($student);
                if ($user->status == 'active' && $user->role_name == "student") {
                    array_push($enrolled_students, $student);
                }
            }

            if ($request->has('search')) {

                $active_students = User::where('role_name', 'student')->where('status', 'active')
                    ->where(function ($query) use ($request) {
                        $query->where('first_name', 'LIKE', "%$request->search%")
                            ->orWhere('last_name', 'LIKE', "%$request->search%")
                            ->orWhere('id_number', 'LIKE', $request->search)
                            ->orWhere('status', 'LIKE', "%$request->search%")
                            ->orWhere('middle_name', 'LIKE', "%$request->search%")
                            ->orWhere('nationality', 'LIKE', "%$request->search%");
                    })->get();
            } else {

                $active_students = User::where('role_name', 'student')->where('status', 'active')->get();
            }



            foreach ($active_students as $student) {
                //Active Student's average rating
                $average_rating = 5;
                $rating_sum = UserFeedback::where('receiver_id', $student->id)->sum('rating');
                $total_reviews = UserFeedback::where('receiver_id', $student->id)->count();
                if ($total_reviews > 0) {
                    $average_rating = $rating_sum / $total_reviews;
                }
                $student->average_rating =  $average_rating;

                //finding student courses
                $student_courses = ClassRoom::with('course')->where('student_id', $student->id)->get();
                if (count($student_courses) == 0) {
                    $student->total_spendings = 0;
                } else {
                    foreach ($student_courses as  $student_course) {
                        if (isset($student_course->course)) {
                            $prices = $prices + $student_course->course->total_price;
                        }
                    }
                    $student->total_spendings = $prices;
                }
                $student->total_bookings = count($student_courses);
            }

            return response()->json([
                'status' => true,
                'message' => 'Active Students ',
                'Total' => count($active_students),
                'enrolled' => count($enrolled_students),
                'unenrolled' => count($active_students) - count($enrolled_students),
                'students' => $this->paginate($active_students, $request->per_page ?? 10),

            ]);
        }

        if (isset($request->status) && $request->status == "inactive") {

            if ($request->has('search')) {

                $inactive_students = User::where('role_name', 'student')->where('status', 'inactive')
                    ->where(function ($query) use ($request) {
                        $query->where('first_name', 'LIKE', "%$request->search%")
                            ->orWhere('last_name', 'LIKE', "%$request->search%")
                            ->orWhere('id_number', 'LIKE', $request->search)
                            ->orWhere('status', 'LIKE', "%$request->search%")
                            ->orWhere('middle_name', 'LIKE', "%$request->search%")
                            ->orWhere('nationality', 'LIKE', "%$request->search%");
                    })->get();
            } else {

                $inactive_students = User::where('role_name', 'student')->where('status', 'inactive')->get();
            }


            foreach ($inactive_students as $student) {
                //inActive Student's average rating
                $average_rating = 5;
                $rating_sum = UserFeedback::where('receiver_id', $student->id)->sum('rating');
                $total_reviews = UserFeedback::where('receiver_id', $student->id)->count();
                if ($total_reviews > 0) {
                    $average_rating = $rating_sum / $total_reviews;
                }
                $student->average_rating =  $average_rating;

                //finding student courses
                $student_courses = ClassRoom::with('course')->where('student_id', $student->id)->get();
                if (count($student_courses) == 0) {
                    $student->total_spendings = 0;
                } else {
                    foreach ($student_courses as  $student_course) {
                        if (isset($student_course->course)) {
                            $prices = $prices + $student_course->course->total_price;
                        }
                    }
                    $student->total_spendings = $prices;
                }
                $student->total_bookings = count($student_courses);
            }

            return response()->json([
                'status' => true,
                'message' => 'Inactive Students ',
                'students' => $this->paginate($inactive_students, $request->per_page ?? 10),

            ]);
        }

        if (isset($request->status) && $request->status == "suspended") {

            if ($request->has('search')) {

                $suspended_students = User::where('role_name', 'student')->where('status', 'suspended')
                    ->where(function ($query) use ($request) {
                        $query->where('first_name', 'LIKE', "%$request->search%")
                            ->orWhere('last_name', 'LIKE', "%$request->search%")
                            ->orWhere('id_number', 'LIKE', $request->search)
                            ->orWhere('status', 'LIKE', "%$request->search%")
                            ->orWhere('middle_name', 'LIKE', "%$request->search%")
                            ->orWhere('nationality', 'LIKE', "%$request->search%");
                    })->get();
            } else {

                $suspended_students = User::where('role_name', 'student')->where('status', 'suspended')->get();
            }


            foreach ($suspended_students as $student) {
                //suspended Student's average rating
                $average_rating = 5;
                $rating_sum = UserFeedback::where('receiver_id', $student->id)->sum('rating');
                $total_reviews = UserFeedback::where('receiver_id', $student->id)->count();
                if ($total_reviews > 0) {
                    $average_rating = $rating_sum / $total_reviews;
                }
                $student->average_rating =  $average_rating;

                //finding student courses
                $student_courses = ClassRoom::with('course')->where('student_id', $student->id)->get();
                if (count($student_courses) == 0) {
                    $student->total_spendings = 0;
                } else {
                    foreach ($student_courses as  $student_course) {
                        if (isset($student_course->course)) {
                            $prices = $prices + $student_course->course->total_price;
                        }
                    }
                    $student->total_spendings = $prices;
                }
                $student->total_bookings = count($student_courses);
            }

            return response()->json([
                'status' => true,
                'message' => 'suspended Students ',
                'students' => $this->paginate($suspended_students, $request->per_page ?? 10),

            ]);
        }
        //Response
        return response()->json([
            'status' => true,
            'message' => 'All Students ',
            'Total' => count($registered_students),
            'enrolled' => count($enrolled_students),
            'active' => count($active_students),
            'suspended' => count($suspended_students),
            'students' =>  $this->paginate($registered_students, $request->per_page ?? 10),
        ]);
    }

    public function student_bookings($student_id)
    {
        $student = User::find($student_id);
        $bookings = ClassRoom::with(['course' => function ($q) {
            $q->with('teacher', 'program', 'subject', 'field');
        }])->where('student_id', $student_id)->get();
        $courses = [];
        foreach ($bookings as $booking) {
            array_push($courses, $booking['course']);
        }
        //rating
        $average_rating = 5;
        $rating_sum = UserFeedback::where('receiver_id', $student_id)->sum('rating');
        $total_reviews = UserFeedback::where('receiver_id', $student_id)->count();
        if ($total_reviews > 0) {
            $average_rating = $rating_sum / $total_reviews;
        }

        $attendence_count = 0;
        $completed_clasess = 0;
        $attendence_rate = 0;
        foreach ($courses as $course) {
            if (isset($course)) {
                $classes = $course->classes->where('status', 'completed');
                $completed_clasess = $completed_clasess + count($classes);
                foreach ($classes as $class) {
                    $attendence = Attendance::where('academic_class_id', $class->id)
                        ->where('status', 'present')
                        ->where('user_id', $student_id)
                        ->first();
                    if ($attendence != null) {
                        $attendence_count++;
                    }
                }
            }
        }

        if ($completed_clasess > 0) {
            $attendence_rate = ($attendence_count / $completed_clasess) * 100;
        }

        return response()->json([
            'status' => true,
            'message' => 'All Bookings!',
            'student' => $student,
            'total_bookings' => count($bookings),
            'average_rating' => $average_rating,
            // 'attendence_count' => $attendence_count,
            // 'completed_clasess' => $completed_clasess,
            'attendence_rate' => $attendence_rate,
            'bookings' => $courses,
        ]);
    }


    public function student_profile($student_id)
    {

        $student_details = User::find($student_id);
        $bookings = ClassRoom::with(['course' => function ($q) {
            $q->with('teacher', 'program', 'subject', 'field');
        }])->where('student_id', $student_id)->get();
        $courses = [];
        foreach ($bookings as $booking) {
            array_push($courses, $booking['course']);
        }
        //rating
        $average_rating = 5;
        $rating_sum = UserFeedback::where('receiver_id', $student_id)->sum('rating');
        $total_reviews = UserFeedback::where('receiver_id', $student_id)->count();
        if ($total_reviews > 0) {
            $average_rating = $rating_sum / $total_reviews;
        }

        $attendence_count = 0;
        $completed_clasess = 0;
        $attendence_rate = 0;
        $total_spendings = 0;
        foreach ($courses as $course) {
            if (isset($course)) {
                $total_spendings = $total_spendings + $course->total_price;
                $classes = $course->classes->where('status', 'completed');
                $completed_clasess = $completed_clasess + count($classes);
                foreach ($classes as $class) {
                    $attendence = Attendance::where('academic_class_id', $class->id)->where('status', 'present')->first();
                    if ($attendence != null) {
                        $attendence_count++;
                    }
                }
            }
        }

        if ($completed_clasess > 0) {
            $attendence_rate = ($attendence_count / $completed_clasess) * 100;
        }

        $completed_courses =  $bookings->where('status', 'completed');


        return response()->json([
            'status' => true,
            'message' => 'Student profile!',
            'total_courses' => count($bookings),
            'completed_courses' => count($completed_courses),
            'average_rating' => number_format($average_rating, 2),
            'total_spendings' => $total_spendings,
            'attendence_rate' => $attendence_rate,
            'student' => $student_details,
            'courses' => $courses,
        ]);
    }

    public function booking_detail($student_id, $course_id)
    {
        $course = Course::with('program', 'subject', 'field', 'teacher', 'classes.teacher')
            // ->with(['classes' => function ($q) {
            //     $q->where('status', 'completed');
            // }])
            ->find($course_id);
        $student = User::find($student_id);


        $attendence_count = 0;
        $completed_clasess = 0;
        $attendence_percentage = 0;

        if (isset($course)) {
            $classes = $course->classes->where('status', 'completed');
            $completed_clasess = $completed_clasess + count($classes);
            foreach ($classes as $class) {
                $attendence = Attendance::where('academic_class_id', $class->id)->where('status', 'present')->first();
                if ($attendence != null) {
                    $attendence_count++;
                }
            }
            if ($completed_clasess > 0) {
                $attendence_percentage = ($attendence_count / $completed_clasess) * 100;
            }
        }

        //calculating classes stats
        $course->attendence_percentage = $attendence_percentage;
        $completed_classes = $course['classes']->where('status', 'completed');
        $course->completed_classes = count($completed_classes);
        $remaining_classes = $course['classes']->where('status', '!=', 'completed');
        $course->remaining_classes = count($remaining_classes);
        $rescheduled_classes = RescheduleClass::where('course_id', $course->id)->get();
        $course->rescheduled_classes = count($rescheduled_classes);
        $added_classes = $course['classes']->where('class_paradigm', 'added');
        $course->added_classes = count($added_classes);
        $course->student = $student;

        //student_rating
        $average_rating = 5;
        $user = User::find($student_id);
        $rating_sum = UserFeedback::where('receiver_id', $student_id)->sum('rating');
        $total_reviews = UserFeedback::where('receiver_id', $student_id)->count();
        if ($total_reviews > 0) {
            $average_rating = $rating_sum / $total_reviews;
        }
        $student->average_rating = $average_rating;

        //Calculating Assignment completion rate
        $assignment_completion_rate = 100;
        $student_assignments = Assignment::with(['assignees' => function ($q) use ($student_id) {
            $q->where('user_id', $student_id);
        }])->where('course_id', $course_id)->get();

        $completed_assignments = [];
        foreach ($student_assignments as $student_assignment) {
            if ($student_assignment['assignees'] != []) {
                foreach ($student_assignment['assignees'] as $assignee) {
                    if ($assignee->status == 'completed') {
                        array_push($completed_assignments, $assignee);
                    }
                }
            }
        }

        if (count($student_assignments) > 0) {
            $assignment_completion_rate = (count($completed_assignments) / count($student_assignments)) * 100;
        }
        $student->assignment_completion_rate = $assignment_completion_rate;

        //Calculating Attendence Percentage
        $attendence_percentage = 0;
        $atendence = Attendance::where('course_id', $course_id)->where('user_id', $student_id)->get();
        $present_attendence = $atendence->where('status', 'present');
        $completed_classes = $course['classes']->where('status', 'completed');
        if (count($completed_classes) > 0) {
            $attendence_percentage = (count($present_attendence) / count($completed_classes)) * 100;
        }
        $student->attendence_percentage = $attendence_percentage;

        //Calculating Teacher rating
        $average_rating = 5;
        foreach ($course['classes'] as $class) {
            $user = User::find($student_id);
            $rating_sum = UserFeedback::where('receiver_id', $class->teacher_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $class->teacher_id)->count();
            if ($total_reviews > 0) {
                $average_rating = $rating_sum / $total_reviews;
            }
            $teacher = $class['teacher'];
            $teacher->average_rating = $average_rating;

            //Checking class Attendence
            $attendence = Attendance::where('academic_class_id', $class->id)->first();
            if ($attendence != null) {
                $class->class_attendence = $attendence->status;
            } else {
                $class->class_attendence = "N/A";
            }
        }
        $course->assignment_completion_rate = $assignment_completion_rate;



        return response()->json([
            'status' => true,
            'message' => 'Booking Detail!',
            'course' => $course,
        ]);
    }

    public function assignment_summary($student_id, $course_id)
    {
        $student_assignments = Assignment::with('teacher')
            ->with(['assignees' => function ($q) use ($student_id) {
                $q->with('feedback')->where('user_id', $student_id);
            }])
            ->where('course_id', $course_id)->get();

        //Calculating Accuracy
        $assignmnets_accuracy = 0;
        $total_rating = 0;
        $counter = 0;
        foreach ($student_assignments as $student_assignment) {
            foreach ($student_assignment['assignees'] as $assignee) {
                $counter++;
                $total_rating = $total_rating + $assignee['feedback']->sum('rating');
            }
        }
        if ($counter > 0) {
            $assignmnets_accuracy = ($total_rating / ($counter * 10)) * 100;
        }

        //Calculating Assignment completion rate
        $completed_assignments = [];
        $assignment_completion_rate = 0;
        $on_date = 0;
        $late_assignment = [];
        foreach ($student_assignments as $student_assignment) {
            if ($student_assignment['assignees'] != []) {
                foreach ($student_assignment['assignees'] as $assignee) {
                    if ($assignee->status == 'completed') {
                        array_push($completed_assignments, $assignee);
                    }

                    //Checking for Late Submitted Assignments
                    if ($assignee->updated_at > $student_assignment->deadline) {
                        array_push($late_assignment, $assignee);
                    }
                }
            }
        }

        if (count($late_assignment) > 0) {
            $on_date = count($late_assignment) / count($student_assignments);
        }

        if (count($student_assignments) > 0) {
            $assignment_completion_rate = (count($completed_assignments) / count($student_assignments)) * 100;
        }




        return response()->json([
            'status' => true,
            'message' => 'Assignment Summary',
            'assignmnets_accuracy' => $assignmnets_accuracy,
            'assignment_completion_rate' => $assignment_completion_rate,
            'assignments_by_due_date' => $on_date,
            'Assignments' => $student_assignments,
        ]);
    }

    public function studentFeedback($student_id, $course_id)
    {
        $feedbacks = UserFeedback::with('feedback', 'sender')->where('receiver_id', $student_id)->where('course_id', $course_id)->get();

        $student_feedback = [];
        $counter = 0;

        foreach ($feedbacks as $feedback) {
            array_push($student_feedback, $feedback['feedback']);
            $student_feedback[$counter]['rating'] = $feedback->rating;
            $counter++;
        }

        if (isset($feedbacks[0])) {
            $review = $feedbacks[0]->review;
            $teacher = $feedbacks[0]->sender;
        } else {
            $review = Null;
            $teacher = Null;
        }




        return response()->json([
            'status' => true,
            'message' => 'Teacher Feedback to student!',
            'review' => $review,
            'teacher' => $teacher,
            'feedback' => $student_feedback,
        ]);
    }

    public function subject_courses(Request $request)
    {
        $courses_subjects = [];
        $subjects = Course::all()->pluck('subject_id')->unique();

        $running_courses_count = 0;
        $pending_courses_count = 0;
        $completed_courses_count = 0;
        $cancelled_courses_count = 0;
        $reassigned_courses_count = 0;


        if ($request->status == 'running') {
            foreach ($subjects as $subject) {
                // return $subject;
                $all_courses = Course::where("subject_id", $subject)->get();
                $course_subject = Subject::with('program', 'field')->findOrFail($subject);
                $courses = Course::where("subject_id", $subject)->where("status", "inprogress")->get();


                if (count($courses) > 0) {
                    $course_subject->total_booking = $courses->count();
                    $course_subject->total_amount = $courses->sum('total_price');
                    $course_subject->status = "active";
                    $courses_subjects[] = $course_subject;
                }


                $running_courses_count =  $running_courses_count + $all_courses->where('status',  'inprogress')->count();
                $pending_courses_count = $pending_courses_count + $all_courses->where('status',  'pending')->count();
                $completed_courses_count = $completed_courses_count + $all_courses->where('status',  'completed')->count();
                $cancelled_courses_count = $cancelled_courses_count + $all_courses->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_admin', 'cancelled_by_student'])->count();
                $reassigned_courses_count =  $reassigned_courses_count + $all_courses->where('status',  'reassigned')->count();
            }
            // $pending_courses = Course::where('subject_id', 1)->where('status',  'pending')->get();
            return response()->json([
                'status' => true,
                'message' => 'Running Courses!',
                'running_courses_count' => $running_courses_count,
                'pending_courses_count' => $pending_courses_count,
                'completed_courses_count' => $completed_courses_count,
                'cancelled_courses_count' => $cancelled_courses_count,
                'reassigned_courses_count' => $reassigned_courses_count,
                'subjects' => $this->paginate($courses_subjects, $request->per_page ?? 10),
            ]);
        }

        if ($request->status == 'pending') {

            foreach ($subjects as $subject) {
                $all_courses = Course::where("subject_id", $subject)->get();
                $course_subject = Subject::with('program', 'field')->findOrFail($subject);
                // $course_subject->status = "upcoming";
                $course_subject->status = "pending";
                $courses = Course::where("subject_id", $subject)->where("status", "pending")->get();

                if (count($courses) > 0) {
                    $course_subject->total_booking = $courses->count();
                    $course_subject->total_amount = $courses->sum('total_price');
                    $courses_subjects[] = $course_subject;
                }

                $running_courses_count =  $running_courses_count + $all_courses->where('status',  'inprogress')->count();
                $completed_courses_count = $completed_courses_count + $all_courses->where('status',  'completed')->count();
                $cancelled_courses_count = $cancelled_courses_count + $all_courses->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_admin', 'cancelled_by_student'])->count();
                $reassigned_courses_count =  $reassigned_courses_count + $all_courses->where('status',  'reassigned')->count();
                $pending_courses_count = $pending_courses_count + $all_courses->where('status',  'pending')->count();
            }

            return response()->json([
                'status' => true,
                'message' => 'Pending Courses!',
                'running_courses_count' => $running_courses_count,
                'pending_courses_count' => $pending_courses_count,
                'completed_courses_count' => $completed_courses_count,
                'cancelled_courses_count' => $cancelled_courses_count,
                'reassigned_courses_count' => $reassigned_courses_count,
                'subjects' => $this->paginate($courses_subjects, $request->per_page ?? 10),
            ]);
        }

        if ($request->status == 'cancelled') {
            foreach ($subjects as $subject) {
                $all_courses = Course::where("subject_id", $subject)->get();

                $course_subject = Subject::with('program', 'field')->find($subject);
                $courses = Course::where("subject_id", $subject)->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_admin', 'cancelled_by_student'])->get();

                if (count($courses) > 0) {
                    $course_subject->total_booking = $courses->count();
                    $course_subject->total_amount = $courses->sum('total_price');
                    $course_subject->status = "cancelled";
                    $courses_subjects[] = $course_subject;
                }

                $running_courses_count =  $running_courses_count + $all_courses->where('status',  'inprogress')->count();
                $pending_courses_count = $pending_courses_count + $all_courses->where('status',  'pending')->count();
                $completed_courses_count = $completed_courses_count + $all_courses->where('status',  'completed')->count();
                $cancelled_courses_count = $cancelled_courses_count + $all_courses->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_admin', 'cancelled_by_student'])->count();
                $reassigned_courses_count =  $reassigned_courses_count + $all_courses->where('status',  'reassigned')->count();
            }

            return response()->json([
                'status' => true,
                'message' => 'Cancelled Courses!',
                'running_courses_count' => $running_courses_count,
                'pending_courses_count' => $pending_courses_count,
                'completed_courses_count' => $completed_courses_count,
                'cancelled_courses_count' => $cancelled_courses_count,
                'reassigned_courses_count' => $reassigned_courses_count,
                'subjects' => $this->paginate($courses_subjects, $request->per_page ?? 10),
            ]);
        }

        if ($request->status == 'completed') {

            foreach ($subjects as $subject) {

                $all_courses = Course::where("subject_id", $subject)->get();
                $course_subject = Subject::with('program', 'field')->find($subject);
                $courses = Course::where("subject_id", $subject)->where('status', 'completed')->get();

                if (count($courses) > 0) {
                    $course_subject->total_booking = $courses->count();
                    $course_subject->total_amount = $courses->sum('total_price');
                    $course_subject->status = "completed";
                    $courses_subjects[] = $course_subject;
                }

                $running_courses_count =  $running_courses_count + $all_courses->where('status',  'inprogress')->count();
                $pending_courses_count = $pending_courses_count + $all_courses->where('status',  'pending')->count();
                $completed_courses_count = $completed_courses_count + $all_courses->where('status',  'completed')->count();
                $cancelled_courses_count = $cancelled_courses_count + $all_courses->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_admin', 'cancelled_by_student'])->count();
                $reassigned_courses_count =  $reassigned_courses_count + $all_courses->where('status',  'reassigned')->count();
            }

            return response()->json([
                'status' => true,
                'message' => 'Completed Courses!',
                'running_courses_count' => $running_courses_count,
                'pending_courses_count' => $pending_courses_count,
                'completed_courses_count' => $completed_courses_count,
                'cancelled_courses_count' => $cancelled_courses_count,
                'reassigned_courses_count' => $reassigned_courses_count,
                'subjects' => $this->paginate($courses_subjects, $request->per_page ?? 10),
            ]);
        }

        if ($request->status == 'reassigned') {
            foreach ($subjects as $subject) {
                $all_courses = Course::where("subject_id", $subject)->get();
                $course_subject = Subject::with('program', 'field')->find($subject);
                $courses = Course::where("subject_id", $subject)->where("status", "reassigned")->get();

                if (count($courses) > 0) {
                    $course_subject->total_booking = $courses->count();
                    $course_subject->total_amount = $courses->sum('total_price');
                    $course_subject->status = "rescheduled";
                    $courses_subjects[] = $course_subject;
                }



                $running_courses_count =  $running_courses_count + $all_courses->where('status',  'inprogress')->count();
                $pending_courses_count = $pending_courses_count + $all_courses->where('status',  'pending')->count();
                $completed_courses_count = $completed_courses_count + $all_courses->where('status',  'completed')->count();
                $cancelled_courses_count = $cancelled_courses_count + $all_courses->whereIn('status', ['cancelled_by_teacher', 'cancelled_by_admin', 'cancelled_by_student'])->count();
                $reassigned_courses_count =  $reassigned_courses_count + $all_courses->where('status',  'reassigned')->count();
            }

            return response()->json([
                'status' => true,
                'message' => 'Reassigned Courses!',
                'running_courses_count' => $running_courses_count,
                'pending_courses_count' => $pending_courses_count,
                'completed_courses_count' => $completed_courses_count,
                'cancelled_courses_count' => $cancelled_courses_count,
                'reassigned_courses_count' => $reassigned_courses_count,
                'subjects' => $this->paginate($courses_subjects, $request->per_page ?? 10),
            ]);
        }
    }

    public function teacher_bookings($teacher_id)
    {
        $courses = Course::with('student', 'program', 'subject', 'field')
            ->where('teacher_id', $teacher_id)
            ->get();
        $teacher = User::find($teacher_id);

        //rating
        $average_rating = 5;
        // $rating_sum = UserFeedback::where('receiver_id', $teacher_id)->sum('rating');
        $total_reviews = UserFeedback::where('receiver_id', $teacher_id)->get();
        $total_reviews = $total_reviews->groupBy(['sender_id', 'course_id']);
        if ($total_reviews) {
            // $average_rating = $rating_sum / count($total_reviews);

            $points_array = [];
            $sum_feedback = 0;
            $rating_sum = 0;
            foreach ($total_reviews as $feedback) {
                // converted to a single user feedback
                $sum_stars = 0;
                foreach ($feedback as $user_feedback) {
                    // return $user_feedback;
                    $points_detail = new stdClass();
                    $points_detail->student_name = $user_feedback[0]->sender->first_name . ' ' . $user_feedback[0]->sender->last_name;
                    $points_detail->avatar = $user_feedback[0]->sender->avatar;
                    $points_detail->course_name = $user_feedback[0]->course->course_name;
                    $points_detail->date = $user_feedback[0]->created_at->format('d M Y');
                    $sum_stars =  $user_feedback->sum('rating') / count($user_feedback);
                    $points_detail->stars = $sum_stars;
                    $points_detail->review = $user_feedback[0]->review;
                    $points_detail->kudos_points = $user_feedback->sum('kudos_points');
                    $sum_feedback = $sum_feedback + $user_feedback->sum('kudos_points');
                    array_push($points_array, $points_detail);
                }


                $rating_sum = $sum_stars + $rating_sum;
            }
        }

        if (count($points_array) > 0) {
            $average_rating = $rating_sum / count($points_array);
        }

        $attendence_count = 0;
        $completed_clasess = 0;
        $attendence_rate = 0;
        foreach ($courses as $course) {
            $classes = $course->classes->where('status', 'completed');
            $completed_clasess = $completed_clasess + count($classes);
            foreach ($classes as $class) {
                $attendence = Attendance::where('academic_class_id', $class->id)
                    ->where('status', 'present')
                    ->where('user_id', $teacher_id)
                    ->first();
                if ($attendence != null) {
                    $attendence_count++;
                }
            }
        }

        if ($completed_clasess > 0) {
            $attendence_rate = ($attendence_count / $completed_clasess) * 100;
        }

        $teacher->review_count = count($points_array);

        return response()->json([
            'status' => true,
            'message' => 'All Bookings!',
            'teacher' => $teacher,
            'total_bookings' => count($courses),
            'rating_count' => count($points_array),
            'average_rating' => $average_rating,
            'attendence_rate' => round($attendence_rate),
            'bookings' => $courses,
        ]);
    }

    public function teacher_assignment(Request $request)
    {

        $rules = [

            'status' => 'required',
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

        if ($request->status == 'not-available') {
            $no_teacherCourse = NoTeacherCourse::pluck('course_id');

            $new_courses = Course::with('program', 'subject', 'teacher', 'student')
                ->whereIn('id', $no_teacherCourse)
                ->where('teacher_id', null)
                ->get();

            $flag = 0;
            $newly = [];
            foreach ($new_courses as $course) {
                $counter = '0 H 0 Min';
                if (Carbon::parse($course->start_date)->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                    $difference = (Carbon::now())->diffInSeconds(Carbon::parse($course->start_date));
                    $hours = floor($difference / 3600);
                    $minutes = floor(($difference / 60) % 60);
                    $seconds = $difference % 60;
                    $counter =  $hours . " H " . $minutes . " Min";
                    $course->timer = $counter;
                } else {
                    $course->timer = $counter;
                }
                $weeks = (Carbon::parse($course->end_date))->diffInWeeks(Carbon::parse($course->start_date));
                $course->weeks = $weeks;
                $flag++;
                array_push($newly, $course);
            }
            $completed_courses = Course::with('program', 'subject', 'teacher', 'student')
                ->whereIn('id', $no_teacherCourse)
                ->where('teacher_id', '!=', null)
                ->get();

            $flag = 0;
            $completed = [];
            foreach ($completed_courses as $course) {
                $counter = '0 H 0 Min';
                if (Carbon::parse($course->start_date)->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                    $difference = (Carbon::now())->diffInSeconds(Carbon::parse($course->start_date));
                    $hours = floor($difference / 3600);
                    $minutes = floor(($difference / 60) % 60);
                    $seconds = $difference % 60;
                    $counter =  $hours . " H " . $minutes . " Min";
                    $course->timer = $counter;
                } else {
                    $course->timer = $counter;
                }
                $weeks = (Carbon::parse($course->end_date))->diffInWeeks(Carbon::parse($course->start_date));
                $course->weeks = $weeks;
                $flag++;
                array_push($completed, $course);
            }

            return response()->json([
                'status' => true,
                'message' => 'Rejected Courses!',
                'newly_courses_count' => count($new_courses),
                'completed_courses_count' => count($completed_courses),
                'newly_requested_courses' => $this->paginate($newly, $request->per_page ?? 10),
                'completed_courses' => $this->paginate($completed, $request->per_page ?? 10),
            ]);
        }
        if ($request->status == 'declined') {
            $rejected_courses = RejectedCourse::pluck('course_id');
            $new_courses = Course::with('program', 'subject', 'teacher', 'student')
                ->whereIn('id', $rejected_courses)
                // ->where('status', 'rejected')->get();
                ->whereIn('status', ['declined_by_teacher', 'declined_by_student'])->get();
            $flag = 0;
            $newly = [];
            foreach ($new_courses as $course) {
                $counter = '0 H 0 Min';
                if (Carbon::parse($course->start_date)->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                    $difference = (Carbon::now())->diffInSeconds(Carbon::parse($course->start_date));
                    $hours = floor($difference / 3600);
                    $minutes = floor(($difference / 60) % 60);
                    $seconds = $difference % 60;
                    $counter =  $hours . " H " . $minutes . " Min";
                    $course->timer = $counter;
                } else {
                    $course->timer = $counter;
                }
                $weeks = (Carbon::parse($course->end_date))->diffInWeeks(Carbon::parse($course->start_date));
                $course->weeks = $weeks;
                $flag++;
                array_push($newly, $course);
            }
            $completed_courses = Course::with('program', 'subject', 'teacher', 'student')
                ->whereIn('id', $rejected_courses)
                // ->where('status', '!=', 'rejected')
                ->whereNotIn('status', ['declined_by_teacher', 'declined_by_student'])
                ->get();

            $flag = 0;
            $completed = [];
            foreach ($completed_courses as $course) {
                $counter = '0 H 0 Min';
                if (Carbon::parse($course->start_date)->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                    $difference = (Carbon::now())->diffInSeconds(Carbon::parse($course->start_date));
                    $hours = floor($difference / 3600);
                    $minutes = floor(($difference / 60) % 60);
                    $seconds = $difference % 60;
                    $counter =  $hours . " H " . $minutes . " Min";
                    $course->timer = $counter;
                } else {
                    $course->timer = $counter;
                }
                $weeks = (Carbon::parse($course->end_date))->diffInWeeks(Carbon::parse($course->start_date));
                $course->weeks = $weeks;
                $flag++;
                array_push($completed, $course);
            }

            return response()->json([
                'status' => true,
                'message' => 'Courses With no Teacher!',
                'newly_courses_count' => count($new_courses),
                'completed_courses_count' => count($completed_courses),
                'newly_requested_courses' => $this->paginate($newly, $request->per_page ?? 10),
                'completed_courses' => $this->paginate($completed, $request->per_page ?? 10),
            ]);
        }

        if ($request->status == 'cancelled') {
            $cancelled_courses = CanceledCourse::pluck('course_id');
            $new_courses = Course::with('program', 'subject', 'teacher', 'student')->whereIn('id', $cancelled_courses)->where('status', 'cancelled_by_teacher')->get();
            $flag = 0;
            $newly = [];
            foreach ($new_courses as $course) {
                $counter = '0 H 0 Min';
                if (Carbon::parse($course->start_date)->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                    $difference = (Carbon::now())->diff(Carbon::parse($course->start_date));
                    $counter =  $difference->format('%H Hours %I Minutes');
                    $course->timer = $counter;
                } else {
                    $course->timer = $counter;
                }
                $weeks = (Carbon::parse($course->end_date))->diffInWeeks(Carbon::parse($course->start_date));
                $course->weeks = $weeks;
                $flag++;
                array_push($newly, $course);
            }

            $completed_courses = Course::with('program', 'subject', 'teacher', 'student')->whereIn('id', $cancelled_courses)->where('status', '!=', 'cancelled_by_teacher')->get();
            $flag = 0;
            $completed = [];
            foreach ($completed_courses as $course) {
                $counter = '0 H 0 Min';
                if (Carbon::parse($course->start_date)->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                    $difference = (Carbon::now())->diff(Carbon::parse($course->start_date));
                    $counter =  $difference->format('%H Hours %I Minutes');
                    $course->timer = $counter;
                } else {
                    $course->timer = $counter;
                }
                $weeks = (Carbon::parse($course->end_date))->diffInWeeks(Carbon::parse($course->start_date));
                $course->weeks = $weeks;
                $flag++;
                array_push($completed, $course);
            }


            return response()->json([
                'status' => true,
                'message' => 'Cancelled Courses!',
                'newly_courses_count' => count($new_courses),
                'completed_courses_count' => count($completed_courses),
                'newly_requested_courses' => $this->paginate($newly, $request->per_page ?? 10),
                'completed_courses' => $this->paginate($completed, $request->per_page ?? 10),
            ]);
        }

        if ($request->status == 'running') {
            $courses = Course::with('program', 'subject', 'teacher')->where('status', 'inprogress')->get();
            $flag = 0;

            foreach ($courses as $course) {
                $counter = '0 H 0 Min';
                if (Carbon::parse($course->start_date)->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                    $difference = (Carbon::now())->diff(Carbon::parse($course->start_date));
                    $counter =  $difference->format('%H Hours %I Minutes');
                    $course->timer = $counter;
                } else {
                    $course->timer = $counter;
                }
                $weeks = (Carbon::parse($course->end_date))->diffInWeeks(Carbon::parse($course->start_date));
                $course->weeks = $weeks;
                $flag++;
            }

            return response()->json([
                'status' => true,
                'message' => 'Running Courses!',
                'courses_count' => count($courses),
                'courses' => $this->paginate($courses, $request->per_page ?? 10),
            ]);
        }
    }

    public function send_mail(Request $request)
    {
        $rules = [
            'email' => 'required',
            'message' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => false,
                'errors' => $errors,
            ], 400);
        }

        //*********** Sending Email  ************\\
        $user_email = $request->email;
        $message_data = $request->message;
        $to_email = $user_email;

        $data = array('email' =>  $user_email, 'message_data' =>  $message_data);

        Mail::send('email.send_mail', $data, function ($message) use ($to_email) {
            $message->to($to_email)->subject('Course Email');
            $message->from(env('MAIL_FROM_ADDRESS', 'info@metutors.com'), 'MEtutors');
        });

        //********* Sending Email ends **********//
        return response()->json([
            'status' => true,
            'message' => "Email sent Successfully!",
            'email' => $request->email,
            'message' => $request->message,
        ]);
    }

    public function testimonial_status($user_id, Request $request)
    {
        $rules = [
            'status' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => false,
                'errors' => $errors,
            ], 400);
        }
        $user_testimonials = UserTestimonial::where('sender_id', $user_id)->get();
        foreach ($user_testimonials as $testimonial) {
            $testimonial->status = $request->status;
            $testimonial->update();
        }
        $user_testimonials = UserTestimonial::where('sender_id', $user_id)->get();
        return response()->json([
            'status' => true,
            'message' => "Status Updated Successfully",
            'user_testimonials' => $user_testimonials,
        ]);
    }

    public function requested_courses(Request $request)
    {

        $requested_courses = RequestedCourse::with('program', 'country', 'language')->where('status', 'pending')->orderBy('id', 'DESC')->get();
        $completed_courses = RequestedCourse::with('program', 'country', 'language')->where('status', '!=', 'pending')->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => true,
            'message' => "All Requested Courses",
            'new_request_count' => count($requested_courses),
            'completed_count' => count($requested_courses),
            'requested_courses' => $this->paginate($requested_courses, $request->per_page ?? 10),
            'completed_courses' => $this->paginate($completed_courses, $request->per_page ?? 10),
        ]);
    }

    public function orders(Request $request)
    {
        $orders = Order::with('transaction', 'user')->with(['course' => function ($q) {
            $q->withCount('classes');
        }])->get();

        // Calculating student rating
        foreach ($orders as $order) {
            $student_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $order->course->student_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $order->course->student_id)->count();
            if ($total_reviews > 0) {
                $student_rating = $rating_sum / $total_reviews;
            }
            $order->user->student_rating = $student_rating;
        }

        return response()->json([
            'status' => true,
            'message' => "All Orders",
            'orders' => $this->paginate($orders, $request->per_page ?? 10),
        ]);
    }

    public function refund_orders(Request $request)
    {
        $orders = RefundCourse::with('student', 'teacher', 'course', 'canceled_course', 'refunded_classes.academic_class')->get();

        //calculating the ratings
        foreach ($orders as $order) {
            #for student
            $student_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $order->student_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $order->student_id)->count();
            if ($total_reviews > 0) {
                $student_rating = $rating_sum / $total_reviews;
            }
            $order->student->rating = $student_rating;

            #for teacher
            $teacher_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $order->teacher_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $order->teacher_id)->count();
            if ($total_reviews > 0) {
                $teacher_rating = $rating_sum / $total_reviews;
            }
            $order->teacher->rating = $teacher_rating;
        }

        return response()->json([
            'status' => true,
            'message' => "All Orders to refund!",
            'orders' => $this->paginate($orders, $request->per_page ?? 10),
        ]);
    }

    public function refund_details($course_id)
    {
        $refunds = RefundCourse::with('order', 'course.student',  'canceled_course', 'refunded_classes')->where('course_id', $course_id)->first();

        $refunds->remaining_classes = $refunds['course']->total_classes -  $refunds->refunded_classes_count;
        $refunds->subtotal = $refunds->total_refunds +  $refunds['refunded_classes']->sum('service_fee');
        $class = AcademicClass::findOrFail($refunds['refunded_classes'][0]->academic_class_id);
        $course = Course::findOrFail($class->course_id);
        $subject = Subject::FindOrFail($course->subject_id);
        $refunds->refund_amount_per_class = $subject->price_per_hour * $class->duration;
        // $refunds->refund_amount_per_class = $refunds['refunded_classes']

        return response()->json([
            'status' => true,
            'message' => "Course refund details!",
            'refund_detail' => $refunds,
        ]);
    }
    public function requested_courses_status(Request $request, $id)
    {

        $rules = [
            'status' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => false,
                'errors' => $errors,
            ], 400);
        }

        $requested_course = RequestedCourse::find($id);
        $requested_course->status = $request->status;
        $requested_course->save();

        return response()->json([
            'status' => true,
            'message' => "status changed successfully",
        ]);
    }

    public function teacherStatus($course_id, Request $request)
    {

        $rules = [
            'status' =>  'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ]);
        }
        $course = Course::findOrFail($course_id);
        $course->teacher_status = $request->status;
        $course->update();



        return response()->json([
            'status' => true,
            'message' => "Status changed successfully!",
            'course' => $course,
        ]);
    }

    public function subject_orders($subject_id, Request $request)
    {
        $orders = Order::with('transaction', 'user')->whereHas('course', function ($q) use ($subject_id) {
            $q->where('subject_id', $subject_id)->withCount('classes');
        })->get();

        // Calculating student rating
        foreach ($orders as $order) {
            $student_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $order->course->student_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $order->course->student_id)->count();
            if ($total_reviews > 0) {
                $student_rating = $rating_sum / $total_reviews;
            }
            $order->user->student_rating = $student_rating;
        }

        return response()->json([
            'status' => true,
            'message' => "Subject Orders",
            'orders' => $this->paginate($orders, $request->per_page),
        ]);
    }

    public function order_detail($course_id)
    {
        $order = Order::with('transaction', 'user')->where('course_id', $course_id)->first();

        // Calculating student rating
        if ($order) {
            $student_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $order->course->student_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $order->course->student_id)->count();
            if ($total_reviews > 0) {
                $student_rating = $rating_sum / $total_reviews;
            }
            $order->user->student_rating = $student_rating;
            $order->course->classes_count = count($order['course']->classes);
            // removes classes object from response
            unset($order['course']['classes']);
        }


        return response()->json([
            'status' => true,
            'message' => "Order Details!",
            'order_detail' => $order,
        ]);
    }

    public function teacher_profile(Request $request, $id)
    {


        if (isset($id)) {

            $interviewRequests = TeacherInterviewRequest::with('user', 'user.country', 'user.userMetas', 'user.teacherSpecifications', 'user.teacherQualifications', 'user.spokenLanguages', 'user.spokenLanguages.language', 'user.teacher_subjects', 'user.teacher_subjects.program', 'user.teacher_subjects.field', 'user.teacher_subjects.subject')->where('id', $id)->first();
        }

        $teacher_profile = User::with('country', 'userMetas', 'teacherSpecifications', 'teacherQualifications', 'spokenLanguages', 'spokenLanguages.language', 'teacher_subjects', 'teacher_subjects.program', 'teacher_subjects.field', 'teacher_subjects.subject', 'teacher_interview_request')->where('id', $id)->first();

        return response()->json([
            'status' => true,
            'teacher_profile' => $teacher_profile,

        ]);
    }

    public function approval_request(Request $request)
    {
        $no_teacherCourse = NoTeacherCourse::pluck('course_id');

        $new_courses = Course::with('program', 'subject', 'student')
            ->whereIn('id', $no_teacherCourse)
            ->where('teacher_id', null)
            ->get();

        $flag = 0;
        $newly = [];
        foreach ($new_courses as $course) {
            $counter = '0 H 0 Min';
            if (Carbon::parse($course->start_date)->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                $difference = (Carbon::now())->diffInSeconds(Carbon::parse($course->start_date));
                $hours = floor($difference / 3600);
                $minutes = floor(($difference / 60) % 60);
                $seconds = $difference % 60;
                $counter =  $hours . " H " . $minutes . " Min";
                $course->timer = $counter;
            } else {
                $course->timer = $counter;
            }
            $weeks = (Carbon::parse($course->end_date))->diffInWeeks(Carbon::parse($course->start_date));
            $course->weeks = $weeks;
            $flag++;
            array_push($newly, $course);
        }
        $completed_courses = Course::with('program', 'subject', 'teacher', 'student', 'teacherSpecifications')
            ->whereIn('id', $no_teacherCourse)
            ->where('teacher_id', '!=', null)
            ->get();

        $flag = 0;
        $completed = [];
        foreach ($completed_courses as $course) {
            $counter = '0 H 0 Min';
            if (Carbon::parse($course->start_date)->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                $difference = (Carbon::now())->diffInSeconds(Carbon::parse($course->start_date));
                $hours = floor($difference / 3600);
                $minutes = floor(($difference / 60) % 60);
                $seconds = $difference % 60;
                $counter =  $hours . " H " . $minutes . " Min";
                $course->timer = $counter;
            } else {
                $course->timer = $counter;
            }
            $weeks = (Carbon::parse($course->end_date))->diffInWeeks(Carbon::parse($course->start_date));
            $course->weeks = $weeks;
            $flag++;
            array_push($completed, $course);
        }

        return response()->json([
            'status' => true,
            'message' => 'Approval Requests!',
            'newly_courses_count' => count($new_courses),
            'completed_courses_count' => count($completed_courses),
            'newly_requested_courses' => $this->paginate($newly, $request->per_page ?? 10),
            'completed_courses' => $this->paginate($completed, $request->per_page ?? 10),
        ]);
    }

    public function featured_teacher($subject_id)
    {
        $featured_teachers = [];
        $subject = Subject::with('program', 'field', 'available_teachers.teacher.country')
            ->with('available_teachers', function ($q) {
                $q->with('teacher_qualification', 'teacher_subjects.subject');
            })
            ->findOrFail($subject_id);

        $courses = Course::get();
        foreach ($subject['available_teachers'] as $teacher) {
            // return $teacher;
            $courses_count = $courses->where('teacher_id', $teacher->user_id)->count();
            $teacher_programs = TeacherSubject::where('user_id', $teacher->user_id)->where('status', 'approved')->pluck('program_id')->unique();
            $programs = Program::whereIn('id', $teacher_programs)->get();
            $classes = AcademicClass::where('teacher_id', $teacher->user_id)->where('status', 'completed')->count();
            $teacher->courses_count = $courses_count;
            $teacher->classes_taught = $classes;


            //-------rating--------
            $average_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id', $teacher->user_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $teacher->user_id)->count();
            if ($total_reviews > 0) {
                $average_rating = $rating_sum / $total_reviews;
            }

            $reviews = UserFeedback::where('receiver_id', $teacher->user_id)->get();
            $reviews_count = $reviews->groupBy('sender_id')->count();
            //--------------------------

            $teacher->average_rating = $average_rating;
            $teacher->reviews_count  = $reviews_count;
            array_push($featured_teachers, $teacher);
            $teacher->programs = $programs;
        }
        unset($subject['available_teachers']);

        $featured_teachers = collect($featured_teachers)->sortByDesc('courses_count')->take(3);
        $teachers_list = [];
        foreach ($featured_teachers as $featured_teacher) {
            array_push($teachers_list, $featured_teacher);
        }

        return response()->json([
            'status' => true,
            'message' => 'Featured Teachers Related to Subject!',
            'subject' => $subject,
            'featured_teachers' => $teachers_list,
        ]);
    }

    public function featured_teachers_list()
    {
        $courses = Course::all();

        $featured_teachers = [];
        $teacher_ids = TeacherSubject::where('status', 'approved')
            ->pluck('user_id')
            ->unique();
        $teachers = User::with('country')->whereIn('id', $teacher_ids)->where('admin_approval', 'approved')->limit(3)->get();
        foreach ($teachers as $teacher) {
            //  $teacher;
            $courses_count = $courses->where('teacher_id', $teacher->id)->count();
            $students_count = $courses->where('teacher_id', $teacher->id)->pluck('student_id')->unique('student_id')->count();
            $teacher_programs = TeacherSubject::where('user_id', $teacher->id)
                ->where('status', 'approved')
                ->pluck('program_id')
                ->unique();

            $programs = Program::whereIn('id', $teacher_programs)->get();
            $classes = AcademicClass::where('teacher_id',  $teacher->id)->where('status', 'completed')->count();
            $teacher->courses_count = $courses_count;
            $teacher->classes_taught = $classes;


            //-------rating--------
            $average_rating = 5;
            $rating_sum = UserFeedback::where('receiver_id',  $teacher->id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id',  $teacher->id)->count();
            if ($total_reviews > 0) {
                $average_rating = $rating_sum / $total_reviews;
            }

            $reviews = UserFeedback::where('receiver_id',  $teacher->id)->get();
            $reviews_count = $reviews->groupBy('sender_id')->count();
            //--------------------------

            $teacher->average_rating = $average_rating;
            $teacher->reviews_count  = $reviews_count;
            $teacher->teacher_students_count  = $students_count;
            array_push($featured_teachers, $teacher);
            $teacher->programs = $programs;
        }


        $featured_teachers = collect($featured_teachers)->sortByDesc('courses_count')->take(4);
        $teachers_list = [];
        foreach ($featured_teachers as $featured_teacher) {
            $subjects = TeacherSubject::where('user_id', $featured_teacher->id)
                ->where('status', 'approved')
                ->pluck('subject_id')
                ->unique();

            $featured_teacher->subjects = Subject::whereIn('id', $subjects)->get();
            array_push($teachers_list, $featured_teacher);
        }

        return response()->json([
            'status' => true,
            'message' => 'Featured Teachers!',
            'featured_teachers' => $teachers_list,
        ]);
    }

    public function refund_account_detail($course_id)
    {
        $refunds = RefundCourse::with('order', 'course')->where('course_id', $course_id)->first();
        return response()->json([
            'status' => true,
            'message' => 'Refund order Account Detail!',
            'refund' => $refunds,
        ]);
    }

    public function process_refund($course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $course = Course::findOrFail($course_id);
        $course->status = "refunded";
        $class_rooms = ClassRoom::where('course_id', $course_id)->get();
        foreach ($class_rooms as $class_room) {
            $class_room->status = "refunded";
            $class_room->update();
        }
        $course->update();
        $refunds = RefundCourse::with('order', 'course')->where('course_id', $course_id)->first();

        $admin_message = "Course Refunded Successfully!";
        $student = User::findOrFail($course->student_id);
        $teacher = User::findOrFail($course->teacher_id);

        // event(new RefundCourseEvent($token_user->id, $token_user, $admin_message, $refunds, $course));
        // event(new RefundCourseEvent($student->id, $student, $admin_message, $refunds, $course));
        // event(new RefundCourseEvent($teacher->id, $teacher, $admin_message, $refunds, $course));
        // dispatch(new RefundCourseJob($token_user->id, $token_user, $admin_message, $refunds, $course));
        // dispatch(new RefundCourseJob($student->id, $student, $admin_message, $refunds, $course));
        // dispatch(new RefundCourseJob($teacher->id, $teacher, $admin_message, $refunds, $course));


        return response()->json([
            'status' => true,
            'message' => 'Refunded Successfully!',
            'refund' => $refunds,
        ]);
    }

    public function all_courses(Request $request)
    {
        $courses = Course::select('id', 'course_code', 'course_name', 'subject_id', 'student_id',  'teacher_id', 'program_id', 'country_id', 'created_at')
            ->with('program', 'program_country')
            ->paginate($request->per_page ?? 10);

        //search implementation
        if ($request->has('search')) {
            $courses = Course::select('id', 'course_code', 'course_name', 'subject_id', 'student_id',  'teacher_id', 'program_id', 'country_id', 'created_at')
                ->with('program', 'program_country')
                ->where(function ($query) use ($request) {
                    $query->where('course_code', $request->search)
                        ->orWhere('course_name', 'LIKE', "%$request->search%");
                })
                ->paginate($request->per_page ?? 10);
        }

        //calculating min max prices
        foreach ($courses as  $course) {
            $teacher_subjects = TeacherSubject::where(['subject_id' => $course->subject_id, 'program_id' => $course->program_id])->get();
            $subjects = Subject::where(['id' => $course->subject_id, 'program_id' => $course->program_id])->get();

            $course->price_per_hour = $course->subject->price_per_hour;
            unset($course->subject);
            $course->min_hourly_rate_ask =   $teacher_subjects->min('hourly_price');
            $course->max_hourly_rate_ask = $teacher_subjects->max('hourly_price');
            $course->min_hourly_rate_actual = $subjects->min('price_per_hour');
            $course->max_hourly_rate_actual = $subjects->max('price_per_hour');
        }


        return response()->json([
            'status' => true,
            'message' => 'All Courses!',
            'courses' =>  $courses,
            // 'courses' =>  $this->paginate($courses, $request->per_page ?? 10),
        ]);
    }

    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }
}
