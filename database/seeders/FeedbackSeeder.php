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
                'name' => 'Expert in the subject',
                'role_name' => 'student',
                'ar_name' => 'خبير في الموضوع',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Present Complex Topics clearly and easily',
                'role_name' => 'student',
                'ar_name' => 'عرض المواضيع المعقدة بوضوح وسهولة',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Skillfull in engaging students',
                'role_name' => 'student',
                'ar_name' => 'Skillfull في إشراك الطلاب',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Always on time',
                'role_name' => 'student',
                'ar_name' => 'منتظم دائما فى الميعاد ملتزم دائما بدقة المواعيد دائما فى الوقت المحدد.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Fast learner',
                'role_name' => 'teacher',
                'ar_name' => 'متعلم سريع',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Focuses in class and paying attention',
                'role_name' => 'teacher',
                'ar_name' => 'يركز في الفصل والانتباه',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Attend class on-time',
                'role_name' => 'teacher',
                'ar_name' => 'حضور الفصل في الوقت المحدد',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Complete homework on time',
                'role_name' => 'teacher',
                'ar_name' => 'أكمل الواجب المنزلي في الوقت المحدد',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Active participation in class',
                'role_name' => 'teacher',
                'ar_name' => 'المشاركة الفعالة في الفصل',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
