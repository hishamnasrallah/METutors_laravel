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
            $table->integer('course_id')->nullable();
            $table->integer('topic_id')->nullable();
            $table->integer('resource_id')->nullable();
            $table->integer('class_id')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->integer('student_id');
            $table->string('lesson_name')->nullable();
            $table->text('start_date');
            $table->text('end_date');
            $table->float('duration');
            $table->string('class_type')->nullable();
            $table->text('start_time');
            $table->text('end_time');
            $table->string('day');
            $table->string('status');
            $table->integer('resource_id')->nullable();
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
