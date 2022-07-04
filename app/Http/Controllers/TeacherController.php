<?php

namespace App\Http\Controllers;

use App\Events\AcceptCourse;
use App\Events\AddAssignmentEvent;
use App\Events\AddSyllabusEvent;
use App\Events\CancelCourse;
use App\Events\CancelCourseEvent;
use App\Events\RejectCourse;
use App\Events\RejectCourseEvent;
use App\Events\StudentAcceptCourse;
use App\Events\UpdateAssignmentEvent;
use App\Events\UpdateSyllabusEvent;
use App\Jobs\AddAssignmentJob;
use App\Jobs\AddSyllabusJob;
use App\Jobs\CancelCourseJob;
use App\Jobs\RejectCourseJob;
use App\Jobs\UpdateAssignmentJob;
use App\Jobs\UpdateSyllabusJob;
use App\Models\AcademicClass;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\CanceledClass;
use App\Models\CanceledCourse;
use App\Models\ClassRoom;
use App\Models\ClassTopic;
use App\Models\Course;
use App\Models\RejectedCourse;
use App\Models\Resource;
use App\Models\Topic;

use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserFeedback;
use Illuminate\Http\Request;
use JWTAuth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    //************* Progress of all Courses *************
    public function coursesProgress(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $teacher = User::find($token_user->id);
        $courses = Course::with('classes')->where('teacher_id', $teacher->id)->get();
        $lastarray = [];

        foreach ($courses as $course) {
            $percentage = 0;
            $classes = $course['classes'];

            $completed_classes = 0;
            $remaining_classes = 0;

            foreach ($classes as $class) {
                if ($class->status == 'completed') {
                    $completed_classes = $completed_classes + 1;
                }
                if ($class->status != 'completed') {
                    $remaining_classes = $remaining_classes + 1;
                }
            }
            $total_classes = count($course['classes']);
            if ($total_classes > 0) {
                $percentage = ($completed_classes / $total_classes) * 100;
            }

            array_push(
                $lastarray,
                [
                    'course' => $course,
                    'percentage' => $percentage
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Progress of all courses of teacher',
            'courses_progress' => $lastarray
        ]);
    }

    //************* Progress of Specific Course *************

    public function courseProgress($course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $teacher = User::find($token_user->id);
        $course = Course::with('classes')->where('teacher_id', $teacher->id)->where('id', $course_id)->first();

        $percentage = 0;
        $classes = $course['classes'];

        $completed_classes = 0;
        $remaining_classes = 0;

        $total_classes = count($course['classes']);

        foreach ($classes as $class) {
            if ($class->status == 'completed') {
                $completed_classes = $completed_classes + 1;
            }
            if ($class->status != 'completed') {
                $remaining_classes = $remaining_classes + 1;
            }
        }

        if ($total_classes > 0) {
            $percentage = ($completed_classes / $total_classes) * 100;
        }

        return response()->json([
            'status' => true,
            'message' => 'Course Progress',
            'course' => $course,
            'percentage' => $percentage
        ]);
    }
    //************* View Class Attendees *************
    public function classAttendees($class_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $teacher = User::find($token_user->id);
        $class = AcademicClass::find($class_id);

        $attendees = Attendance::with('user')->where('academic_class_id', $class->id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Class Attendence!',
            'attendees' => $attendees
        ]);
    }

    public function overallSearch($search_query)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $teacher = User::find($token_user->id);
        $courses  = Course::where('teacher_id', $teacher->id)->where('course_name', 'LIKE', "%$search_query%")->get();
        $classes  = AcademicClass::where('teacher_id', $teacher->id)->where('title', 'LIKE', "%$search_query%")->get();

        return response()->json([
            'success' => true,
            'message' => 'Search results for courses and classes',
            'courses' => $courses,
            'classes' => $classes,
        ]);
    }

    //************* View Course Reviews *************
    public function courseReviews($course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $teacher = User::find($token_user->id);

        $reviews = UserFeedback::with('sender', 'course', 'feedback')->where('receiver_id', $teacher->id)->where('course_id', $course_id)->get();
        return response()->json([
            'success' => true,
            'message' => 'Course Reviews!',
            'reviews' => $reviews,
        ]);
    }

    //************* Newly Assigned Courses Detail *************
    public function newlyCourses()
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $teacher = User::find($token_user->id);

        $courses = Course::with('student', 'program', 'country', 'field', 'classes')->where('teacher_id', $teacher->id)->where('status', 'pending')->get();
        return response()->json([
            'success' => true,
            'message' => 'Newly Assigned Courses!',
            'courses' => $courses,
        ]);
    }

    //************* Todays Classes Detail *************
    public function todaysClasses()
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $current_date = Carbon::today()->format('Y-m-d');
        $teacher = User::find($token_user->id);

        $todays_classes = AcademicClass::select('title', "start_date", "end_date", "start_time", "end_time", "course_id", 'duration', 'day', 'status')
            ->with('course', 'course.subject', 'course.student', 'attendence')
            ->where('start_date', $current_date)
            ->where('teacher_id', $teacher->id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Todays Classes!',
            'todays_classes' => $todays_classes,
        ]);
    }

    //************* Reschedule Course *************
    public function rescheduleCourse($course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $teacher = User::find($token_user->id);

        $course = Course::find($course_id);
        if ($course->status != 'completed') {
            return $course;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Course Could not be rescheduled!',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course Rescheduled Successfully!',
        ]);
    }
    //************* Acccept Course *************
    public function acceptCourse($course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $course = Course::find($course_id);
        $user = User::find($course->student_id);
        $teacher = User::find($course->teacher_id);

        // $class = AcademicClass::where('course_id', $course->id)->get();
        $classes = AcademicClass::where('course_id', $course->id)->get();
        $counter = 1;
        foreach ($classes as $class) {

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
                'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
                'title' =>  'Introduction|class1',
                'timezone' => 90,
                'start_time' => $class->start_time,
                'end_time' => $class->end_time,
                'date' => $class->start_date,
                'currency' => "USD",
                'ispaid' => null,
                'is_recurring' => 0,
                'repeat' => 0,
                'weekdays' => null,
                'end_date' => $class->end_date,
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
                $course->status = "active";
                $class->class_id = $responseBody['class_id'];
                $class->status = "scheduled";
                $course->status = "active";
                $course->teacher_status = "available";
                $course->update();
                $class->update();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $responseBody['error'],
                ], 400);
            }
            $counter++;
        }

        $clasroom = ClassRoom::where('course_id', $course_id)->get();
        foreach ($clasroom as $room) {
            $room->status = 'active';
            $room->update();
        }

        $teacher_message = 'Course Accepted Successfully!';
        $student_message = 'Teacher Accepted Your Course!';

        // event(new AcceptCourse($course, $course->teacher_id, $teacher_message, $teacher));
        // event(new AcceptCourse($course, $course->student_id, $student_message, $user));

        return response()->json([
            'success' => true,
            'message' => 'Course Accepted!',
            'course' => $course,
        ]);
    }

    //************* Reject Course *************
    public function rejectCourse($course_id, Request $request)
    {
        $rules = [
            'reason' =>  'required',
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

        $course = Course::find($course_id);
        if ($course->teacher_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Currently no teacher assigned to this course!',
            ], 400);
        }
        $user = User::find($course->student_id);
        $teacher = User::find($course->teacher_id);

        $rejected = new RejectedCourse();
        $rejected->course_id = $course->id;
        $rejected->course_id = $course->id;
        $rejected->user_id = $token_user->id;
        $rejected->user_id = $token_user->id;
        $rejected->reason = $request->reason;
        $rejected->save();

        $classes = AcademicClass::where('course_id', $course->id)->where('teacher_id', $user->id)->get();
        foreach ($classes as $class) {
            $cls = AcademicClass::find($class->id);
            $cls->status = "rejected";
            // $cls->teacher_id = null;
            $cls->update();
        }

        $clasroom = ClassRoom::where('course_id', $course_id)->get();
        foreach ($clasroom as $room) {
            $room->status = 'declined_by_teacher';
            $room->update();
        }
        $teacher_message = "Course Rejected Successfully";
        $student_message = "Teacher Rejected your Course";

        // event(new RejectCourseEvent($course, $course->teacher_id, $teacher_message, $teacher));
        // event(new RejectCourseEvent($course, $course->student_id, $student_message, $user));
        // dispatch(new RejectCourseJob($course, $course->teacher_id, $teacher_message, $teacher));
        // dispatch(new RejectCourseJob($course, $course->student_id, $student_message, $user));

        $course->status = "declined_by_teacher";
        // $course->teacher_id = null;
        $course->teacher_status = "not-available";
        $course->update();


        return response()->json([
            'success' => true,
            'message' => 'Course Rejected!',
            'course' => $course,
        ]);
    }

    public function addTopic(Request $request)
    {

        $rules = [
            "name" => "required",
            "description" => "required",
            "classes" => "required|integer",
            "course_id" => "required|integer",
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

        $course_id = $request->course_id;

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $user = User::find($token_user->id);

        $classes = $request->classes;
        $course = Course::find($request->course_id);
        $academic_classes = AcademicClass::where('topic_id', null)->where('course_id', $course->id)->get();
        $academic_classes_count = AcademicClass::where('topic_id', null)->where('course_id', $course->id)->count();

        if ($classes > $academic_classes_count) {
            return response()->json([
                'success' => false,
                'message' => 'Entered number of classes is greater than actual classes!',
            ], 400);
        } else {

            $topic = new Topic();
            $topic->name = $request->name;
            $topic->description = $request->description;
            $topic->save();
            $counter = 0;

            foreach ($academic_classes as $academic_class) {
                if ($counter < $classes) {
                    $acad_class = AcademicClass::find($academic_class->id);
                    $acad_class->topic_id = $topic->id;

                    $class_topic = new ClassTopic();
                    $class_topic->class_id = $acad_class->id;
                    $class_topic->course_id = $acad_class->course_id;
                    $class_topic->topic_id = $topic->id;
                    $class_topic->user_id = $token_user->id;

                    $class_topic->save();
                    $acad_class->update();
                } else {

                    $topicClasses = Topic::with('classes')->find($topic->id);
                    $academic_classes_count = AcademicClass::where('topic_id', null)->where('course_id', $course->id)->get();

                    $totalTopicClases = AcademicClass::where('topic_id', $topic->id)->count();
                    $completedTopicClases = AcademicClass::where('topic_id', $topic->id)->where('status', 'completed')->count();
                    $total = AcademicClass::where('topic_id', $topic->id)->get();

                    $totaltopicHours = 0;
                    foreach ($total as $class) {
                        $totaltopicHours = $totaltopicHours + $class->duration;
                    }

                    $topicProgress = 0;
                    if ($totalTopicClases > 0) {
                        $topicProgress = ($completedTopicClases / $totalTopicClases) * 100;
                    }

                    // //Emails and notifications
                    // $teacher = User::find($token_user->id);
                    // $student = User::find($course->student_id);
                    // $student_message = "Syllabus has been added to course";
                    // $teacher_message = "Syllabus added Successfully!";

                    // event(new AddSyllabusEvent($student->id, $student, $topic, $student_message));
                    // event(new AddSyllabusEvent($teacher->id, $teacher, $topic, $teacher_message));
                    // dispatch(new AddSyllabusJob($student->id, $student, $topic, $student_message));
                    // dispatch(new AddSyllabusJob($teacher->id, $teacher, $topic, $teacher_message));

                    return response()->json([
                        'success' => true,
                        'message' => 'Topic Added Successfully!',

                        'unclassified_classes' => $academic_classes_count,


                        'topic_detail' => [
                            'total_classes' => $totalTopicClases,
                            'completedTopicClases' => $completedTopicClases,
                            'total_topic_hours' => $totaltopicHours,
                            "topic_progress" => $topicProgress,
                            'topic' => $topicClasses,

                        ],
                    ]);
                }
                $counter++;
            }

            $academic_classes_count = AcademicClass::where('topic_id', null)->where('course_id', $course->id)->get();
            $topicClasses = Topic::with('classes')->find($topic->id);

            $totalTopicClases = AcademicClass::where('topic_id', $topic->id)->count();
            $completedTopicClases = AcademicClass::where('topic_id', $topic->id)->where('status', 'completed')->count();
            $total = AcademicClass::where('topic_id', $topic->id)->get();

            $totaltopicHours = 0;
            foreach ($total as $class) {
                $totaltopicHours = $totaltopicHours + $class->duration;
            }

            $topicProgress = 0;
            if ($totalTopicClases > 0) {
                $topicProgress = ($completedTopicClases / $totalTopicClases) * 100;
            }

            //Emails and notifications
            // $teacher = User::find($token_user->id);
            // $student = User::find($course->student_id);
            // $student_message = "Syllabus has been added to course";
            // $teacher_message = "Syllabus added Successfully!";

            // event(new AddSyllabusEvent($student->id, $student, $topic, $student_message));
            // event(new AddSyllabusEvent($teacher->id, $teacher, $topic, $teacher_message));
            // dispatch(new AddSyllabusJob($student->id, $student, $topic, $student_message));
            // dispatch(new AddSyllabusJob($teacher->id, $teacher, $topic, $teacher_message));

            return response()->json([
                'success' => true,
                'message' => 'Topic Added Successfully!',

                'unclassified_classes' => $academic_classes_count,
                'topic_detail' => [
                    'total_classes' => $totalTopicClases,
                    'completedTopicClases' => $completedTopicClases,
                    'total_topic_hours' => $totaltopicHours,
                    "topic_progress" => $topicProgress,
                    'topic' => $topicClasses,

                ],
            ]);
        }
    }

    public function syllabusDashboard(Request $request, $course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($token_user->role_name == 'teacher') {
            $userrole = 'teacher_id';
        } elseif ($token_user->role_name == 'student') {
            $userrole = 'student_id';
        }

        $teacher = User::find($token_user->id);
        $course = Course::with('subject', 'language', 'program', 'student', 'student')->find($course_id);

        $totalClases = AcademicClass::where('course_id', $course_id)->where($userrole, $token_user->id)->count();
        $completedClases = AcademicClass::where('course_id', $course_id)->where('status', 'completed')->where($userrole, $token_user->id)->count();

        $courseProgress = 0;
        if ($totalClases) {
            $courseProgress = ($completedClases / $totalClases) * 100;
        }


        $classes = AcademicClass::where('course_id', $course_id)->where($userrole, $token_user->id)->get();
        $class_topics = array();
        foreach ($classes as $class) {
            if ($class->topic_id != null) {
                array_push($class_topics, $class->topic_id);
            }
        }
        $class_topics = array_unique($class_topics);
        if ($class_topics == null) {
            $remaining_classes = AcademicClass::where('course_id', $course_id)->where($userrole, $token_user->id)->where('topic_id', null)->get();
            return response()->json([
                'status' => true,
                'message' => 'Syllabus dashboard',
                'course' => $course,
                'course_progress' => $courseProgress,
                'topics' => $class_topics,
                'unclassified_classes' => $remaining_classes,
            ]);
        } else {

            $topics_array = [];
            foreach ($class_topics as $topic) {
                $class_topic = Topic::with('classes')->find($topic);
                $totalTopicClases = AcademicClass::where('course_id', $course_id)->where($userrole, $token_user->id)->where('topic_id', $topic)->count();
                $completedTopicClases = AcademicClass::where('course_id', $course_id)->where('status', 'completed')->where('topic_id', $topic)->where($userrole, $token_user->id)->count();
                $total = AcademicClass::where('course_id', $course_id)->where($userrole, $token_user->id)->where('topic_id', $topic)->get();
                $totaltopicHours = 0;
                foreach ($total as $class) {
                    $totaltopicHours = $totaltopicHours + $class->duration;
                }

                $topicProgress = 0;
                if ($totalTopicClases > 0) {
                    $topicProgress = ($completedTopicClases / $totalTopicClases) * 100;
                }


                array_push($topics_array, [
                    "topic" => $class_topic,
                    "topic_progress" => $topicProgress,
                    "total_classes" => $totalTopicClases,
                    "total_topic_hours" => $totaltopicHours,
                ]);
            }

            $remaining_classes = AcademicClass::where('course_id', $course_id)->where($userrole, $token_user->id)->where('topic_id', null)->get();
            return response()->json([
                'status' => true,
                'message' => 'Syllabus dashboard',
                'course' => $course,
                'course_progress' => $courseProgress,
                'topics' => $topics_array,
                'unclassified_classes' => $remaining_classes,
            ]);
        }
    }

    public function addResource(Request $request, $class_id)
    {
        $rules = [
            // 'academic_class_id' =>  'required',
            'description' => 'required',
            'urls' => 'required|json',
            'files' => 'required',
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

        $user = User::find($token_user->id);

        $academic_class = AcademicClass::find($class_id);
        $resource = new Resource();
        // $resource->academic_class_id= $academic_class->id;
        $resource->user_id = $user->id;
        $resource->description = $request->description;
        $resource->urls = $request->urls;
        if ($request->hasFile('files')) {
            //************* Resource files **********\\
            $resource_files = array();
            $files = $request->file('files');
            foreach ($files as $file) {
                $imageName = date('YmdHis') . random_int(10, 100) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/resources'), $imageName);
                $resource_files[] = $imageName;
            }
            $resource->files = json_encode($resource_files);
            //************* Resource files ends **********\\
        }
        $resource->save();
        $academic_class->resource_id = $resource->id;
        $academic_class->update();

        $resource1 = Resource::with('class')->find($resource->id);
        return response()->json([
            'status' => true,
            'message' => 'Resource Added Successfully!',
            'resource' => $resource1
        ]);
    }

    public function classResources($course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $teacher = User::find($token_user->id);

        $course = Course::with('subject', 'language', 'program', 'student', 'student', 'classes')->where('teacher_id', $teacher->id)->where('id', $course_id)->first();

        return response()->json([
            'status' => true,
            'message' => 'Resources Dasboard!',
            'course' => $course,
        ]);
    }

    public function updateResource(Request $request, $resource_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $teacher = User::find($token_user->id);

        $resource = Resource::with('class')->find($resource_id);
        if ($request->has('description')) {
            $resource->description = $request->description;
        }
        if ($request->has('urls')) {
            $resource->urls = $request->urls;
        }
        if ($request->hasFile('files')) {
            //************* Resource files **********\\
            $resource_files = array();
            $files = $request->file('files');
            foreach ($files as $file) {
                $imageName = date('YmdHis') . random_int(10, 100) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/resources'), $imageName);
                $resource_files[] = $imageName;
            }
            $resource->files = json_encode($resource_files);
            //************* Resource files ends **********\\
        }
        $resource->update();

        return response()->json([
            'status' => true,
            'message' => 'Resource updated successfully!',
            'resource' => $resource,
        ]);
    }

    public function delResource($resource_id)
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $teacher = User::find($token_user->id);


        $resource = Resource::with('class')->find($resource_id);
        $academic_class = AcademicClass::find($resource['class']->id);
        $resource->delete();
        $academic_class->resource_id = null;
        $academic_class->update();

        return response()->json([
            'status' => true,
            'message' => 'Resource Deleted successfully!',
            'resource' => $resource,
        ]);
    }

    public function editResource($resource_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $teacher = User::find($token_user->id);

        // $academic_class = AcademicClass::find($class_id);
        $resource = Resource::with('class')->find($resource_id);

        return response()->json([
            'status' => true,
            'message' => 'Resource Details!',
            'resource' => $resource,
        ]);
    }

    public function addAssignment(Request $request)
    {
        $rules = [
            'course_id' =>  'required|integer',
            'title' =>  'required',
            'description' =>  'required',
            'start_date' =>  'required',
            'deadline' =>  'required',
            'assignee' =>  'required',
            'urls' =>  'required|json',
            'files' =>  'required',
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
        $teacher = User::find($token_user->id);

        $assignment = new Assignment();
        $assignment->title = $request->title;
        $assignment->description = $request->description;
        $assignment->start_date = $request->start_date;
        $assignment->deadline = $request->deadline;
        $assignment->urls = $request->urls;
        $assignment->course_id = $request->course_id;

        $assignments = array();

        if ($request->hasFile('files')) {
            //************* Asignment files **********\\
            $files = $request->file('files');
            foreach ($files as $file) {
                $imageName = date('YmdHis') . random_int(10, 100) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/assignments'), $imageName);
                $assignments[] = $imageName;
            }
            //************* Asignment files ends **********\\
        }


        $assignment->files = json_encode($assignments);
        $assignment->status = 'active';
        $assignment->created_by = $teacher->id;
        $assignment->save();

        $assignees = json_decode($request->assignee);
        foreach ($assignees as $assignee) {
            $user_assignment = new UserAssignment();
            $user_assignment->user_id =  $assignee;
            $user_assignment->assignment_id = $assignment->id;
            $user_assignment->status = 'pending';
            // $user_assignment->course_id = $request->course_id;
            $user_assignment->save();
        }

        $course = Course::findOrFail($request->course_id);
        $student = User::findOrFail($course->student_id);
        $teacher_message = "Successfully Added Assignment!";
        $student_message = "You have a new Assignment!";


        //Sending Emails and notifications to Student and teacher
        // event(new AddAssignmentEvent($assignment, $course->teacher_id, $teacher_message, $teacher));
        // event(new AddAssignmentEvent($assignment, $course->student_id, $student_message, $student));
        // dispatch(new AddAssignmentJob($assignment, $course->teacher_id, $teacher_message, $teacher));
        // dispatch(new AddAssignmentJob($assignment, $course->student_id, $student_message, $student));

        return response()->json([
            'status' => true,
            'message' => 'Course Assignment added Successfully!',
        ]);
    }

    public function assignmentDashboard($course_id, Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $teacher = User::find($token_user->id);

        $corse = Course::find($course_id);

        $course = Course::with('assignments', 'assignments.assignees', 'assignments.assignees.user')
            ->find($course_id);

        $total_assinments = 0;
        $completed_assignments = 0;
        $active_assignments = 0;
        foreach ($course['assignments'] as $assignment) {

            if ($assignment->status == 'active') {
                $active_assignments = $active_assignments + 1;
            }
            if ($assignment->status == 'completed') {
                $completed_assignments = $completed_assignments + 1;
            }
            $total_assinments++;
        }


        if (count($request->all()) >= 1) {

            if ($request->status == 'active') {

                $course = Course::with('assignments.assignees', 'assignments.assignees.user')
                    ->with('assignments', function ($query) {
                        $query->where('status', 'active');
                    })
                    ->where('id', $course_id)->get();
            }
            if ($request->status == 'completed') {

                $course = Course::with('assignments.assignees', 'assignments.assignees.user')
                    ->with('assignments', function ($query) {
                        $query->where('status', 'completed');
                    })
                    ->where('id', $course_id)->get();
            }



            return response()->json([
                'status' => true,
                'message' => 'Course Assignment Dashboard!',
                'course' => $course,
                'total_assignments' => $total_assinments,
                'active_assignments' => $active_assignments,
                'completed_assignments' => $completed_assignments,

            ]);
        }


        return response()->json([
            'status' => true,
            'message' => 'Course Assignment Dashboard!',
            'course' => $course,
            'total_assignments' => $total_assinments,
            'active_assignments' => $active_assignments,
            'completed_assignments' => $completed_assignments,

        ]);
    }

    public function cancelCourse($course_id, Request $request)
    {

        $rules = [
            'reason' =>  'required',
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
        $teacher = User::find($token_user->id);
        $course = Course::find($course_id);
        $user = User::find($course->student_id);
        $teacher = User::find($course->teacher_id);

        if ($course->status == 'canceled_by_teacher' || $course->status == 'canceled_by_student' || $course->status == 'canceled_by_admin') {
            return response()->json([
                'status' => true,
                'message' => 'Course Already cancelled!',
            ], 400);
        }

        $classes = AcademicClass::where('course_id', $course->id)->where('teacher_id', $teacher->id)->get();


        $canceledCourse = new CanceledCourse();
        $canceledCourse->cancelled_by = $token_user->role_name;
        $canceledCourse->student_id = $course->student_id;
        $canceledCourse->teacher_id = $course->teacher_id;
        $canceledCourse->course_id = $course->id;
        $canceledCourse->canceled_classes_count = count($classes->where('status', '!=', 'completed'));
        $canceledCourse->reason = $request->reason;
        $canceledCourse->save();

        foreach ($classes as $class) {
            if ($class->status != 'completed') {
                $cls = AcademicClass::find($class->id);
                // $cls->teacher_id = null;
                $cls->status = 'canceled';

                $canceledClass = new CanceledClass();
                $canceledClass->academic_class_id = $cls->id;
                $canceledClass->canceled_course_id = $canceledCourse->id;
                $canceledClass->save();
                $cls->update();
            }
        }
        $admin = User::where('role_name', "admin")->first();
        $teacher_message = "Course Canceled Successfully";
        $student_message = "Teacher Canceled Course";
        $admin_message = "Teacher Canceled a Course";

        // event(new CancelCourseEvent($course, $course->teacher_id, $teacher_message, $teacher));
        // event(new CancelCourseEvent($course, $course->student_id, $student_message, $user));
        // event(new CancelCourseEvent($course, $admin->id, $admin_message, $admin));
        // dispatch(new CancelCourseJob($course, $course->teacher_id, $teacher_message, $teacher));
        // dispatch(new CancelCourseJob($course, $course->student_id, $student_message, $user));
        // dispatch(new CancelCourseJob($course, $admin->id, $admin_message, $admin));

        // $course->teacher_id = null;
        $course->status = 'cancelled_by_teacher';
        $clasroom = ClassRoom::where('course_id', $course_id)->get();
        foreach ($clasroom as $room) {
            $room->status = 'cancelled_by_teacher';
            $room->update();
        }
        $course->update();

        return response()->json([
            'status' => true,
            'message' => 'Course cancelled successfully!',
            'course' => $course,
        ]);
    }

    public function updateTopic(Request $request)
    {
        $rules = [
            "topic_id" => "required|integer",
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

        $user = User::find($token_user->id);
        $class_topic = ClassTopic::where('topic_id', $request->topic_id)->first();

        $course_id = $class_topic->course_id;

        $classes = $request->classes;
        $course = Course::find($course_id);
        $topic = Topic::find($request->topic_id);



        $academic_classes = AcademicClass::where('topic_id', $topic->id)->where('course_id', $class_topic->course_id)->get();

        if ($request->has('classes')) {
            if ($request->classes > count($academic_classes)) {
                // return "greater";
                $required_classes = $request->classes -  count($academic_classes);
                $academic_clsses = AcademicClass::where('topic_id', null)->where('course_id', $course->id)->get();
                if ($required_classes > count($academic_clsses)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Entered number of classes is greater than actual classes!',
                    ], 400);
                } else {

                    $counter = 0;
                    $classTopic = Topic::with('classes')->find($request->topic_id);
                    if ($request->has('name')) {
                        $classTopic->name = $request->name;
                    }
                    if ($request->has('description')) {
                        $classTopic->description = $request->description;
                    }

                    foreach ($academic_clsses as $academic_class) {
                        if ($counter < $required_classes) {
                            $acad_class = AcademicClass::find($academic_class->id);
                            $acad_class->topic_id = $topic->id;

                            $class_topic = new ClassTopic();
                            $class_topic->class_id = $acad_class->id;
                            $class_topic->course_id = $acad_class->course_id;
                            $class_topic->topic_id = $topic->id;
                            $class_topic->user_id = $token_user->id;

                            $class_topic->save();
                            $acad_class->update();
                        } else {
                            $classTopic->update();
                            $classTopic = Topic::with('classes')->find($request->topic_id);
                            $academic_classes_count = AcademicClass::where('topic_id', null)->where('course_id', $course->id)->get();
                            // $classified_classes = AcademicClass::where('topic_id',$topic->id )->where('course_id', $course->id)->get();

                            $totalTopicClases = AcademicClass::where('topic_id', $topic->id)->count();
                            $completedTopicClases = AcademicClass::where('topic_id', $topic->id)->where('status', 'completed')->count();
                            $total = AcademicClass::where('topic_id', $topic->id)->get();

                            $totaltopicHours = 0;
                            foreach ($total as $class) {
                                $totaltopicHours = $totaltopicHours + $class->duration;
                            }

                            $topicProgress = 0;
                            if ($totalTopicClases > 0) {
                                $topicProgress = ($completedTopicClases / $totalTopicClases) * 100;
                            }

                            //Emails and notifications
                            $teacher = User::find($token_user->id);
                            $student = User::find($course->student_id);
                            $student_message = "Syllabus has been updated";
                            $teacher_message = "Syllabus updated Successfully!";

                            // event(new UpdateSyllabusEvent($student->id, $student, $topic, $student_message));
                            // event(new UpdateSyllabusEvent($teacher->id, $teacher, $topic, $teacher_message));
                            // dispatch(new UpdateSyllabusJob($student->id, $student, $topic, $student_message));
                            // dispatch(new UpdateSyllabusJob($teacher->id, $teacher, $topic, $teacher_message));



                            return response()->json([
                                'success' => true,
                                'message' => 'Topic Added Successfully!',

                                // 'classified_classes' => $classified_classes,
                                'unclassified_classes' =>  $academic_classes_count,
                                'topic_detail' => [
                                    'total_classes' => $totalTopicClases,
                                    'completedTopicClases' => $completedTopicClases,
                                    'total_topic_hours' => $totaltopicHours,
                                    "topic_progress" => $topicProgress,
                                    'topic' => $classTopic,

                                ],
                            ]);
                        }
                        $counter++;
                    }
                }
            }

            if ($request->classes < count($academic_classes)) {
                // return 'lesss';
                // return "less";
                $academic_classes = AcademicClass::where('topic_id', $topic->id)->where('course_id', $course->id)->orderBy('id', 'desc')->get();
                $required_classes = count($academic_classes) - $request->classes;

                $counter = 0;
                if ($counter < $required_classes) {
                    $classTopic = Topic::with('classes')->find($request->topic_id);
                    if ($request->has('name')) {
                        $classTopic->name = $request->name;
                    }
                    if ($request->has('description')) {
                        $classTopic->description = $request->description;
                    }

                    foreach ($academic_classes as $academic_class) {
                        if ($counter < $required_classes) {
                            //  $academic_class;
                            $acad_class = AcademicClass::find($academic_class->id);
                            $acad_class->topic_id = null;
                            $class_topic = ClassTopic::with('classes')->where('topic_id', $request->topic_id)->where('class_id', $acad_class->id)->where('course_id', $course_id)->first();
                            $class_topic->delete();
                            $acad_class->update();
                        } else {

                            $classTopic->update();
                            $classTopic = Topic::with('classes')->find($request->topic_id);
                            $academic_classes_count = AcademicClass::where('topic_id', null)->where('course_id', $course->id)->get();
                            // $classified_classes = AcademicClass::where('topic_id',$topic->id )->where('course_id', $course->id)->get();

                            $totalTopicClases = AcademicClass::where('topic_id', $topic->id)->count();
                            $completedTopicClases = AcademicClass::where('topic_id', $topic->id)->where('status', 'completed')->count();
                            $total = AcademicClass::where('topic_id', $topic->id)->get();

                            $totaltopicHours = 0;
                            foreach ($total as $class) {
                                $totaltopicHours = $totaltopicHours + $class->duration;
                            }

                            $topicProgress = 0;
                            if ($totalTopicClases > 0) {
                                $topicProgress = ($completedTopicClases / $totalTopicClases) * 100;
                            }

                            //Emails and notifications
                            $teacher = User::find($token_user->id);
                            $student = User::find($course->student_id);
                            $student_message = "Syllabus has been updated";
                            $teacher_message = "Syllabus updated Successfully!";

                            // event(new UpdateSyllabusEvent($student->id, $student, $topic, $student_message));
                            // event(new UpdateSyllabusEvent($teacher->id, $teacher, $topic, $teacher_message));
                            // dispatch(new UpdateSyllabusJob($student->id, $student, $topic, $student_message));
                            // dispatch(new UpdateSyllabusJob($teacher->id, $teacher, $topic, $teacher_message));

                            return response()->json([
                                'success' => true,
                                'message' => 'Topic Added Successfully!',
                                'unclassified_classes' =>  $academic_classes_count,
                                'topic_detail' => [

                                    'total_classes' => $totalTopicClases,
                                    'completedTopicClases' => $completedTopicClases,
                                    'total_topic_hours' => $totaltopicHours,
                                    "topic_progress" => $topicProgress,
                                    'topic' => $classTopic,
                                ],
                            ]);
                        }
                        $counter++;
                    }
                }
            }
            if ($request->classes == count($academic_classes)) {
                // return "equal";
                $classTopic = Topic::with('classes')->find($request->topic_id);
                if ($request->has('name')) {
                    $classTopic->name = $request->name;
                }
                if ($request->has('description')) {
                    $classTopic->description = $request->description;
                }
                $classTopic->update();
                // $classTopic = Topic::find($request->topic_id);
                $academic_classes_count = AcademicClass::where('topic_id', null)->where('course_id', $course->id)->get();
                // $classified_classes = AcademicClass::where('topic_id',$topic->id )->where('course_id', $course->id)->get();

                $totalTopicClases = AcademicClass::where('topic_id', $topic->id)->count();
                $completedTopicClases = AcademicClass::where('topic_id', $topic->id)->where('status', 'completed')->count();
                $total = AcademicClass::where('topic_id', $topic->id)->get();

                $totaltopicHours = 0;
                foreach ($total as $class) {
                    $totaltopicHours = $totaltopicHours + $class->duration;
                }

                $topicProgress = 0;
                if ($totalTopicClases > 0) {
                    $topicProgress = ($completedTopicClases / $totalTopicClases) * 100;
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Topic updated successfully',

                    // 'classified_classes' => $classified_classes,
                    'unclassified_classes' => $academic_classes_count,
                    'topic_detail' => [

                        'topic' => $classTopic,
                        'total_classes' => $totalTopicClases,
                        'completedTopicClases' => $completedTopicClases,
                        'total_topic_hours' => $totaltopicHours,
                        "topic_progress" => $topicProgress,

                    ],
                ]);
            }
        }



        $classTopic = Topic::with('classes')->find($request->topic_id);
        if ($request->has('name')) {
            $classTopic->name = $request->name;
        }
        if ($request->has('description')) {
            $classTopic->description = $request->description;
        }
        $classTopic->update();
        $academic_classes_count = AcademicClass::where('topic_id', null)->where('course_id', $course->id)->get();

        $totalTopicClases = AcademicClass::where('topic_id', $topic->id)->count();
        $completedTopicClases = AcademicClass::where('topic_id', $topic->id)->where('status', 'completed')->count();
        $total = AcademicClass::where('topic_id', $topic->id)->get();

        $totaltopicHours = 0;
        foreach ($total as $class) {
            $totaltopicHours = $totaltopicHours + $class->duration;
        }

        $topicProgress = 0;
        if ($totalTopicClases > 0) {
            $topicProgress = ($completedTopicClases / $totalTopicClases) * 100;
        }


        return response()->json([
            'status' => true,
            'message' => 'Topic updated successfully',

            'unclassified_classes' => $academic_classes_count,
            'topic_detail' => [

                'topic' => $classTopic,
                'total_classes' => $totalTopicClases,
                'completedTopicClases' => $completedTopicClases,
                'total_topic_hours' => $totaltopicHours,
                "topic_progress" => $topicProgress,

            ],
        ]);
    }



    public function editTopicClass(Request $request, $id)
    {

        $rules = [


            "title" => "required|string",
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

        $teacher = User::find($token_user->id);

        $academic_class = AcademicClass::find($id);

        if ($academic_class->class_id != null) {
            /// Curl Implementation
            $apiURL = 'https://api.braincert.com/v2/updateclass';
            $postInput = [
                'apikey' =>  'xKUyaLJHtbvBUtl3otJc',
                'id' => $academic_class->class_id
            ];

            $client = new Client();
            $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody['status'] == 'ok') {
                $academic_class->title = $request->title;
                $academic_class->update();

                return response()->json([
                    'status' => true,
                    'message' => "Class updated Successfully!",
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $responseBody['error'],
                ]);
            }
        } else {

            $academic_class->title = $request->title;
            $academic_class->update();

            return response()->json([
                'status' => true,
                'message' => "Class updated Successfully!",
            ]);
        }
    }

    public function editTopic($topic_id)
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $teacher = User::find($token_user->id);

        $topic = Topic::find($topic_id);

        $totalTopicClases = AcademicClass::where('topic_id', $topic->id)->count();
        $completedTopicClases = AcademicClass::where('topic_id', $topic->id)->where('status', 'completed')->count();
        $total = AcademicClass::where('topic_id', $topic->id)->get();

        $totaltopicHours = 0;
        foreach ($total as $class) {
            $totaltopicHours = $totaltopicHours + $class->duration;
        }

        $topicProgress = 0;
        if ($totalTopicClases > 0) {
            $topicProgress = ($completedTopicClases / $totalTopicClases) * 100;
        }

        return response()->json([
            'status' => true,
            'message' => 'Topic details',
            'topic' => $topic,
            'topic_progress' => $topicProgress,
            'total_classes' => $totalTopicClases,
            'total_topic_hours' => $totaltopicHours,
        ]);
    }

    public function assignmentDetail($assignment_id)
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $assignment = Assignment::with('assignees', 'assignees.user')->find($assignment_id);

        return response()->json([
            'status' => true,
            'message' => 'Assignment details',
            'assignment' => $assignment,
        ]);
    }

    public function updateAssignment($assignment_id, Request $request)
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        $teacher = User::find($token_user->id);

        $assignment = Assignment::find($assignment_id);
        $assignment->title = $request->title;
        $assignment->description = $request->description;
        $assignment->start_date = $request->start_date;
        $assignment->deadline = $request->deadline;
        if ($request->has('urls')) {
            $new_urls = [];
            $common_urls = [];
            $final_urls = [];
            $resource_urls = json_decode($request->urls);
            $db_urls = json_decode($assignment->urls);
            foreach ($resource_urls as $url) {
                if (in_array($url, $db_urls)) {
                    array_push($common_urls, $url);
                } else {
                    array_push($new_urls, $url);
                }
            }

            $final_urls = array_merge($common_urls, $new_urls);

            $assignment->urls = $final_urls;
            // return response()->json([
            //     'common_urls'=>$common_urls,
            //     'new_urls'=>$new_urls,
            //     'final_urls'=>$final_urls,
            // ]);

        }
        $assignment->course_id = $assignment->course_id;

        $assignments = array();

        if ($request->hasFile('files')) {
            //************* Asignment files **********\\
            $files = $request->file('files');
            foreach ($files as $file) {
                $imageName = date('YmdHis') . random_int(10, 100) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/assignments'), $imageName);
                $assignments[] = $imageName;
            }
            //************* Asignment files ends **********\\
            $assignment->files = json_encode($assignments);
        }



        $assignment->status = 'active';
        $assignment->created_by = $teacher->id;
        $assignment->update();

        $assignees = json_decode($request->assignee);

        $assignment = Assignment::with('assignees')->find($assignment_id);

        $db_assignees = [];
        foreach ($assignment['assignees'] as $assignee) {
            array_push($db_assignees, $assignee->user->id);
        }

        //******** Requested Array loop ********
        foreach ($assignees as $assignee) {
            if (in_array($assignee, $db_assignees)) {
            } else {
                $user_assignment = new UserAssignment();
                $user_assignment->user_id =  $assignee;
                $user_assignment->assignment_id = $assignment->id;
                $user_assignment->status = 'pending';
                $user_assignment->save();
            }
        }

        //******** Database Array loop ********
        foreach ($assignment['assignees'] as $assignee) {
            if (in_array($assignee->user_id, $assignees)) {
            } else {
                $user_assignment = UserAssignment::find($assignee->id);
                $user_assignment->delete();
            }
        }

        //******** returning response ********
        $assignment = Assignment::with('assignees')->find($assignment_id);

        $course = Course::findOrFail($assignment->course_id);
        $student = User::findOrFail($course->student_id);
        $teacher_message = "Successfully Updated Assignment!";
        $student_message = "Your Assignment has been updated!";

        //Sending Emails and notifications to Student and teacher
        // event(new UpdateAssignmentEvent($assignment, $course->teacher_id, $teacher_message, $teacher));
        // event(new UpdateAssignmentEvent($assignment, $course->student_id, $student_message, $student));
        // dispatch(new UpdateAssignmentJob($assignment, $course->teacher_id, $teacher_message, $teacher));
        // dispatch(new UpdateAssignmentJob($assignment, $course->student_id, $student_message, $student));

        return response()->json([
            'status' => true,
            'message' => 'Course Assignment updated Successfully!',
            'assignment' => $assignment,
        ]);
    }

    public function deleteTopic(Request $request, $topic_id)
    {


        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $topic = Topic::find($topic_id);
        // $academic_classes = AcademicClass::where('topic_id',$topic_id)->get();

        foreach ($topic->classes as $academic_class) {
            // $academic_class;
            $class = AcademicClass::find($academic_class->id);
            $class->topic_id = null;
            $class->update();
        }
        // return $topic->classes;
        // // $topic->classes->delete();
        $topic->delete();

        $userTopic = ClassTopic::where('topic_id', $topic_id)->first();

        $unclassified_classes = AcademicClass::where('topic_id', null)->where('course_id', $userTopic->course_id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Course Assignment updated Successfully!',
            'deleted_topic' => $topic,
            'unclassified_classes' => $unclassified_classes,
        ]);
    }

    public function deleteAssignment($assignment_id)
    {
        $assignment = Assignment::find($assignment_id);
        // $assignment->assignees->delete();
        foreach ($assignment->assignees as $assignee) {
            $assignee->delete();
        }
        $assignment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Course Assignment updated Successfully!',
            'deleted_assignment' => $assignment,

        ]);
    }
}
