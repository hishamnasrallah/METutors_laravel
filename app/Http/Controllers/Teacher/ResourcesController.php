<?php

namespace App\Http\Controllers\Teacher;

use App\Events\AddResource;
use App\Events\AddResourceEvent;
use App\Events\Resource as EventsResource;
use App\Events\UpdateResourceEvent;
use App\Http\Controllers\Controller;
use App\Jobs\AddResourceJob;
use App\Jobs\UpdateResourceJob;
use App\Models\AcademicClass;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\CanceledCourse;
use App\Models\ClassTopic;
use App\Models\Course;
use App\Models\RejectedCourse;
use App\Models\Resource;
use App\Models\Topic;
use App\Models\Images;
use App\Models\ResourceDocument;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserFeedback;
use Illuminate\Http\Request;
use JWTAuth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use File;

class ResourcesController extends Controller
{





    public function deleteFile(Request $request, $id)
    {

        $image = Images::find($id);
        if ($image == null) {
            return response()->json([
                'status' => false,
                'message' => 'file not found!',

            ], 400);
        }
        $image->delete();

        $file_path = public_path('assets/') . $image->filename;

        // print_r($file_path);die;
        if (File::exists($file_path)) {
            File::delete($file_path);
        }

        return response()->json([
            'status' => true,
            'message' => 'file deleted successfully!',

        ]);
    }


    public function uploadFiles(Request $request)
    {
        $rules = [

            'file' => 'required',
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

        if ($request->hasFile('file')) {
            //************* Resource files **********\\
            $resource_files = array();


            $imageName = date('YmdHis') . random_int(10, 100) . '.' . $request->file->getClientOriginalExtension();
            $request->file->move(public_path('uploads'), $imageName);

            $image = new Images();
            $image->filename = $imageName;
            $image->original_name = $request->file->getClientOriginalName();

            $image->url = $request->root() . '/uploads/' . $imageName;
            $image_url = $request->root() . '/uploads/' . $imageName;

            $image->save();


            //************* Resource files ends **********\\

            $file_size = $request->size;
            $file_name = $request->file->getClientOriginalName();
        }

        $array = array(
            'id' => $image->id ?? '',
            'url' => $image_url ?? '',
            'size' => $file_size,
            'original_name' => $file_name
        );

        return response()->json([
            'status' => true,
            'message' => 'files uploaded!',
            'file' => [$array],

        ]);
    }
    public function onboarding(Request $request)
    {
        $rules = [

            'file' => 'required',
            'size' => 'required',
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

        if ($request->hasFile('file')) {
            //************* Resource files **********\\
            $resource_files = array();

             if (file_exists(public_path() . '/uploads/onboarding/onboarding.pdf')) {
                    unlink(public_path() . '/uploads/onboarding/onboarding.pdf');
             }
            $imageName = 'onboarding' . '.' . $request->file->getClientOriginalExtension();
            $request->file->move(public_path('uploads/onboarding'), $imageName);

            $image = new Images();
            $image->filename = $imageName;
            $image->original_name = $request->file->getClientOriginalName();

            $image->url = $request->root() . '/uploads/onboarding/' . $imageName;
            $image_url = $request->root() . '/uploads/onboarding/' . $imageName;

            $image->save();


            //************* Resource files ends **********\\

            $file_size = $request->size;
            $file_name = $request->file->getClientOriginalName();
        }

        $array = array(
            'id' => $image->id ?? '',
            'url' => $image_url ?? '',
            'size' => $file_size,
            'original_name' => $file_name
        );

        return response()->json([
            'status' => true,
            'message' => 'files uploaded!',
            'file' => [$array],

        ]);
    }
    public function addResource(Request $request, $class_id)
    {
        $rules = [
            // 'academic_class_id' =>  'required',
            'description' => 'required',
            'urls' => 'required',
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
        $resource->urls = json_encode($request->urls);
        $resource->files = json_encode($request['files']);
        $resource->save();
        $academic_class->resource_id = $resource->id;
        $academic_class->update();

        $resource1 = Resource::with('class')->find($resource->id);

        $resource1->urls = json_decode($resource1->urls);
        $resource1->files = json_decode($resource1->files);

        // Event notification
        $teacher = User::find($token_user->id);
        $student = User::find($academic_class->student_id);
        $student_message = "Resource has been added!";
        $teacher_message = "Resource has been added!";

        //Emails and notifications
        // event(new AddResourceEvent($teacher->id, $teacher_message, $resource1, $teacher));
        // event(new AddResourceEvent($student->id, $student_message, $resource1, $student));
        // dispatch(new AddResourceJob($teacher->id, $teacher_message, $resource1, $teacher));
        // dispatch(new AddResourceJob($student->id, $student_message, $resource1, $student));

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

        $resource_teacher_documents  = ResourceDocument::with('user')->where('course_id',$course_id)->where('user_role','teacher')->get();
        $resource_student_documents  = ResourceDocument::with('user')->where('course_id',$course_id)->where('user_role','student')->get();
        $course = Course::with('subject', 'language', 'program', 'student', 'student', 'classes')->where('teacher_id', $teacher->id)->where('id', $course_id)->first();

        return response()->json([
            'status' => true,
            'message' => 'Resources Dasboard!',
            'teacher_other_documents' => $resource_teacher_documents,
            'student_other_documents' => $resource_student_documents,
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
        if ($request->has('files')) {

            $resource->files = $request['files'];
        }
        $resource->update();


        // Event notification
        $academic_class = AcademicClass::where('resource_id', $resource_id)->first();
        $teacher = User::find($token_user->id);
        $student = User::find($academic_class->student_id);
        $student_message = "Resource has been updated!";
        $teacher_message = "Resource has been updated!";

        //Emails and notifications
        event(new UpdateResourceEvent($teacher->id, $teacher_message, $resource, $teacher));
        event(new UpdateResourceEvent($student->id, $student_message, $resource, $student));
        dispatch(new UpdateResourceJob($teacher->id, $teacher_message, $resource, $teacher));
        dispatch(new UpdateResourceJob($student->id, $student_message, $resource, $student));
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

        // print_r($resource);die; 

        $resource->urls = json_decode($resource->urls);
        $resource->files = json_decode($resource->files);

        return response()->json([
            'status' => true,
            'message' => 'Resource Details!',
            'resource' => $resource,
        ]);
    }
}
