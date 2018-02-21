<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTruckListImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('las_truck_lists_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('truck_list_id');
            $table->string('image_name', 100)->nullable();
            $table->string('image_path', 100)->nullable();
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
        Schema::dropIfExists('las_truck_lists_images');
    }
}
