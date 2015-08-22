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

$app->get('/', 'Controller@index');

$app->get('/test', 'TestController@index');
$app->get('/parse-web-pasarjaya', 'WebParserController@parsePasarjaya');

$app->get('/test_parse_sms', 'SmsController@storeAndParseSMS');
