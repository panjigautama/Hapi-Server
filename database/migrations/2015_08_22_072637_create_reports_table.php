<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('commodities_id');
            $table->unsignedInteger('location_id');
            $table->unsignedInteger('data_sources_id');
            $table->unsignedInteger('sms_id');
            $table->timestamps();
        });

        Schema::table('reports', function ($table) {
            $table->foreign('commodities_id')->references('id')->on('commodities');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('data_sources_id')->references('id')->on('data_sources');
            $table->foreign('sms_id')->references('id')->on('sms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reports');
    }
}
