<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsFromTeachingSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teaching_specifications', function (Blueprint $table) {
            $table->dropColumn('level_of_education');
            $table->dropColumn('field_of_study');
            $table->dropColumn('subject');
            $table->dropColumn('expected_salary_per_hour');
            $table->dropColumn('teaching_days');
            $table->dropColumn('teaching_hours');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teaching_specifications', function (Blueprint $table) {
            //
        });
    }
}
