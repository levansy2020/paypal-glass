<?php

use angelleye\PayPal;

class PayPalController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| PayPal Controller
	|--------------------------------------------------------------------------
	|
	| Here we'll make all the calls to PayPal API's.
	|
	|
	*/

	public function getBalance()
	{
        /**
         *  Run GetBalance API Request
         */

        $PayPalConfig = array(
            'Sandbox' => $_ENV['SANDBOX'],
            'APIUsername' => $_ENV['PAYPAL_API_USERNAME'],
            'APIPassword' => $_ENV['PAYPAL_API_PASSWORD'],
            'APISignature' => $_ENV['PAYPAL_API_SIGNATURE']
        );

        $PayPal = new PayPal\PayPal($PayPalConfig);
        $GBFields = array('returnallcurrencies' => true);
        $PayPalRequestData = array('GBFields'=>$GBFields);
        $PayPalResult = $PayPal->GetBalance($PayPalRequestData);

        $balance = number_format($PayPalResult['BALANCERESULTS'][0]['L_AMT'],2);

        return View::make('paypal/get-balance')->with('balance', $balance);
	}

}
