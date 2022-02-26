<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachingSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teaching_specifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('level_of_education');
            $table->string('field_of_study');
            $table->string('subject');
            $table->string('type_of_tutoring');
            $table->string('expected_salary_per_hour');
            $table->string('availability_start_date');
            $table->string('availability_end_date');
            $table->string('teaching_days');
            $table->string('teaching_hours');
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
        Schema::dropIfExists('teaching_specifications');
    }
}
