<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherInterviewRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_interview_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');	
            $table->string('date_for_interview');
            $table->string('time_for_interview');
            $table->longText('additional_comments');
            $table->string('status');
            $table->longText('admin_comments');
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
        Schema::dropIfExists('teacher_interview_requests');
    }
}
