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

    /**
     * @return array
     *
     * Get an array of config data to pass into PayPal API request methods.
     */
    private function getPayPalConfig()
    {
        return array(
            'Sandbox' => $_ENV['SANDBOX'],
            'APIUsername' => $_ENV['PAYPAL_API_USERNAME'],
            'APIPassword' => $_ENV['PAYPAL_API_PASSWORD'],
            'APISignature' => $_ENV['PAYPAL_API_SIGNATURE']
        );
    }

    /**
     * @return mixed
     *
     * Call PayPal's GetBalance API and pass the result to a balance view.
     */
    public function getBalance()
	{
        $PayPal = new PayPal\PayPal($this->getPayPalConfig());
        $GBFields = array('returnallcurrencies' => true);
        $PayPalRequestData = array('GBFields'=>$GBFields);
        $PayPalResult = $PayPal->GetBalance($PayPalRequestData);

        $Balance = number_format($PayPalResult['BALANCERESULTS'][0]['L_AMT'],2);

        return View::make('paypal/get-balance')->with('balance', $Balance);
	}

}
