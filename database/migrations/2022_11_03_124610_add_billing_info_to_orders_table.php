<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingInfoToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('billing_country')->after('transaction_id')->nullable();
            $table->string('billing_state')->after('billing_country')->nullable();
            $table->string('billing_city')->after('billing_state')->nullable();
            $table->string('billing_street')->after('billing_city')->nullable();
            $table->string('postcode')->after('billing_street')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
