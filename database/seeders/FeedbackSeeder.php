<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('feedback')->insert([
            [
                'name' => 'EXPERT_IN_SUBJECT',
                'role_name' => 'student',
                // 'ar_name' => 'خبير في الموضوع',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'PRESENT_COMPLEX_TOPICS_CLEARLY',
                'role_name' => 'student',
                // 'ar_name' => 'عرض المواضيع المعقدة بوضوح وسهولة',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'SKILLFULL_ENGAGING_STUDENTS',
                'role_name' => 'student',
                // 'ar_name' => 'Skillfull في إشراك الطلاب',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'ALWAYS_ON_TIME',
                'role_name' => 'student',
                // 'ar_name' => 'منتظم دائما فى الميعاد ملتزم دائما بدقة المواعيد دائما فى الوقت المحدد.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'FAST_LEARNER',
                'role_name' => 'teacher',
                // 'ar_name' => 'متعلم سريع',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'FOCUS_CLASS_PAYING_ATTENTION',
                'role_name' => 'teacher',
                // 'ar_name' => 'يركز في الفصل والانتباه',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'ATTEND_CLASS_ONTIME',
                'role_name' => 'teacher',
                // 'ar_name' => 'حضور الفصل في الوقت المحدد',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'COMPLETE_HOMEWORK_ONTIME',
                'role_name' => 'teacher',
                // 'ar_name' => 'أكمل الواجب المنزلي في الوقت المحدد',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'ACTIVE_PARTICIPATION_CLASS',
                'role_name' => 'teacher',
                // 'ar_name' => 'المشاركة الفعالة في الفصل',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
