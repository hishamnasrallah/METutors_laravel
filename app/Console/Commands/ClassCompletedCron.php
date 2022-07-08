<?php

namespace App\Console\Commands;

use App\Models\AcademicClass;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClassCompletedCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'class:completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Class Completion Scnerio';

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
        $classes = AcademicClass::where('start_date', "<=", Carbon::today()->format('Y-m-d'))
            ->where('status', '!=', 'completed')
            ->get();

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
        }
    }
}
