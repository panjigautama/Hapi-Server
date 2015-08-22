<?php

use App\Models\DataSource;
use Illuminate\Database\Seeder;

class DataSourceTableSeeder extends DatabaseSeeder
{

    public function run()
    {
        // Data Source SMS
        DataSource::create([
            'name' => 'SMS'
        ]);

        // Data Source Pasar Jaya
        DataSource::create([
            'name' => 'Pasar jaya'
        ]);

        // Data Source Kemendag
        DataSource::create([
            'name' => 'Kementrian Perdagangan'
        ]);

    }

}