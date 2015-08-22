<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function ($table) {
            $table->unsignedInteger('google_geocode_id',10)->default(0);
        });

        Schema::table('reports', function ($table) {
            $table->foreign('google_geocode_id')->references('id')->on('google_geocodes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
