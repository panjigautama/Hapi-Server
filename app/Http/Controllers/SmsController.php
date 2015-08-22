<?php

namespace App\Http\Controllers;


use App\Models\Commodity;
use App\Models\DataSource;
use App\Models\GoogleGeodecode;
use App\Models\Location;
use App\Models\Report;
use GuzzleHttp\Client;

class SmsController
{

    public function getGeodecode($location)
    {
        $location = "pasar cipulir";

        $relevant_result = 0;
        $url_encoded_string = urlencode($location);
        $client = new \GuzzleHttp\Client();
        $res = $client->get('https://maps.googleapis.com/maps/api/geocode/json?address=' . $url_encoded_string . '&key=AIzaSyAN6zAyOynhjQDMEdjRFMlOb8c7IrbUF-k');
        if ($res->getStatusCode() == 200) {
            $response_message = $res->getBody();
            $response_message_json_obj = json_decode($response_message);

            // check status from google geocoding API
            if ($response_message_json_obj->status == "OK") {
                $results = $response_message_json_obj->results;

                // check whether result return more than one result
                $result_size = count($results);
                if ($result_size > 0) {

                    // iterate results
                    $counter = 0;
                    while ($counter < $result_size) {
                        $googleGeocodeItem = $results[$counter];
                        // save geodecode result
                        $googleGeocode = GoogleGeodecode::findOrNew($googleGeocodeItem->place_id);
                        $googleGeocode->formatted_address = $googleGeocodeItem->formatted_address;
                        $googleGeocode->location_lat = $googleGeocodeItem->geometry->location->lat;
                        $googleGeocode->location_lng = $googleGeocodeItem->geometry->location->lng;
                        $googleGeocode->save();
                        $counter++;
                    }

                    // get relevant location with keyword
                    $relevant_result = GoogleGeodecode::where('formatted_address', 'LIKE', '%' . $location . '%')->first();
                }
            }
        }
        return $relevant_result;
    }

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
                // get geo location
                $geodecode = $this->getGeodecode($location_dirty);
                $location->name = $location_dirty;
                $location->longitude = $geodecode == 0 ? $geodecode : $geodecode->location_lat;
                $location->latitude = $geodecode == 0 ? $geodecode : $geodecode->location_lng;
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
            $reports->price = $price_cleaned;
            $reports->save();
        }
    }

}