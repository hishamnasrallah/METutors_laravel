<?php

namespace App\Console\Commands;

use App\Events\PreClassDayEvent;
use App\Events\PreClassHourEvent;
use App\Jobs\PreClassDayJob;
use App\Jobs\PreClassHourJob;
use App\Models\AcademicClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PreClassDayCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pre_class_day:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre Class Aleerts to users';

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
        $today = Carbon::now()->addDay(1)->toISOString();
        $today_date = Carbon::parse($today)->format('Y-m-d');

        $classes = AcademicClass::with('course', 'teacher', 'student')->whereDate('start_date', $today_date)
            ->get();

        foreach ($classes as $class) {
            $attendees =  $class->attendees->pluck('student_id');
            $teacher =  User::findOrFail($class->teacher_id);
            //Pre class alerts for students 
            foreach ($attendees as  $attendee) {
                $Attendee = User::findOrFail($attendee);
                $custom_message = "Your class is about to start in 1 hour";
                event(new PreClassDayEvent($Attendee->id, $Attendee, $custom_message, $class));
                dispatch(new PreClassDayJob($Attendee->id, $Attendee, $custom_message, $class));
            }
            //Pre class alerts for teacher
            event(new PreClassDayEvent($teacher->id, $teacher, $custom_message, $class));
            dispatch(new PreClassDayJob($teacher->id, $teacher, $custom_message, $class));
        }
    }
}
