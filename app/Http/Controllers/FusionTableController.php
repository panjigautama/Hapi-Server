<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Google_Auth_AssertionCredentials;
use Google_Client;
use Google_Service_Fusiontables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FusionTableController extends Controller
{

    public function updateFusionData($lowest_price, $highest_price, $price_fraction, $kecamatan_name)
    {
        // get token
        $client_id = "158682885156-dit0o067eji2ki52ftfnt4m478ru16eq.apps.googleusercontent.com";
        $client_email = '158682885156-3o5ij8s9v1t272jam9o34tagqa5gf6j8@developer.gserviceaccount.com';
        $private_key = file_get_contents(base_path() . '/app/Http/Controllers/Hapi.p12');
        $scopes = array('https://www.googleapis.com/auth/fusiontables');
        $credentials = new Google_Auth_AssertionCredentials(
            $client_email,
            $scopes,
            $private_key
        );
        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setAssertionCredentials($credentials);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion();
        }

        // get data
        $tableId = "13492tA0Z8fabNTdMvkRto-ccdub7YQpxRBieA7Vl";
        $query = ("SELECT ROWID FROM " . $tableId . " WHERE nm_keca = '" . $kecamatan_name . "'");
        $fushionTable = new Google_Service_Fusiontables($client);

        $row_id = 0;
        try {
            $result = $fushionTable->query->sql($query);
            if (count($result->rows) > 0) {
                $row_id = $result->rows[0][0];
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        // update row id
        if ($row_id != 0) {
            $query = ("UPDATE " . $tableId . " SET lowest_price = " . $lowest_price . ", highest_price = " . $highest_price . ", price_fraction = " . $price_fraction . " WHERE ROWID = '" . $row_id . "'");
            $result = $fushionTable->query->sql($query);
            return json_encode($result);
        }

        return 0;
    }

    public function updateFusionTable()
    {
        $results = DB::select("SELECT * FROM reports WHERE DATE(created_at) = CURDATE()");
        return json_encode($results);
    }

}