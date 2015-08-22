<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleGeodecode extends Model
{

    protected $table = "google_geocodes";
    protected $primaryKey = 'place_id';


}