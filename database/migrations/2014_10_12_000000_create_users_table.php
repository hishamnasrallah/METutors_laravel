<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('id_number')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->longText('full_name')->nullable();
            $table->string('role_name');
            $table->integer('role_id');
            $table->string('mobile')->nullable();
            $table->string('email')->unique();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('bio')->nullable();
            $table->string('password')->nullable();
            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->integer('verified')->default(0);
            $table->integer('financial_approval')->default(0);
            $table->string('avatar')->nullable();
            $table->string('cover_img')->nullable();
            $table->string('headline')->nullable();
            $table->longText('about')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('status')->default('active');
            $table->string('admin_approval')->default('pending');
            $table->integer('profile_completed_step')->default(0);
            $table->integer('newsletter')->default(0);
            $table->integer('public_message')->default(0);
            $table->string('account_type')->nullable();
            $table->integer('ban')->default(0);
            $table->integer('ban_start_at')->nullable();
            $table->integer('ban_end_at')->nullable();
            $table->integer('offline')->default(0);
            $table->longText('offline_message')->nullable();
            $table->integer('kudos_points')->nullable();
            $table->rememberToken()->nullable();
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
        Schema::dropIfExists('users');
    }
}
