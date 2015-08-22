<?php

use App\Models\Commodity;
use Illuminate\Database\Seeder;

class CommoditiesTableSeeder extends DatabaseSeeder
{

    public function run()
    {
        Commodity::create([
            'name' => 'Daging Sapi Paha Depan'
        ]);

        Commodity::create([
            'name' => 'Daging Iga Sapi'
        ]);

        Commodity::create([
            'name' => 'Has Dalam'
        ]);

        Commodity::create([
            'name' => 'Tanjung'
        ]);

        Commodity::create([
            'name' => 'Lamosir'
        ]);

        Commodity::create([
            'name' => 'T-Bone'
        ]);

        Commodity::create([
            'name' => 'Lidah Sapi'
        ]);

        Commodity::create([
            'name' => 'Ekor Sapi'
        ]);

        Commodity::create([
            'name' => 'Sandung Lamur'
        ]);

        Commodity::create([
            'name' => 'Sengkel'
        ]);

        Commodity::create([
            'name' => 'Hati Sapi'
        ]);

        Commodity::create([
            'name' => 'Jeroan Sapi'
        ]);

        Commodity::create([
            'name' => 'Kaki Sapi'
        ]);

        Commodity::create([
            'name' => 'Kulit Sapi'
        ]);

        Commodity::create([
            'name' => 'Tetelan'
        ]);

        Commodity::create([
            'name' => 'Kikil'
        ]);

        Commodity::create([
            'name' => 'Kelapa'
        ]);
    }

}