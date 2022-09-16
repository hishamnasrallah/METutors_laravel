<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceFeeToReundClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refund_classes', function (Blueprint $table) {
            $table->double('service_fee')->after('academic_class_id');
            $table->double('refund')->after('service_fee');
            $table->string('status')->default('pending')->after('refund');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reund_classes', function (Blueprint $table) {
            //
        });
    }
}
