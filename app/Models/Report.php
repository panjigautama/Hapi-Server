<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    protected $table = "reports";
    
    public function location()
    {
        return $this->belongsTo( 'App\Models\Location', 'location_id' );
    }
}