<?php

namespace App\Http\Controllers;

use App\Events\NewCertificateEvent;
use App\Jobs\NewCertificateJob;
use App\Models\Certificate;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\User;
use App\Models\UserFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function certificates()
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        // if requested user is not a student
        if ($token_user->role_name != 'student') {
            return response()->json([
                'success' => true,
                'message' => 'Access Denied',
            ], 400);
        }


        $courses = Course::where('student_id', $token_user->id)
            ->where('status', 'completed')
            ->get();

        $user = User::findOrFail($token_user->id);
        foreach ($courses as $course) {
            $certificate = Certificate::where('student_id', $token_user->id)
                ->where('course_id', $course->id)
                ->first();

            // if no certificare found
            if ($certificate == '') {
                $user_certificate = new Certificate();
                $user_certificate->student_id = $token_user->id;
                $user_certificate->course_id = $course->id;
                $user_certificate->save();

                event(new NewCertificateEvent( $user,'Your certificate is ready to download', $user_certificate));
                dispatch(new NewCertificateJob( $user,'Your certificate is ready to download', $user_certificate));
            }
        }

        $certificates = Certificate::with('course.classes', 'course.program', 'course.subject', 'course.teacher.teacher_qualification')
            ->where('student_id', $token_user->id)
            ->select('id','student_id', 'course_id')
            ->get();

        foreach ($certificates as $certificate) {

            $certificate->id = $certificate->id;
            $certificate->course_name = $certificate['course']->course_name;
            $certificate->classes_count = count($certificate['course']['classes']);
            $certificate->program = $certificate['course']['program']->code;
            $certificate->grade = $certificate['course']['subject']->grade;
            $certificate->completed_hours = $certificate['course']->total_hours;
            $certificate->completed_at = $certificate['course']->updated_at;
            $certificate->teacher = $certificate['course']['teacher']->first_name . ' ' . $certificate['course']['teacher']->last_name;
            $certificate->tag_line =  $certificate['course']['teacher']['teacher_qualification'][0]->degree_field;

            // calculating average rating
            $average_rating = 5.0;
            $rating_sum = UserFeedback::where('receiver_id', $certificate['course']->teacher_id)->sum('rating');
            $total_reviews = UserFeedback::where('receiver_id', $certificate['course']->teacher_id)->count();
            if ($total_reviews > 0) {
                $average_rating = $rating_sum / $total_reviews;
            }

            $certificate->teacher_rating = Str::limit($average_rating,3,'');

            unset($certificate['course']);
        }

        return response()->json([
            'success' => true,
            'message' => 'User certificates',
            'certificates' => $certificates,
        ]);
    }

    public function certificate_detail($certificate_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $certificate = Certificate::with('course.student')
            ->where('id', $certificate_id)
            ->select('student_id', 'course_id')
            ->first();

        // if requested user is not a student
        if ($token_user->role_name != 'student') {
            return response()->json([
                'success' => true,
                'message' => 'Access Denied',
            ], 400);
        }

        // if certificate does not exist
        if ($certificate == '') {
            return response()->json([
                'success' => true,
                'message' => 'Certificate not found',
            ], 400);
        }

        $certificate->student_name = $certificate['course']['student']->first_name . ' ' . $certificate['course']['student']->last_name;
        $certificate->course_name = $certificate['course']->course_name;
        $certificate->completed_at = $certificate['course']->updated_at;

        unset($certificate['course']);


        return response()->json([
            'success' => true,
            'message' => 'Certificate details',
            'certificate' => $certificate,
        ]);
    }
}
