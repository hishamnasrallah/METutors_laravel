<?php

namespace App\Console\Commands;

use App\Events\AssignmentDeadlineEvent;
use App\Jobs\AssignmentDeadlineJob;
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
    protected $signature = 'assignment:deadline_alert';

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
        //if assignment is expired tomorrow
        $tomorrow = Carbon::now()->addDay(1)->toISOString();
        $tomorrow_date = Carbon::parse($tomorrow)->format('Y-m-d');

        $assignments = Assignment::with('course')->whereDate('deadline', $tomorrow_date)->get();

        if (count($assignments) > 0) {
            foreach ($assignments as $assignment) {
                $course = Course::with('teacher','student')->findOrFail($assignment->course_id);
                $student = User::findOrFail($course->student_id);
                $teacher = User::findOrFail($course->teacher_id);
                $custom_message = "First reminder - Course assignment due tomorrow";
                $reminder = 1;

                //emails and notifications
                event(new AssignmentDeadlineEvent($course, $student, $custom_message, $assignment, $reminder));
                event(new AssignmentDeadlineEvent($course, $teacher, $custom_message, $assignment, $reminder));
                dispatch(new AssignmentDeadlineJob($course, $student, $custom_message, $assignment, $reminder));
                dispatch(new AssignmentDeadlineJob($course, $teacher, $custom_message, $assignment, $reminder));
            }
        }

        //if assignment is expiring today
        $today = Carbon::now()->toISOString();
        $today_date = Carbon::parse($today)->format('Y-m-d');

        $assignments = Assignment::with('course')->whereDate('deadline', $today_date)->get();

        if (count($assignments) > 0) {
            foreach ($assignments as $assignment) {
                $course = Course::with('teacher','student')->findOrFail($assignment->course_id);
                $student = User::findOrFail($course->student_id);
                $teacher = User::findOrFail($course->teacher_id);
                $custom_message = "Second reminder - Course assignment due today";
                $reminder = 2;

                //emails and notifications
                event(new AssignmentDeadlineEvent($course, $student, $custom_message, $assignment, $reminder));
                event(new AssignmentDeadlineEvent($course, $teacher, $custom_message, $assignment, $reminder));
                dispatch(new AssignmentDeadlineJob($course, $student, $custom_message, $assignment, $reminder));
                dispatch(new AssignmentDeadlineJob($course, $teacher, $custom_message, $assignment, $reminder));
            }
        }
    }
}
