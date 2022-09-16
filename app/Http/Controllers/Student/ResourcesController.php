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

class ResourcesController extends Controller
{


    public function classResources($course_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        if ($token_user->role_name == 'teacher') {
            $userrole = 'teacher_id';
        } elseif ($token_user->role_name == 'student') {
            $userrole = 'student_id';
        }


        $teacher = User::find($token_user->id);

        $course = Course::with('subject', 'language', 'program', 'student', 'teacher', 'classes')->where($userrole, $token_user->id)->where('id', $course_id)->first();

        return response()->json([
            'status' => true,
            'message' => 'Resources Dasboard!',
            'course' => $course,
        ]);
    }


    public function editResource($resource_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);



        $resource = Resource::with('class')->find($resource_id);

        $resource->urls=json_decode($resource->urls);
        $resource->files=json_decode($resource->files);

        return response()->json([
            'status' => true,
            'message' => 'Resource Details!',
            'resource' => $resource,
        ]);
    }

    public function updateResource(Request $request, $resource_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $resource = Resource::with('class')->find($resource_id);
        if ($request->has('description')) {
            $resource->description = $request->description;
        }
        if ($request->has('urls')) {
            $new_urls=[];
            $common_urls=[];
            $final_urls=[];
            $resource_urls = json_decode($request->urls);
            $db_urls = json_decode($resource->urls);
            foreach($resource_urls as $url){
                if(in_array($url,$db_urls)){
                    array_push($common_urls,$url);
                }
                else{
                    array_push($new_urls,$url);
                }
            }
        
            $final_urls = array_merge($common_urls, $new_urls);

            $resource->urls=$final_urls;
            // return response()->json([
            //     'common_urls'=>$common_urls,
            //     'new_urls'=>$new_urls,
            //     'final_urls'=>$final_urls,
            // ]);

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

        $resource->urls=json_decode($resource->urls);
        $resource->files=json_decode($resource->files);

        return response()->json([
            'status' => true,
            'message' => 'Resource updated successfully!',
            'resource' => $resource,
        ]);
    }
}
