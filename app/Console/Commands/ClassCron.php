<?php

namespace App\Console\Commands;

use App\Events\AbsentClassEvent;
use App\Events\ClassStartedEvent;
use App\Events\PreClassEvent;
use App\Events\StudentAbsentClassEvent;
use App\Events\TeacherAbsentClassEvent;
use App\Jobs\AbsentClassJob;
use App\Jobs\ClassStartedJob;
use App\Jobs\PreClassJob;
use App\Jobs\StudentAbsentClassJob;
use App\Jobs\TeacherAbsentClassJob;
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
        $today =  Carbon::now()->toISOString();
        $today_date =  Carbon::parse($today)->format('Y-m-d');
        $today_time =  Carbon::parse($today)->format('H:i:s');

        $classes = AcademicClass::with('student', 'teacher', 'course.order')
            ->whereDate('start_date', "<=", $today_date)
            ->whereNotIn('status', ['completed', 'teacher_absent', 'student_absent'])
            ->where('teacher_id', "!=", null)
            ->get();

        foreach ($classes as $class) {

            $attendees =  $class->attendees->pluck('student_id');
            $teacher = User::findOrFail($class->teacher->id);

            //sending notifications if students are not in the class
            #------students
            foreach ($attendees as  $attendee) {
                if ($today >= $class->start_time) {
                    if (Attendance::where('user_id', $attendee)->where('academic_class_id', $class->id)->count() == 0) {
                        $attendee = User::findOrFail($attendee);
                        $custom_message = "Class has been started! Please join";
                        event(new ClassStartedEvent($attendee->id, $attendee, $custom_message, $class));
                        dispatch(new ClassStartedJob($attendee->id, $attendee, $custom_message, $class));
                    }
                }

                #----teacher is not in class
                if ($class->teacher->id != '') {

                    $teacher_attendence = Attendance::where('user_id', $teacher)->where('academic_class_id', $class->id)->count();
                    if ($teacher_attendence == 0) {
                        $custom_message = "Class has been started! Please join";
                        event(new ClassStartedEvent($teacher->id, $teacher, $custom_message, $class));
                        dispatch(new ClassStartedJob($teacher->id, $teacher, $custom_message, $class));
                    }
                }

                //marking as absent if after class time ends and student has not joined
                if ($today > $class->end_time) {
                    $attendance = Attendance::where('user_id', $attendee)
                        ->where('academic_class_id', $class->id)
                        ->first();

                    $custom_message = "You have been marked as absent in class";
                    if ($attendance == null) {
                        $userAttendence = new Attendance();
                        $userAttendence->academic_class_id = $class->id;
                        $userAttendence->course_id = $class->course_id;
                        $userAttendence->user_id = $attendee;
                        $userAttendence->status = 'absent';
                        $userAttendence->role_name = 'student';
                        $userAttendence->save();

                        $attendee = User::findOrFail($attendee);
                        $teacher = User::findOrFail($class->teacher->id);
                        $admin = User::where('role_name', 'admin')->first();
                        // sending notidfications if user is absent
                        event(new StudentAbsentClassEvent($attendee->id, $attendee, $custom_message, $class));
                        event(new StudentAbsentClassEvent($teacher->id, $teacher, $custom_message, $class));
                        event(new StudentAbsentClassEvent($admin->id, $admin, $custom_message, $class));
                        dispatch(new StudentAbsentClassJob($attendee->id, $attendee, $custom_message, $class));
                        dispatch(new StudentAbsentClassJob($teacher->id, $teacher, $custom_message, $class));
                        dispatch(new StudentAbsentClassJob($admin->id, $admin, $custom_message, $class));
                    }
                }
            }




            //-----Marking teacher as absent if he did not attend the class
            if ($class->end_time > $today) {
                $attendance = Attendance::where('user_id', $teacher)
                    ->where('academic_class_id', $class->id)
                    ->first();


                if ($attendance == null) {
                    $userAttendence = new Attendance();
                    $userAttendence->academic_class_id = $class->id;
                    $userAttendence->course_id = $class->course_id;
                    $userAttendence->user_id = $teacher->id;
                    $userAttendence->status = 'absent';
                    $userAttendence->role_name = 'teacher';
                    $userAttendence->save();

                    $custom_message = "Teacher is absent";

                    $admin = User::where('role_name', 'admin')->first();
                    $student = User::where('id', $class->student_id)->first();
                    //Absent notifications for teacher
                    event(new TeacherAbsentClassEvent($teacher->id, $teacher, $custom_message, $class));
                    dispatch(new TeacherAbsentClassJob($teacher->id, $teacher, $custom_message, $class));
                    event(new TeacherAbsentClassEvent($admin->id, $admin, $custom_message, $class));
                    dispatch(new TeacherAbsentClassJob($admin->id, $admin, $custom_message, $class));
                    event(new TeacherAbsentClassEvent($student->id, $student, $custom_message, $class));
                    dispatch(new TeacherAbsentClassJob($student->id, $student, $custom_message, $class));
                }
            }
        }
    }
}
