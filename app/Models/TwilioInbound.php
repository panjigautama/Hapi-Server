<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwilioInbound extends Model
{

    protected $table = "twilio_inbounds";
    protected $primaryKey = 'message_id';

}