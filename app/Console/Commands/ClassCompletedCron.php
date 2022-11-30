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
        $today = Carbon::now()->toISOString();
        $today_date = Carbon::parse($today)->format('Y-m-d');

        $classes = AcademicClass::whereDate('start_date', "<=", $today_date)
            ->where('status', '!=', 'completed')
            ->where('teacher_id', '!=', null)
            ->get();

        if (count($classes) > 0) {
            foreach ($classes as $class) {
                if ($class->teacher_attendence != null && $class->student_attendence != null) {
                    //********************* Class completion scnerio *********************
                    ### if current time is greater than class end time and teacher and student are present
                    if ($class->end_time <= $today && $class->teacher_attendence->status == 'present' && $class->student_attendence->status == 'present') { // $class->teacher_attendence != null && $class->student_attendence != null
                        $class->status = "completed";
                        $class->update();
                    }
                    ### if current time is greater than class end time and teacher is present and student is absent
                    if ($class->end_time <= $today && $class->teacher_attendence->status == 'present' && $class->student_attendence->status == 'absent') { // $class->student_attendence == null && $class->teacher_attendence != null
                        $class->status = "student_absent";
                        $class->update();
                    }
                    ### if current time is greater than class end time and teacher is absent and student is present
                    if ($class->end_time <= $today && $class->teacher_attendence->status == 'absent' && $class->student_attendence->status == 'present') { // $class->student_attendence != null && $class->teacher_attendence == null
                        $class->status = "teacher_absent";
                        $class->update();
                    }
                    ### if current time is greater than class end time and teacher is absent and student is absent
                    if ($class->end_time <= $today && $class->teacher_attendence->status == 'absent' && $class->student_attendence->status == 'absent') { //$class->student_attendence == null && $class->teacher_attendence == null
                        $class->status = "student_absent";
                        $class->update();
                    }
                    //********************* Class completion scnerio ends *********************
                }
            }
        }
    }
}
