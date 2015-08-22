<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleGeocodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_geocodes', function (Blueprint $table) {
            $table->string('place_id')->unique();
            $table->string("formatted_address");
            $table->double("location_lat");
            $table->double("location_lng");
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
        Schema::drop('google_geocodes');
    }
}
