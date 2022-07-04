<?php

namespace App\Console\Commands;

use App\Events\AssignmentDeadlineEvent;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AssignmentDeadlineCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignment:deadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This cron runs every night at 12:01 and checks if any assignment has reached its deadline';

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
        $assignments = Assignment::where('deadline', Carbon::tomorrow()->format('Y-m-d'))->get();
        foreach ($assignments as $assignment) {
            $course = Course::findOrFail($assignment->course_id);
            $student = User::findOrFail($course->student_id);
            $teacher = User::findOrFail($course->teacher_id);
            $custom_message = "Today is the last date of assignment! please Submitt if not submitted yet";

            //emails and notifications
            event(new AssignmentDeadlineEvent($student->id, $student, $custom_message, $assignment));
            event(new AssignmentDeadlineEvent($teacher->id, $teacher, $custom_message, $assignment));
            dispatch(new AssignmentDeadlineCron($student->id, $student, $custom_message, $assignment));
            dispatch(new AssignmentDeadlineCron($teacher->id, $teacher, $custom_message, $assignment));
        }
    }
}
