<?php

namespace App\Http\Controllers;


use App\Models\Commodity;
use App\Models\DataSource;
use App\Models\Location;
use App\Models\Report;

class SmsController
{

    public function storeAndParseSMS()
    {
        $sms_sender = "+6285725706128";
        $sms_content = "Rp150.000#pasar cipulir";

        // store to sms
        $sms = new SMS;
        $sms->sender = $sms_sender;
        $sms->content = $sms_content;
        $sms->save();

        // clean up sms content
        // sms content will be unparseable if the content array size is not 2 after exploded
        $sms_item_arrays = explode("#", $sms_content);
        if (!count($sms_item_arrays) < 3) {

            // index 0 is for price
            $price_dirty = $sms_item_arrays[0];
            $price_dirty = preg_replace("/[^0-9,.]/", "", $price_dirty);
            $price_cleaned = trim($price_dirty);

            // index 1 is for location
            $location_dirty = $sms_item_arrays[1];
            $location = Location::where('name', 'LIKE', '%' . $location_dirty . '%')->first();
            if (count($location) == 0) {
                $location = new Location;
                $location_lat = 0;
                $location_lng = 0;
                $location->name = $location_dirty;
                $location->longitude = $location_lat;
                $location->latitude = $location_lng;
                $location->save();
            }

            // index 2 is for commodities type
            $commodities_type_dirty = $sms_item_arrays[2];
            $commodities_type_cleaned = trim($commodities_type_dirty);
            $commodity = Commodity::where('name', 'LIKE', '%' . $commodities_type_cleaned . '%')->first();
            if (count($commodity) == 0) {
                $commodity = new Commodity;
                $commodity->name = $commodities_type_cleaned;
                $commodity->save();
            }

            // save report
            $reports = new Report;
            $reports->commodities_id = $commodity->id();
            $reports->location_id = $location->id();
            $reports->data_sources_id = 1;
            $reports->sms_id = $sms->id;
            $reports->save();
        }
    }

}