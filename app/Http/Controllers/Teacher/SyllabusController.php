<?php

namespace App\Http\Controllers\Teacher;

use App\Events\AddSyllabusEvent;
use App\Http\Controllers\Controller;
use App\Jobs\AddSyllabusJob;
use App\Models\AcademicClass;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\CanceledCourse;
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

class SyllabusController extends Controller
{

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


                    $teacher = User::find($token_user->id);
                    $student = User::find($course->student_id);
                    $student_message = "Syllabus has been added to course";
                    $teacher_message = "Syllabus added Successfully!";

                    event(new AddSyllabusEvent($student->id, $student, $topic, $student_message));
                    event(new AddSyllabusEvent($teacher->id, $teacher, $topic, $teacher_message));
                    dispatch(new AddSyllabusJob($student->id, $student, $topic, $student_message));
                    dispatch(new AddSyllabusJob($teacher->id, $teacher, $topic, $teacher_message));

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


            $teacher = User::find($token_user->id);
            $student = User::find($course->student_id);
            $student_message = "Syllabus has been added to course";
            $teacher_message = "Syllabus added Successfully!";

            event(new AddSyllabusEvent($student->id, $student, $topic, $student_message));
            event(new AddSyllabusEvent($teacher->id, $teacher, $topic, $teacher_message));
            dispatch(new AddSyllabusJob($student->id, $student, $topic, $student_message));
            dispatch(new AddSyllabusJob($teacher->id, $teacher, $topic, $teacher_message));

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


                            return response()->json([
                                'success' => true,
                                'message' => 'Topic Added Successfully!',

                                // 'classified_classes' => $classified_classes,
                                'unclassified_classes' =>  $academic_classes_count,
                                'topic_detail' => [

                                    'topic' => $classTopic,
                                    'total_classes' => $totalTopicClases,
                                    'completedTopicClases' => $completedTopicClases,
                                    'total_topic_hours' => $totaltopicHours,
                                    "topic_progress" => $topicProgress,

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
                            return response()->json([
                                'success' => true,
                                'message' => 'Topic Added Successfully!',

                                // 'classified_classes' => $classified_classes,
                                'unclassified_classes' =>  $academic_classes_count,
                                'topic_detail' => [

                                    'topic' => $classTopic,
                                    'total_classes' => $totalTopicClases,
                                    'completedTopicClases' => $completedTopicClases,
                                    'total_topic_hours' => $totaltopicHours,
                                    "topic_progress" => $topicProgress,

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
}
