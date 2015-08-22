<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('/', 'HomeController@index');
$app->get('/chart', 'Controller@index');
$app->get('/test', 'TestController@index');
$app->get('/parse-web-pasarjaya', 'WebParserController@parsePasarjaya');


/**
 * --------------------------------------
 * SMS Handler
 * --------------------------------------
 **/
$app->group(['prefix' => 'sms'], function ($app) {

    $app->get('manual_call', 'App\Http\Controllers\SmsController@storeAndParseSMS');
    $app->post('request', 'App\Http\Controllers\SmsController@twilioRequestURL');

});

/**
 * --------------------------------------
 * Google API Handler
 * --------------------------------------
 **/
$app->group(['prefix' => 'google'], function ($app) {

    $app->get('get_token', 'App\Http\Controllers\FusionTableController@generateFusionData');

});
