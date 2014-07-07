<?php

/**
 * Class PayPal
 */
class PayPal {

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
        $PayPal = new \angelleye\PayPal\PayPal($this->getPayPalConfig());
        $GBFields = array('returnallcurrencies' => true);
        $PayPalRequestData = array('GBFields'=>$GBFields);
        $PayPalResult = $PayPal->GetBalance($PayPalRequestData);

        $Balance = number_format($PayPalResult['BALANCERESULTS'][0]['L_AMT'],2);

        return $Balance;

    }
}