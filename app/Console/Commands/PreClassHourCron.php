<?php

namespace App\Console\Commands;

use App\Events\PreClassEvent;
use App\Events\PreClassHourEvent;
use App\Jobs\PreClassHourJob;
use App\Jobs\PreClassJob;
use App\Models\AcademicClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PreClassHourCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pre_class_hour:cron';

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
        $today = Carbon::now()->toISOString();
        $today_date = Carbon::parse($today)->format('Y-m-d');

        $classes = AcademicClass::with('course', 'teacher', 'student')->whereDate('start_date', $today_date)
            ->where('notification_counter', 0)
            ->get();

        foreach ($classes as $class) {
            $attendees =  $class->attendees->pluck('student_id');
            $teacher =  User::findOrFail($class->teacher_id);
            //Pre class alerts for students and teacher
            $remaining_hour = Carbon::parse($class->start_time)->subHour(1)->toISOString();
            if (Carbon::now()->toISOString() >= $remaining_hour) {
                foreach ($attendees as  $attendee) {
                    $Attendee = User::findOrFail($attendee);
                    $custom_message = "Your class is about to start in 1 hour";
                    event(new PreClassHourEvent($Attendee->id, $Attendee, $custom_message, $class));
                    dispatch(new PreClassHourJob($Attendee->id, $Attendee, $custom_message, $class));
                }
                event(new PreClassHourEvent($teacher->id, $teacher, $custom_message, $class));
                dispatch(new PreClassHourJob($teacher->id, $teacher, $custom_message, $class));
                
                //marked ar notification sent
                $class->notification_counter = 1;
                $class->update();
            }
        }
    }
}
