<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRescheduleClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reschedule_classes', function (Blueprint $table) {
            $table->id();
            $table->string('rescheduled_by');
            $table->integer('course_id');
            $table->integer('academic_class_id');
            $table->text('start_time');
            $table->text('end_time');
            $table->text('start_date');
            $table->string('day');
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
        Schema::dropIfExists('reschedule_classes');
    }
}
