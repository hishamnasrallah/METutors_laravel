<?php

namespace App\Console\Commands;

use App\Models\AcademicClass;
use App\Models\TeacherPayment;
use Illuminate\Console\Command;
use Str;

class PaymentTransferCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command created pending payment records in database';

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
        $teachers = AcademicClass::where('payment_status','pending')
        ->where('status','completed')
        ->pluck('teacher_id')
        ->unique();

        foreach($teachers as $teacher){
             $classes = AcademicClass::where('payment_status','pending')
            ->where('status','completed')
            ->where('teacher_id', $teacher)
            ->get();

            $transaction_id =  Str::random(20);

            foreach($classes as $class){

                $teacher_payment = new TeacherPayment();
                $teacher_payment->course_id = $class->course_id;
                $teacher_payment->academic_class_id = $class->id;
                $teacher_payment->transaction_id = $transaction_id;
                $teacher_payment->user_id = $class->teacher_id;
                $teacher_payment->save();
            }
        }
    }
}
