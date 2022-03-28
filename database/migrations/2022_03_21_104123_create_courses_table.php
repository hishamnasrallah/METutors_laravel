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
            $table->integer('language_id');
            $table->string('book_type');
            $table->string('book_info');
            $table->string('book_name');
            $table->string('book_edition');
            $table->string('book_author');
            $table->float('total_price');
            $table->integer('country_id');
            $table->integer('program_id');
            $table->integer('total_hours');
            $table->integer('total_classes');
            $table->string('weekdays');
            $table->string('files');
            $table->string('status');
            $table->text('start_date');
            $table->text('end_date');
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
