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
 * Fusion Table Updater
 * --------------------------------------
 **/
$app->group(['prefix' => 'fusion'], function ($app) {

    $app->get('update', 'App\Http\Controllers\FusionTableController@updateFusionTable');

});
