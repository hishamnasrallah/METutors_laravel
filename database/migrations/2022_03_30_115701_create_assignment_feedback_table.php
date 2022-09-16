<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_feedback', function (Blueprint $table) {
            $table->id();
            $table->integer('user_assignment_id')->nullable();
            $table->integer('assignment_id');
            $table->integer('student_id');
            $table->longText('review')->nullable();
            $table->integer('rating')->nullable();
            $table->string('file')->nullable();
            $table->longText('description')->nullable();
            $table->string('status');
            $table->integer('feedback_by');
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
        Schema::dropIfExists('assignment_feedback');
    }
}
