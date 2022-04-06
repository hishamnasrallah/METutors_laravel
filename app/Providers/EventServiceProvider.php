<?php

namespace App\Providers;

use App\Events\AcceptCourse;
use App\Events\CancelCourse;
use App\Events\CourseBooked;
use App\Events\NewCourse;
use App\Events\RejectCourse;
use App\Events\StudentAcceptCourse;
use App\Events\StudentRegister;
use App\Events\UpdateProfile;
use App\Listeners\AcceptCourseListener;
use App\Listeners\CancelCourseListener;
use App\Listeners\CourseBookedListener;
use App\Listeners\NewCourseListener;
use App\Listeners\RejectCourseListener;
use App\Listeners\StudentAcceptCourseListener;
use App\Listeners\StudentEventListner;
use App\Listeners\UpdateProfileListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        CourseBooked::class => [
            CourseBookedListener::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        StudentRegister::class => [
            StudentEventListner::class,
        ],
        NewCourse::class => [
            NewCourseListener::class,
        ],
        AcceptCourse::class => [
            AcceptCourseListener::class,
        ],
        StudentAcceptCourse::class => [
            StudentAcceptCourseListener::class,
        ],
        RejectCourse::class => [
            RejectCourseListener::class,
        ],
        CancelCourse::class => [
            CancelCourseListener::class,
        ],
        UpdateProfile::class => [
            UpdateProfileListener::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
