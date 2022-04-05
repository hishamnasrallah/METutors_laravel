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
            $table->string('assignment_id');
            $table->integer('student_id');
            $table->longText('review');
            $table->integer('rating');
            $table->string('file');
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
