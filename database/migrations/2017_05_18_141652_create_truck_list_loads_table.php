<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTruckListLoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('las_truck_lists_loads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('truck_list_id');
            $table->dateTime('load_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('from_destination', 250)->nullable();
            $table->string('to_destination', 250)->nullable();
            $table->integer('las_master_data_load_id')->default(0);
            $table->integer('las_master_data_volume_id')->default(0);
            $table->decimal('quantity', 9, 2)->default(0);
            $table->string('notes', 500)->nullable();
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
        Schema::dropIfExists('las_truck_lists_loads');
    }
}
