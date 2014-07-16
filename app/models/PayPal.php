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
            'Sandbox' => Config::get('paypal.sandbox'),
            'APIUsername' => Config::get('paypal.api-username'),
            'APIPassword' => Config::get('paypal.api-password'),
            'APISignature' => Config::get('paypal.api-signature')
        );
    }

    public function logger($type = 'info', $name = '', $data = array())
    {
        if(Config::get('paypal.api-log'))
        {
            Log::$type($name, $data);
        }
    }

    /**
     * GetBalance API Request
     */
    public function getBalance()
    {
        $GBFields = array('returnallcurrencies' => true);
        $PayPalRequestData = array('GBFields'=>$GBFields);
        $PayPalResult = $this->PayPal->GetBalance($PayPalRequestData);

        // API Logs
        $log_type = $this->PayPal->APICallSuccessful($PayPalResult['ACK']) ? 'info' : 'error';
        $log_name = 'PayPal API Result';
        $log_data = array(
            'API Request' => $this->PayPal->MaskAPIResult($PayPalResult['RAWREQUEST']),
            'API Response' => $PayPalResult['RAWRESPONSE']
        );
        $this->logger($log_type, $log_name, $log_data);

        if($this->PayPal->APICallSuccessful($PayPalResult['ACK']))
        {
            /**
             * Returns first balance result.
             *
             * @todo
             * Enhance this by allowing users to specify
             * which currency balance they want to return,
             * or go ahead and display all currency values
             * on overview instead of just one.
             */
            $Balance = $PayPalResult['BALANCERESULTS'][0]['L_AMT'];
            return $Balance;
        }
        else
        {
            // Store errors in flash and return false.
            $errors = isset($PayPalResult['ERRORS']) ? $PayPalResult['ERRORS'] : array();
            Session::flash('errors', $errors);

            return false;
        }
    }

    /**
     * GetTransactionDetails API Request
     */
    public function getTransactionDetails($transaction_id)
    {
        // Prepare request arrays
        $GTDFields = array(
            'transactionid' => $transaction_id							// PayPal transaction ID of the order you want to get details for.
        );

        $PayPalRequestData = array('GTDFields'=>$GTDFields);

        // Pass data into class for processing with PayPal and load the response array into $PayPalResult
        $PayPalResult = $this->PayPal->GetTransactionDetails($PayPalRequestData);

        // Add subtotal and net total to results.
        $subtotal = isset($PayPalResult['AMT']) ? $PayPalResult['AMT'] : 0;
        $subtotal = isset($PayPalResult['TAXAMT']) ? $subtotal - $PayPalResult['TAXAMT'] : $subtotal;
        $subtotal = isset($PayPalResult['SHIPPINGAMT']) ? $subtotal - $PayPalResult['SHIPPINGAMT'] : $subtotal;
        $subtotal = isset($PayPalResult['HANDLINGAMT']) ? $subtotal - $PayPalResult['HANDLINGAMT'] : $subtotal;
        $PayPalResult['SUBTOTAL'] = $subtotal;

        $nettotal = isset($PayPalResult['AMT']) ? $PayPalResult['AMT'] : 0;
        $nettotal = isset($PayPalResult['FEEAMT']) ? $nettotal - $PayPalResult['FEEAMT'] : $nettotal;
        $PayPalResult['NETAMT'] = $nettotal;

        // API Logs
        $log_type = $this->PayPal->APICallSuccessful($PayPalResult['ACK']) ? 'info' : 'error';
        $log_name = 'PayPal API Result';
        $log_data = array(
            'API Request' => $this->PayPal->MaskAPIResult($PayPalResult['RAWREQUEST']),
            'API Response' => $PayPalResult['RAWRESPONSE']
        );
        $this->logger($log_type, $log_name, $log_data);

        if($this->PayPal->APICallSuccessful($PayPalResult['ACK']))
        {
            // Success
            return $PayPalResult;
        }
        else
        {
            // Store errors in flash and return false.
            $errors = isset($PayPalResult['ERRORS']) ? $PayPalResult['ERRORS'] : array();
            Session::flash('errors', $errors);

            return false;
        }
    }

    /**
     * TransactionSearch API Request
     */
    public function transactionSearch($params)
    {
        // Prepare request arrays
        $number_of_days = isset($params['number_of_days']) ? $params['number_of_days'] : 1;
        $start_date = gmdate("Y-m-d\\TH:i:sZ",strtotime('now - ' . $number_of_days . ' days'));
        $end_date = isset($params['end_date']) ? $params['end_date'] : '';
        $email = isset($params['email']) ? $params['email'] : '';
        $receiver = isset($params['receiver']) ? $params['receiver'] : '';
        $receipt_id = isset($params['receipt_id']) ? $params['receipt_id'] : '';
        $transaction_id = isset($params['transaction_id']) ? $params['transaction_id'] : '';
        $invoice_number = isset($params['invoice_number']) ? $params['invoice_number'] : '';
        $cc_number = isset($params['cc_number']) ? $params['cc_number'] : '';
        $auction_item_number = isset($params['auction_item_number']) ? $params['auction_item_number'] : '';
        $transaction_class = isset($params['transaction_class']) ? $params['transaction_class'] : '';
        $amount = isset($params['amount']) ? $params['amount'] : '';
        $currency_code = isset($params['currency_code']) ? $params['currency_code'] : '';
        $status = isset($params['status']) ? $params['status'] : '';
        $profile_id = isset($params['profile_id']) ? $params['profile_id'] : '';
        $salutation = isset($params['salutation']) ? $params['salutation'] : '';
        $first_name = isset($params['first_name']) ? $params['first_name'] : '';
        $middle_name = isset($params['middle_name']) ? $params['middle_name'] : '';
        $last_name = isset($params['last_name']) ? $params['last_name'] : '';
        $suffix = isset($params['suffix']) ? $params['suffix'] : '';

        $TSFields = array(
            'startdate' => $start_date, 							// Required.  The earliest transaction date you want returned.  Must be in UTC/GMT format.  2008-08-30T05:00:00.00Z
            'enddate' => $end_date, 							// The latest transaction date you want to be included.
            'email' => $email, 								// Search by the buyer's email address.
            'receiver' => $receiver, 							// Search by the receiver's email address.
            'receiptid' => $receipt_id, 							// Search by the PayPal account optional receipt ID.
            'transactionid' => $transaction_id, 						// Search by the PayPal transaction ID.
            'invnum' => $invoice_number, 							// Search by your custom invoice or tracking number.
            'acct' => $cc_number, 								// Search by a credit card number, as set by you in your original transaction.
            'auctionitemnumber' => $auction_item_number, 					// Search by auction item number.
            'transactionclass' => $transaction_class, 					// Search by classification of transaction.  Possible values are: All, Sent, Received, MassPay, MoneyRequest, FundsAdded, FundsWithdrawn, Referral, Fee, Subscription, Dividend, Billpay, Refund, CurrencyConversions, BalanceTransfer, Reversal, Shipping, BalanceAffecting, ECheck
            'amt' => $amount, 								// Search by transaction amount.
            'currencycode' => $currency_code, 						// Search by currency code.
            'status' => $status,  							// Search by transaction status.  Possible values: Pending, Processing, Success, Denied, Reversed
            'profileid' => $profile_id							// Recurring Payments profile ID.  Currently undocumented but has tested to work.
        );

        $PayerName = array(
            'salutation' => $salutation, 						// Search by payer's salutation.
            'firstname' => $first_name, 							// Search by payer's first name.
            'middlename' => $middle_name, 						// Search by payer's middle name.
            'lastname' => $last_name, 							// Search by payer's last name.
            'suffix' => $suffix	 							// Search by payer's suffix.
        );

        $PayPalRequestData = array(
            'TSFields' => $TSFields,
            'PayerName' => $PayerName
        );

        // Pass data into class for processing with PayPal and load the response array into $PayPalResult
        $PayPalResult = $this->PayPal->TransactionSearch($PayPalRequestData);

        // API Logs
        $log_type = $this->PayPal->APICallSuccessful($PayPalResult['ACK']) ? 'info' : 'error';
        $log_name = 'PayPal API Result';
        $log_data = array(
            'API Request' => $this->PayPal->MaskAPIResult($PayPalResult['RAWREQUEST']),
            'API Response' => $PayPalResult['RAWRESPONSE']
        );
        $this->logger($log_type, $log_name, $log_data);

        if($this->PayPal->APICallSuccessful($PayPalResult['ACK']))
        {
            // Success
            $SearchResults = isset($PayPalResult['SEARCHRESULTS']) ? $PayPalResult['SEARCHRESULTS'] : array();
            return $SearchResults;
        }
        else
        {
            // Store errors in flash and return false.
            $errors = isset($PayPalResult['ERRORS']) ? $PayPalResult['ERRORS'] : array();
            Session::flash('errors', $errors);

            return false;
        }
    }
}