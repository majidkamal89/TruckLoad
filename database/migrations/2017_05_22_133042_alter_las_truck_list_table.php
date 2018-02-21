<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLasTruckListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('las_truck_lists', function (Blueprint $table) {
            $table->renameColumn('company_id', 'customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('las_truck_lists', function (Blueprint $table) {
            $table->renameColumn('customer_id', 'company_id');
        });
    }
}
