<?php

namespace App\Console\Commands;

use App\Events\AbsentClassEvent;
use App\Events\ClassStartedEvent;
use App\Events\PreClassEvent;
use App\Jobs\AbsentClassJob;
use App\Jobs\ClassStartedJob;
use App\Jobs\PreClassJob;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClassCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'class:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will change the status of class if class time has reached and give notifications to users that class has been started';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $classes = AcademicClass::where('start_date', Carbon::today()->format('Y-m-d'))->get();
        foreach ($classes as $class) {
            //Class completion scnerio
            if (Carbon::parse($class->end_time) >= Carbon::now() && count($class->teacher_attendence) > 0  && count($class->student_attendence) > 0) {
                $class->status = "completed";
                $class->update();
            }
            if (Carbon::parse($class->end_time) >= Carbon::now() && count($class->student_attendence) == 0 && count($class->teacher_attendence) > 0) {
                $class->status = "completed";
                $class->update();
            }
            if (Carbon::parse($class->end_time) >= Carbon::now() && count($class->student_attendence) > 0 && count($class->teacher_attendence) == 0) {
                $class->status = "teacher_absent";
                $class->update();
            }
            //Class completion scnerio ends 

            $attendees =  $class->attendees->pluck('student_id');
            $teacher =  $class->attendees->pluck('teacher_id');

            //sending notifications if students are not in the class
            #------students
            foreach ($attendees as  $attendee) {
                if (Carbon::now() >= Carbon::parse($class->start_time)) {
                    if (Attendance::where('user_id', $attendee)->where('academic_class_id', $class->id)->count() == 0) {
                        $attendee = User::findOrFail($attendee);
                        $custom_message = "Class has been started! Please join";
                        event(new ClassStartedEvent($attendee->id, $attendee, $custom_message, $class));
                        dispatch(new ClassStartedJob($attendee->id, $attendee, $custom_message, $class));
                    }
                }
                //marking as absent if after class time student has not joined
                if (Carbon::now() > Carbon::parse($class->end_time)) {
                    $attendance = Attendance::where('user_id', $attendee)->where('academic_class_id', $class->id)->first();
                    $custom_message = "You have been marked as absent in class";
                    if ($attendance == null) {
                        $userAttendence = new Attendance();
                        $userAttendence->academic_class_id = $class->id;
                        $userAttendence->course_id = $class->course_id;
                        $userAttendence->user_id = $attendee;
                        $userAttendence->status = 'absent';
                        $userAttendence->role_name = 'student';
                        $userAttendence->save();
                        // sending notidfications if user is absent
                        event(new AbsentClassEvent($attendee->id, $attendee, $custom_message, $class));
                        dispatch(new AbsentClassJob($attendee->id, $attendee, $custom_message, $class));
                    }
                }
            }

            #----teacher is not in class
            $teacher = User::findOrFail($teacher);
            if (Attendance::where('user_id', $teacher)->where('academic_class_id', $class->id)->count() == 0) {
                $custom_message = "Class has been started! Please join";
                event(new ClassStartedEvent($teacher->id, $teacher, $custom_message, $class));
                dispatch(new ClassStartedJob($teacher->id, $teacher, $custom_message, $class));
            }

            //-----Marking teacher as absent if he did not attend the class
            if (Carbon::parse($class->end_time) > Carbon::now()) {
                $attendance = Attendance::where('user_id', $teacher)->where('academic_class_id', $class->id)->first();
                if ($attendance == null) {
                    $userAttendence = new Attendance();
                    $userAttendence->academic_class_id = $class->id;
                    $userAttendence->course_id = $class->course_id;
                    $userAttendence->user_id = $teacher->id;
                    $userAttendence->status = 'absent';
                    $userAttendence->role_name = 'teacher';
                    $userAttendence->save();

                    //Absent notifications for teacher
                    event(new AbsentClassEvent($teacher->id, $teacher, $custom_message, $class));
                    dispatch(new AbsentClassJob($teacher->id, $teacher, $custom_message, $class));
                }
            }
        }
    }
}
