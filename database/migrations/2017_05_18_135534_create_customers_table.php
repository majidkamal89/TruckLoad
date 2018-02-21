<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('las_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_name', 100);
            $table->string('customer_address', 250)->nullable();
            $table->integer('user_id')->default(0);
            $table->string('signature', 100)->nullable();
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
        Schema::dropIfExists('las_customers');
    }
}
