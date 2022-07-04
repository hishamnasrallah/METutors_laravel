<?php

namespace App\Console\Commands;

use App\Events\TeacherReminderEvent;
use App\Jobs\TeacherReminderJob;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CourseReminderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $courses = Course::where('status', 'pending')->get();
        $admin_message = "Teacher has not responded yet";
        $teacher_message = "You Have not responded to course yet";
        $admin = User::where('role_name', 'admin')->first();

        foreach ($courses as  $course) {
            $teacher = User::findOrFail($course->teacher_id);
            $student =  User::findOrFail($course->student_id);
            // if its first warning
            if ($course->warning_count == 0) {
                $hours = $course->created_at->addHours(8);
                if (Carbon::now() >= $hours) {
                    event(new TeacherReminderEvent($course,  $teacher->id, $teacher_message, $teacher));
                    event(new TeacherReminderEvent($course,  $admin->id, $admin_message, $admin));
                    dispatch(new TeacherReminderJob($course,  $teacher->id, $teacher_message, $teacher));
                    dispatch(new TeacherReminderJob($course,  $admin->id, $admin_message, $admin));
                }
            }
            // if its second warning
            if ($course->warning_count == 1) {
                $hours = $course->created_at->addHours(16);
                if (Carbon::now() >= $hours) {
                    event(new TeacherReminderEvent($course,  $teacher->id, $teacher_message, $teacher));
                    event(new TeacherReminderEvent($course,  $admin->id, $admin_message, $admin));
                    dispatch(new TeacherReminderJob($course,  $teacher->id, $teacher_message, $teacher));
                    dispatch(new TeacherReminderJob($course,  $admin->id, $admin_message, $admin));
                }
            }
            // if its third warning
            if ($course->warning_count == 2) {
                $hours = $course->created_at->addHours(20);
                if (Carbon::now() >= $hours) {
                    event(new TeacherReminderEvent($course,  $teacher->id, $teacher_message, $teacher));
                    event(new TeacherReminderEvent($course,  $admin->id, $admin_message, $admin));
                    event(new TeacherReminderEvent($course,  $student->id, "Please Select another Tutor", $student));
                    dispatch(new TeacherReminderJob($course,  $teacher->id, $teacher_message, $teacher));
                    dispatch(new TeacherReminderJob($course,  $admin->id, $admin_message, $admin));
                    dispatch(new TeacherReminderJob($course,  $student->id, "Please Select another Tutor", $student));
                    $course->status = "not_responded";
                    $course->update();
                }
            }
        }
    }
}
