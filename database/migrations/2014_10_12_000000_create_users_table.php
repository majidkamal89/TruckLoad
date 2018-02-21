<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('email', 200)->unique();
            $table->string('password', 500);
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->integer('company_id')->default(0);
            $table->integer('user_type')->default(0)->comment('1-Admin, 2-Manager, 3-Driver');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
