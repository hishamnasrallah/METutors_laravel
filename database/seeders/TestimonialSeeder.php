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
                'name' => 'This site is intuative and easy to use',
                'role_name' => 'student',
                'ar_name' => 'هذا الموقع بديهي وسهل الاستخدام',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'This site addresses my educational needs.',
                'role_name' => 'student',
                'ar_name' => 'هذا الموقع يلبي احتياجاتي التعليمية.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Flexibility of choosing my courses.',
                'role_name' => 'student',
                'ar_name' => 'المرونة في اختيار الدورات الخاصة بي',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Flexibility of creating my class schedule.',
                'role_name' => 'student',
                'ar_name' => 'المرونة في إنشاء جدول الحصص الخاص بي.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'pricing competitiveness.',
                'role_name' => 'student',
                'ar_name' => 'تنافسية التسعير.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Support team responsiveness.',
                'role_name' => 'student',
                'ar_name' => 'دعم استجابة الفريق.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'MEtutors platform is intuitive and easy to use',
                'role_name' => 'teacher',
                'ar_name' => 'منصة MEtutors بديهية وسهلة الاستخدام',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'MEtutors meets my teaching requirements',
                'role_name' => 'teacher',
                'ar_name' => 'MEtutors يلبي متطلبات التدريس الخاصة بي',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Flexibility of managing my students and classes',
                'role_name' => 'teacher',
                'ar_name' => 'المرونة في إدارة طلابي وفصولي',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Support team is responsible and responsive',
                'role_name' => 'teacher',
                'ar_name' => 'فريق الدعم مسؤول وسريع الاستجابة',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'MEtutors pays fairly compared to industry standards',
                'role_name' => 'teacher',
                'ar_name' => 'يدفع MEtutors بشكل عادل مقارنة بمعايير الصناعة',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
