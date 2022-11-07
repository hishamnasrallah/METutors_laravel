<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AssignmentCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignment:completed';

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
        $today = Carbon::today()->toISOString();
        $today_date = Carbon::parse($today)->format('Y-m-d');

        $assignments = Assignment::whereDate('deadline', '<=', $today_date)
            ->where('status', "!=", 'completed')
            ->get();

        foreach ($assignments as $assignment) {
            if ($assignment->status != "completed") {
                $assignment->status = "completed";
                $assignment->update();
            }
        }
    }
}
