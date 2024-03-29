<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachingQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teaching_qualifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name_of_university');
            $table->string('degree_level');
            $table->string('degree_field');
            $table->string('computer_skills');
            $table->string('teaching_experience');
            $table->string('teaching_experience_online')->nullable();
            $table->string('video')->nullable();
            $table->string('current_employer')->nullable();
            $table->string('current_title')->nullable();
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
        Schema::dropIfExists('teaching_qualifications');
    }
}
