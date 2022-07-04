<?php

namespace App\Console\Commands;

use App\Events\PreClassEvent;
use App\Jobs\PreClassJob;
use App\Models\AcademicClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PreClassCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pre_class:cron';

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
        $classes = AcademicClass::where('start_date', Carbon::today()->format('Y-m-d'))->where('notification_counter', 0)->get();
        foreach ($classes as $class) {
            $attendees =  $class->attendees->pluck('student_id');
            $teacher =  $class->attendees->pluck('teacher_id');
            //Pre class alerts for students and teacher
            $remaining_hour = Carbon::parse($class->start_time)->subHour(1);
            if (Carbon::now() >= $remaining_hour) {
                foreach ($attendees as  $attendee) {
                    $attendee = User::findOrFail($attendee);
                    $custom_message = "Your class is about to start in 1 hour";
                    event(new PreClassEvent($attendee->id, $attendee, $custom_message, $class));
                    dispatch(new PreClassJob($attendee->id, $attendee, $custom_message, $class));
                    event(new PreClassEvent($teacher->id, $teacher, $custom_message, $class));
                    dispatch(new PreClassJob($teacher->id, $teacher, $custom_message, $class));
                }
                //marked ar notification sent
                $class->notification_counter = 1;
                $class->update();
            }
        }
    }
}
