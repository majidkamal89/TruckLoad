<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMasterdataTableFieldType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('las_master_data', function($table)
        {
            $table->string('value', 100)->unique()->change(); //notice the parenthesis I added
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('las_master_data', function($table)
        {
            $table->dropUnique('value'); //notice the parenthesis I added
        });
    }
}
