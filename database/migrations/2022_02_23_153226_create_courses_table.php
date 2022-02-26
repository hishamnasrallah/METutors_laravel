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
            $table->string('course_code')->nullable(); 
            $table->string('course_level')->nullable(); 
            $table->string('course_field'); 
            $table->integer('language_id');
            $table->string('book_type');
            $table->string('book_info');
            $table->string('book_name')->nullable();
            $table->string('book_edition')->nullable();
            $table->string('book_author')->nullable()   ;
            $table->float('total_price'); 
            $table->integer('country_id')->nullable();  
            $table->integer('program_id'); 
            $table->string('total_hours');
            $table->integer('total_classes');
            $table->string('files')->nullable();
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
        Schema::dropIfExists('courses');
    }
}
