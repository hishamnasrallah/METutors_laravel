<?php

namespace App\Models;

use App\Country;
use App\Events\CancelCourse;
use App\FieldOfStudy;
use App\Language;
use App\Program;
use App\Subject;
use App\TeachingSpecification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;

class Course extends Model
{
    use HasFactory;
     protected $casts = [
        'files' => 'array',
    ];

    public function classes()
    {
        return $this->hasMany(AcademicClass::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function field()
    {
        return $this->belongsTo(FieldOfStudy::class, 'field_of_study', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id')->select('id', 'id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar', 'status');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id')->select('id', 'id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function fields()
    {
        return $this->hasMany(FieldOfStudy::class, 'field_of_study', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class)->select('id', 'name', 'emojiU');
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'id', 'resource_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function assignees()
    {
        return $this->hasMany(UserAssignment::class);
    }

    public function resnponse_recieved()
    {
        return $this->hasMany(Assignment::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(UserFeedback::class, 'course_id', 'id');
    }

    public function participants()
    {
        return $this->hasMany(ClassRoom::class, 'course_id', 'id');
    }

    public function attendence()
    {
        return $this->hasMany(Attendance::class, 'course_id', 'id');
    }

    public function canceled_classes()
    {
        return $this->hasMany(CanceledCourse::class,  'course_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(ClassRoom::class, 'course_id', 'id');
    }

    public function remaining_classes()
    {
        return $this->hasMany(AcademicClass::class);
    }

    public function completed_classes()
    {
        return $this->hasMany(AcademicClass::class);
    }

    public function student_rating()
    {
        return $this->hasMany(UserFeedback::class);
    }

    public function cancelled_classes($course_id)
    {
        $cancelled_course = CanceledCourse::where('course_id', $course_id)->first();
        $cancelled_classes = $cancelled_course->classes;
        return $cancelled_classes;
    }

    public function active_classes($course_id)
    {
        $completed_classes = AcademicClass::where('status', 'completed')->get();
        return $completed_classes;
    }

    public function teacherSpecifications()
    {
        return $this->belongsTo(TeachingSpecification::class, 'teacher_id', 'user_id');
    }

    public function program_country()
    {
        return $this->belongsTo(ProgramCountry::class, 'country_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'course_id', 'id');
    }

    public function course_order()
    {
        return $this->belongsTo(Order::class, 'id', 'course_id');
    }
    
}
