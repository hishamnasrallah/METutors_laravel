<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_classes', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('course_id');
            $table->integer('class_id')->nullable();
            $table->integer('teacher_id');
            $table->integer('student_id');
            $table->string('lesson_name')->nullable();
            $table->string('start_date');
            $table->string('end_date');
            $table->string('class_type');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('day');
            $table->string('status')->nullable();
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
        Schema::dropIfExists('academic_classes');
    }
}
