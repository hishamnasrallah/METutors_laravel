<?php

namespace App\Console\Commands;

use App\Events\CourseCompletedEvent;
use App\Jobs\CourseCompletedJob;
use App\Models\Course;
use App\Models\User;
use Illuminate\Console\Command;

class CourseCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'If all the Course classses are completed, then we can make the status of course Completed';

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
        $courses = Course::with('classes')
        ->whereHas('classes', function ($q) {
            $q->where('status', 'completed');
        })
            ->where('status', '!=', "completed")->get();

        $admin  = User::where('role_name','admin')->first();
        foreach ($courses as $course) {
            $count = $course->classes->where('status', 'completed')->count();
            if ($count == $course->total_classes) {
                $course->status = 'completed';
                $course->save();
                event(new CourseCompletedEvent($course->teacher,'course completed successfully',$course));
                event(new CourseCompletedEvent($course->student,'course completed successfully',$course));
                event(new CourseCompletedEvent($admin,'course completed successfully',$course));
                dispatch(new CourseCompletedJob($course->teacher,'course completed successfully',$course));
                dispatch(new CourseCompletedJob($course->student,'course completed successfully',$course));
                dispatch(new CourseCompletedJob($admin,'course completed successfully',$course));
            }
            
        }
    }
}
