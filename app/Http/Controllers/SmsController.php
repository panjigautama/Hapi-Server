<?php

namespace App\Http\Controllers;

use App\Models\Commodity;
use App\Models\DataSource;
use App\Models\SMS;
use App\Models\GoogleGeodecode;
use App\Models\Location;
use App\Models\Report;
use App\Models\TwilioInbound;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SmsController extends Controller
{

    public function getGeodecode($location)
    {
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
                        $googleGeocode->place_id = $googleGeocodeItem->place_id;
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

    public function storeAndParseSMS($sender, $content)
    {
        // store to sms
        $sms = new SMS;
        $sms->sender = $sender;
        $sms->content = $content;
        $sms->save();

        // clean up sms content
        // sms content will be unparseable if the content array size is not 2 after exploded
        $sms_item_arrays = explode("#", $content);
        $size_parser = count($sms_item_arrays);
        if ($size_parser >= 3) {

            // index 0 is for price
            $price_dirty = $sms_item_arrays[0];
            $price_dirty = preg_replace("/[^0-9]/", "", $price_dirty);
            $price_cleaned = trim($price_dirty);

            // index 1 is for location
            $location_dirty = $sms_item_arrays[1];
            $location = Location::where('name', 'LIKE', '%' . $location_dirty . '%')->first();

            if (count($location) == 0) {
                $location = new Location;
                // get geo location
                $geodecode = $this->getGeodecode($location_dirty);
                $location->name = $location_dirty;
                $location->longitude = is_object($geodecode) ? $geodecode->location_lat : $geodecode;
                $location->latitude = is_object($geodecode) ? $geodecode->location_lng : $geodecode;
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
            $report = new Report;
            $report->commodities_id = $commodity->id;
            $report->location_id = $location->id;
            $report->data_sources_id = 1;
            $report->sms_id = $sms->id;
            $report->price = $price_cleaned;
            $report->save();

            return 1;
        } else {
            return 0;
        }
    }

    /**
     * ----------------------------------
     * TWILIO HANDLER
     * ----------------------------------
     */
    public function twilioRequestURL(Request $request)
    {
        $messageId = $request->input('MessageSid');
        $smsId = $request->input('SmsSid');
        $accountId = $request->input('AccountSid');
        $from = $request->input('From');
        $to = $request->input('To');
        $body = $request->input('Body');
        $numMedia = $request->input('NumMedia');

        if (isset($messageId) && isset($smsId) && isset($accountId) && isset($from) && isset($to) && isset($body) && isset($numMedia)) {

            // insert twilio logs
            $twilioInbound = TwilioInbound::findOrNew($messageId);
            $twilioInbound->message_id = $messageId;
            $twilioInbound->sms_id = $smsId;
            $twilioInbound->account_id = $accountId;
            $twilioInbound->from = $from;
            $twilioInbound->to = $to;
            $twilioInbound->body = $body;
            $twilioInbound->num_media = $numMedia;
            $twilioInbound->save();

            // process sms
            $result = $this->storeAndParseSMS($twilioInbound->from, $twilioInbound->body);

            // if ok then return twiML
            if ($result == 1) {
                $twiMl_response = '<?xml version="1.0" encoding="UTF-8" ?><Response><Message>Terima kasih telah melapor ! hati-hati kolesterol ! :)</Message></Response>';
                return $twiMl_response;
            }
        } else {
            return 0;
        }
    }

    public function twilioGetInbounds()
    {
        $today = Carbon::now()->toDateString();
        $client = new \GuzzleHttp\Client();
        $response = $client->get('https://api.twilio.com/2010-04-01/Accounts/AC6c006185b0ee1a6a6556d7efb7e2377f/Messages.json?To=+18329003539&DateSent=' . $today, ['auth' => ['AC6c006185b0ee1a6a6556d7efb7e2377f', '26b43ef33b2e7942908a9816b63da1ee']]);
        if ($response->getStatusCode() == 200) {
            return $response->getBody();
        } else {
            return "error";
        }
    }


}