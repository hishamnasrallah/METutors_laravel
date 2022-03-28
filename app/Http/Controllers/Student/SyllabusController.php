<?php

namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;

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
   
   
    public function syllabusDashboard(Request $request, $course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

         if($token_user->role_name == 'teacher'){
            $userrole='teacher_id';
       }elseif($token_user->role_name == 'student') {
         $userrole='student_id';
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

   
}
