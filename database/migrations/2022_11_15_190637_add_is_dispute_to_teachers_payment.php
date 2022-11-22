<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDisputeToTeachersPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_payments', function (Blueprint $table) {
           $table->boolean('is_dispute')->after('transaction_id')->default(0);
           $table->string('payment_method')->after('is_dispute')->default('PayPal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers_payment', function (Blueprint $table) {
            //
        });
    }
}
