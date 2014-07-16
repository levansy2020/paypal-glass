<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Sandbox Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in sandbox mode, all API calls will be made
    | against PayPal's sandbox servers for testing purposes.
    |
    */

    'SANDBOX' => $_ENV['PAYPAL_SANDBOX'],

    /*
	|--------------------------------------------------------------------------
	| API Username
	|--------------------------------------------------------------------------
	|
	| This is the API username for the account that owns the application.
	|
	*/

    'API_USERNAME' => $_ENV['PAYPAL_API_USERNAME'],

    /*
	|--------------------------------------------------------------------------
	| API Passwrd
	|--------------------------------------------------------------------------
	|
	| This is the API password for the account that owns the application.
	|
	*/

    'API_PASSWORD' => $_ENV['PAYPAL_API_PASSWORD'],

    /*
	|--------------------------------------------------------------------------
	| API Signature
	|--------------------------------------------------------------------------
	|
	| This is the API signature for the account that owns the application.
	|
	*/

    'API_SIGNATURE' => $_ENV['PAYPAL_API_SIGNATURE'],

    /*
	|--------------------------------------------------------------------------
	| Raw API Dump
	|--------------------------------------------------------------------------
	|
	| Enable / Disable the raw API request/response dump at the bottom of
    | pages.
	|
	*/

    'RAW_API_DUMP' => TRUE,

);