<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwilioInboundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('twilio_inbounds', function (Blueprint $table) {
            $table->string('message_id')->unique();
            $table->string('sms_id');
            $table->string('account_id');
            $table->string('from');
            $table->string('to');
            $table->string('body');
            $table->integer('num_media');
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
        Schema::drop('twilio_inbounds');
    }
}
