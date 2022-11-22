<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisputeTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispute_tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('dispute_id');
            $table->text('course_ids');
            $table->string('transaction_id');
            $table->string('subject');
            $table->string('priority');
            $table->longText('message');
            $table->string('file')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('dispute_tickets');
    }
}
