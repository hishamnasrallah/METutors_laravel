<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code');
            $table->string('course_name');
            $table->string('course_level');
            $table->integer('field_of_study');
            $table->integer('subject_id');
            $table->integer('teacher_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->text('start_date')->nullable();
            $table->text('end_date')->nullable();
            $table->integer('language_id');
            $table->string('book_type')->nullable();
            $table->string('book_info');
            $table->string('book_name')->nullable();
            $table->string('book_edition')->nullable();
            $table->string('book_author')->nullable();
            $table->float('total_price');
            $table->integer('country_id')->nullable();
            $table->integer('program_id');
            $table->string('total_hours');
            $table->string('classroom_type')->nullable();
            $table->integer('total_classes');
            $table->string('weekdays')->nullable();
            $table->string('files')->nullable();
            $table->string('status')->nullable();
            $table->text('start_time')->nullable();
            $table->text('end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
