<?php

/**
 * Class PayPal
 */
class PayPal {

    public function __construct(){
        $this->PayPal = new \angelleye\PayPal\PayPal($this->getPayPalConfig());
    }

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
        $GBFields = array('returnallcurrencies' => true);
        $PayPalRequestData = array('GBFields'=>$GBFields);
        $PayPalResult = $this->PayPal->GetBalance($PayPalRequestData);

        if($this->PayPal->APICallSuccessful($PayPalResult['ACK']))
        {
            $Balance = number_format($PayPalResult['BALANCERESULTS'][0]['L_AMT'],2);
            return $Balance;
        }
        else
        {
            return 'Balance not available.';
        }
    }

    /**
     * Call PayPal's TransactionSearch API for recent history.
     */
    public function transactionSearch($params)
    {
        // Prepare request arrays
        $number_of_days = $params['number_of_days'];

        $start_date = gmdate("Y-m-d\\TH:i:sZ",strtotime('now - ' . $number_of_days . ' days'));

        $TSFields = array(
            'startdate' => $start_date, 							// Required.  The earliest transaction date you want returned.  Must be in UTC/GMT format.  2008-08-30T05:00:00.00Z
            'enddate' => '', 							// The latest transaction date you want to be included.
            'email' => '', 								// Search by the buyer's email address.
            'receiver' => '', 							// Search by the receiver's email address.
            'receiptid' => '', 							// Search by the PayPal account optional receipt ID.
            'transactionid' => '', 						// Search by the PayPal transaction ID.
            'invnum' => '', 							// Search by your custom invoice or tracking number.
            'acct' => '', 								// Search by a credit card number, as set by you in your original transaction.
            'auctionitemnumber' => '', 					// Search by auction item number.
            'transactionclass' => '', 					// Search by classification of transaction.  Possible values are: All, Sent, Received, MassPay, MoneyRequest, FundsAdded, FundsWithdrawn, Referral, Fee, Subscription, Dividend, Billpay, Refund, CurrencyConversions, BalanceTransfer, Reversal, Shipping, BalanceAffecting, ECheck
            'amt' => '', 								// Search by transaction amount.
            'currencycode' => '', 						// Search by currency code.
            'status' => '',  							// Search by transaction status.  Possible values: Pending, Processing, Success, Denied, Reversed
            'profileid' => ''							// Recurring Payments profile ID.  Currently undocumented but has tested to work.
        );

        $PayerName = array(
            'salutation' => '', 						// Search by payer's salutation.
            'firstname' => '', 							// Search by payer's first name.
            'middlename' => '', 						// Search by payer's middle name.
            'lastname' => '', 							// Search by payer's last name.
            'suffix' => ''	 							// Search by payer's suffix.
        );

        $PayPalRequestData = array(
            'TSFields' => $TSFields,
            'PayerName' => $PayerName
        );

        // Pass data into class for processing with PayPal and load the response array into $PayPalResult
        $PayPalResult = $this->PayPal->TransactionSearch($PayPalRequestData);

        if($this->PayPal->APICallSuccessful($PayPalResult['ACK']))
        {
            // Success
            $SearchResults = isset($PayPalResult['SEARCHRESULTS']) ? $PayPalResult['SEARCHRESULTS'] : array();
            return $SearchResults;
        }
        else
        {
            // Failure
            return 'Not available.';
        }
    }
}