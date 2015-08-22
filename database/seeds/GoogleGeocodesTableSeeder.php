<?php

use App\Models\GoogleGeodecode;
use Illuminate\Database\Seeder;

class GoogleGeocodesTableSeeder extends DatabaseSeeder
{

    public function run()
    {
        GoogleGeodecode::create([
            'id' => 0,
            'place_id' => 'uncategorized',
            'formatted_address' => 'uncategorized',
            'location_lat' => '0',
            'location_lng' => '0'
        ]);
    }

}