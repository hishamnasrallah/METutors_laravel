<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('testimonials')->insert([
            [
                'name' => 'SITE_INTUITIVE_EASY_USE',
                'role_name' => 'student',
                // 'ar_name' => 'هذا الموقع بديهي وسهل الاستخدام',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'SITE_ADDRESSES_EDUCATIONAL_NEEDS',
                'role_name' => 'student',
                // 'ar_name' => 'هذا الموقع يلبي احتياجاتي التعليمية.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'FLEXIBILITY_CHOOSING_COURSES',
                'role_name' => 'student',
                // 'ar_name' => 'المرونة في اختيار الدورات الخاصة بي',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'FLEXIBILITY_CREATING_CLASS_SCHEDULE',
                'role_name' => 'student',
                // 'ar_name' => 'المرونة في إنشاء جدول الحصص الخاص بي.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'PRICING_COMPETITIVENESS',
                'role_name' => 'student',
                // 'ar_name' => 'تنافسية التسعير.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'SUPPORT_TEAM_RESPONSIVENESS',
                'role_name' => 'student',
                // 'ar_name' => 'دعم استجابة الفريق.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'METUTORS_PLATFORM_INTUITIVE_EASY_USE',
                'role_name' => 'teacher',
                // 'ar_name' => 'منصة MEtutors بديهية وسهلة الاستخدام',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'METUTORS_MEETS_TEACHING_REQUIREMENTS',
                'role_name' => 'teacher',
                // 'ar_name' => 'MEtutors يلبي متطلبات التدريس الخاصة بي',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'FLEXIBILITY_MANAGING_STUDENTS_CLASSES',
                'role_name' => 'teacher',
                // 'ar_name' => 'المرونة في إدارة طلابي وفصولي',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'SUPPORT_TEAM_RESPONSIBLE_RESPONSIVE',
                'role_name' => 'teacher',
                // 'ar_name' => 'فريق الدعم مسؤول وسريع الاستجابة',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'METUTORS_PAYS_FAIRLY_INDUSTRY_STANDARDS',
                'role_name' => 'teacher',
                // 'ar_name' => 'يدفع MEtutors بشكل عادل مقارنة بمعايير الصناعة',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
