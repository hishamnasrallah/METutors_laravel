<?php

namespace App\Console\Commands;

use App\Models\Course;
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
        $courses = Course::with('classes')->whereHas('classes', function ($q) {
            $q->where('status', 'completed');
        })
            ->where('status', '!=', "completed")->get();

        foreach ($courses as $course) {
            $count = $course->classes->where('status', 'completed')->count();
            if ($count == $course->total_classes) {
                $course->status = 'completed';
                $course->update();
            }
        }
    }
}
