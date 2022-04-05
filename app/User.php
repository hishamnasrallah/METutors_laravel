<?php

namespace App;

use App\UserCode;
use App\Models\Accounting;
use App\Models\Badge;
use App\Models\Meeting;
use App\Models\Noticeboard;
use App\Models\Notification;
use App\Models\Permission;
use App\Models\QuizzesResult;
use App\Models\Role;
use App\Models\Follow;
use App\Models\Sale;
use App\Models\Section;
use App\Models\Webinar;
use App\Models\Ticket;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \App\Mail\SendMailOtp;
use App\Models\ClassRoom;
use App\Models\UserFeedback;
use Tymon\JWTAuth\Contracts\JWTSubject;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable;

    static $active = 'active';
    static $pending = 'pending';
    static $inactive = 'inactive';

    protected $dateFormat = 'U';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $hidden = [
        'password', 'remember_token', 'google_id', 'facebook_id'
    ];

    static $statuses = [
        'active', 'pending', 'inactive'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    private $permissions;
    private $user_group;
    private $userInfo;



    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function teacher_qualification()
    {
        return $this->hasOne('App\TeachingQualification');
    }

    // public function feedbacks(){
    //     return $this->hasMany(\App\Models\Feedback::class,'teacher_id','id')->select('id','teacher_id','course_id','review','rating','kudos_points','feedback_by');
    // }

    public function teacher_specification()
    {
        return $this->hasOne(TeachingSpecification::class);
    }

    public function spoken_languages()
    {
        return $this->hasMany(SpokenLanguage::class);
    }

    public function teacher_subjects()
    {
        return $this->hasMany(TeacherSubject::class);
    }



    public function teacherAvailability()
    {
        return $this->hasMany(TeacherAvailability::class);
    }

    public function teacherProgram()
    {
        return $this->hasMany(TeacherProgram::class);
    }



    public function teacherSubject()
    {
        return $this->hasMany(TeacherSubject::class);
    }







    static function getAdmin()
    {
        $role = Role::where('name', Role::$admin)->first();

        $admin = self::where('role_name', $role->name)
            ->where('role_id', $role->id)
            ->first();

        return $admin;
    }


    public function adminOtp()
    {

        $code = rand(1000, 9999);
        UserCode::updateOrCreate(
            ['user_id' => $this->id],
            ['code' => $code]
        );

        $details  = [
            'title' => 'Two Factor authentication for MEtutors',
            'message' => 'Your one time password for MEtutor',
            'code' => $code,
            'ignoremessage' => "If you didnt submit this request, please ignore it.",
        ];
        \Mail::to($this->email)->send(new SendMailOtp($details));

        return response()->json([
            'status' => true,
            'message' => 'Otp has been sent to your email',

        ]);
    }

    public function resendOtp()
    {

        $code = rand(1000, 9999);
        UserCode::updateOrCreate(
            ['user_id' => $this->id],
            ['code' => $code]
        );

        $details  = [
            'title' => 'Two Factor authentication for MEtutors',
            'message' => 'Your one time password for MEtutor',
            'code' => $code,
            'ignoremessage' => "If you didnt submit this request, please ignore it.",
        ];
        \Mail::to($this->email)->send(new SendMailOtp($details));

        return response()->json([
            'status' => true,
            'message' => 'Two factor verification otp has been sent to your email',

        ]);
    }


    public function generateOtp($token)
    {

        $code = rand(1000, 9999);
        UserCode::updateOrCreate(
            ['user_id' => $this->id],
            ['code' => $code]
        );

        $details  = [
            'title' => 'Two Factor authentication for MEtutors',
            'message' => 'Your one time password for MEtutor',
            'code' => $code,
            'ignoremessage' => "If you didnt submit this request, please ignore it.",
        ];
        \Mail::to($this->email)->send(new SendMailOtp($details));




        return response()->json([
            'status' => true,
            'message' => 'Two factor verification otp has been sent to your email!',

            'token' => $token
        ]);

        //   return response()->json([
        //         'status'=>true,
        //         'message'=>'Two factor verification otp has been sent to your email' ,

        //         ]);

    }

    public function isAdmin()
    {
        return $this->role->is_admin;
    }

    public function isUser()
    {
        return $this->role_name === Role::$user;
    }

    public function isTeacher()
    {
        return $this->role_name === Role::$teacher;
    }

    public function isOrganization()
    {
        return $this->role_name === Role::$organization;
    }

    public function hasPermission($section_name)
    {
        if (self::isAdmin()) {
            if (!isset($this->permissions)) {
                $sections_id = Permission::where('role_id', '=', $this->role_id)->where('allow', true)->pluck('section_id')->toArray();
                $this->permissions = Section::whereIn('id', $sections_id)->pluck('name')->toArray();
            }
            return in_array($section_name, $this->permissions);
        }
        return false;
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    public function getAvatar()
    {
        if (!empty($this->avatar)) {
            $imgUrl = '/store/' . $this->id . '/' . $this->avatar;
        } else {
            $imgUrl = getPageBackgroundSettings('user_avatar');
        }

        return $imgUrl;
    }

    public function getCover()
    {
        if (!empty($this->cover_img)) {
            $path = str_replace('/storage', '', $this->cover_img);

            $imgUrl = url($path);
        } else {
            $imgUrl = getPageBackgroundSettings('user_cover');
        }

        return $imgUrl;
    }

    public function getProfileUrl()
    {
        return '/users/' . $this->id . '/profile';
    }

    public static function generatePassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }




    public function followers()
    {
        return Follow::where('user_id', $this->id)->where('status', Follow::$accepted)->get();
    }

    public function following()
    {
        return Follow::where('follower', $this->id)->where('status', Follow::$accepted)->get();
    }




    public function userMetas()
    {
        return $this->hasMany('App\Models\UserMeta', 'user_id', 'id');
    }
    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket', 'user_id', 'id');
    }

    public function teacherInterviewRequests()
    {
        return $this->hasMany('App\TeacherInterviewRequest', 'user_id', 'id');
    }

    public function teacherSpecifications()
    {
        return $this->hasOne('App\TeachingSpecification');
    }

    public function teacherQualifications()
    {
        return $this->hasOne('App\TeachingQualification');
    }

    public function teacherDocuments()
    {
        return $this->hasMany('App\TeacherDocument');
    }

    public function spokenLanguages()
    {
        return $this->hasMany(SpokenLanguage::class);
    }

    public function carts()
    {
        return $this->hasMany('App\Models\Cart', 'creator_id', 'id');
    }

    public function userGroup()
    {
        return $this->belongsTo('App\Models\GroupUser', 'id', 'user_id');
    }

    public function certificates()
    {
        return $this->hasMany('App\Models\Certificate', 'student_id', 'id');
    }

    public function customBadges()
    {
        return $this->hasMany('App\Models\UserBadge', 'user_id', 'id');
    }









    public function feedbacks()
    {
        return $this->hasMany(UserFeedback::class, 'reciever_id', 'id');
    }




    public function getBadges($customs = true, $getNext = false)
    {
        return Badge::getUserBadges($this, $customs, $getNext);
    }

    public function courses()
    {
        return $this->hasMany(ClassRoom::class, 'student_id', 'id');
    }
}
