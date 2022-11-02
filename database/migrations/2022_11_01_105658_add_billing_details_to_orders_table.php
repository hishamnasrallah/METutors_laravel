<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingDetailsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('email')->after('transaction_id')->nullable();
            $table->string('billing_country')->after('email')->nullable();
            $table->string('billing_state')->after('billing_country')->nullable();
            $table->string('billing_city')->after('billing_state')->nullable();
            $table->string('billing_street')->after('billing_city')->nullable();
            $table->string('postal_code')->after('billing_street')->nullable();
            $table->string('customer_name')->after('postal_code')->nullable();
            $table->string('customer_surname')->after('customer_name')->nullable();
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
