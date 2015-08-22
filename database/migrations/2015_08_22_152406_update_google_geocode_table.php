<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGoogleGeocodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('google_geocodes', function ($table) {
            $table->string("administrative_area_level_1")->nullable();
            $table->string("administrative_area_level_2")->nullable();
            $table->string("administrative_area_level_3")->nullable();
            $table->string("administrative_area_level_4")->nullable();
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
