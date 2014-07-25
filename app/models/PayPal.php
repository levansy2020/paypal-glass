<?php

/**
 * Class PayPal
 */
class PayPal {

    /**
     * Constructor
     */
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

    /**
     * logger()
     *
     * Log data with ability to pass in type, name, and log data.
     *
     * @param string $type
     * @param string $name
     * @param array $data
     */
    public function logger($type = 'info', $name = '', $data = array())
    {
        if(Config::get('paypal.api-log'))
        {
            Log::$type($name, $data);
        }
    }

    public function addressVerify($params)
    {
        $email = isset($params['email']) ? $params['email'] : '';
        $street = isset($params['street']) ? $params['street'] : '';
        $zip = isset($params['zip']) ? $params['zip'] : '';

        $AVFields = array
        (
            'email' => $email, 							// Required. Email address of PayPal member to verify.
            'street' => $street, 						// Required. First line of the postal address to verify.  35 char max.
            'zip' => $zip								// Required.  Postal code to verify.
        );

        $PayPalRequestData = array('AVFields'=>$AVFields);
        $PayPalResult = $PayPal->AddressVerify($PayPalRequestData);

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
            return $PayPalResult;
        }
        else
        {
            $errors = isset($PayPalResult['ERRORS']) ? $PayPalResult['ERRORS'] : array();
            Session::flash('errors', $errors);
            throw new PayPalException;
        }
    }

    /**
     * GetBalance API
     *
     * Get balance details for the PayPal account.
     *
     * @param $params
     * @return array|bool
     */
    public function getBalance($params)
    {
        $return_all_currencies = isset($params['return_all_currencies']) ? $params['return_all_currencies'] : false;

        $GBFields = array('returnallcurrencies' => $return_all_currencies);
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
            return $PayPalResult;
        }
        else
        {
            $errors = isset($PayPalResult['ERRORS']) ? $PayPalResult['ERRORS'] : array();
            Session::flash('errors', $errors);
            throw new PayPalException;
        }
    }

    /**
     * Returns the current default balance of the PayPal account.
     *
     * @return mixed
     */
    public function getCurrentDefaultBalance()
    {
        $params = array('return_all_currencies' => false);
        $result = $this->getBalance($params);

        /**
         * Returns first balance result.
         *
         * @todo
         * Enhance this by allowing users to specify
         * which currency balance they want to return,
         * or go ahead and display all currency values
         * on overview instead of just one.
         */
        $Balance = $result['BALANCERESULTS'][0]['L_AMT'];
        return $Balance;
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
            $errors = isset($PayPalResult['ERRORS']) ? $PayPalResult['ERRORS'] : array();
            Session::flash('errors', $errors);
            throw new PayPalException;
        }
    }

    /**
     * TransactionSearch API Request
     */
    public function transactionSearch($params)
    {
        // Prepare request arrays
        $start_date = isset($params['start_date']) ? $params['start_date'] : '';
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
            return $PayPalResult;
        }
        else
        {
            $errors = isset($PayPalResult['ERRORS']) ? $PayPalResult['ERRORS'] : array();
            Session::flash('errors', $errors);
            throw new PayPalException;
        }
    }

    /**
     * Return recent history via TransactionSearch
     *
     * @param int $days
     * @return array
     */
    public function getRecentHistory($days = 1)
    {
        $start_date = gmdate("Y-m-d\\TH:i:sZ",strtotime('now - ' . $days . ' days'));
        $params = array('start_date' => $start_date);
        $result = $this->transactionSearch($params);

        $search_results = isset($result['SEARCHRESULTS']) ? $result['SEARCHRESULTS'] : array();
        return $search_results;
    }

    /**
     * RefundTransaction API
     *
     * @param $params
     * @return array|bool
     */
    public function refundTransaction($params)
    {
        $transaction_id = isset($params['transaction_id']) ? $params['transaction_id'] : '';
        $payer_id = isset($params['payer_id']) ? $params['payer_id'] : '';
        $invoice_id = isset($params['invoice_id']) ? $params['invoice_id'] : '';
        $refund_type = isset($params['refund_type']) ? $params['refund_type'] : '';
        $amount = isset($params['amount']) ? $params['amount'] : '';
        $currency_code = isset($params['currency_code']) ? $params['currency_code'] : '';
        $note = isset($params['note']) ? $params['note'] : '';
        $retry_until = isset($params['retry_until']) ? $params['retry_until'] : '';
        $refund_source = isset($params['refund_source']) ? $params['refund_source'] : '';
        $merchant_store_detail = isset($params['merchant_store_detail']) ? $params['merchant_store_detail'] : '';
        $refund_advice = isset($params['refund_advice']) ? $params['refund_advice'] : '';
        $refund_item_details = isset($params['refund_item_details']) ? $params['refund_item_details'] : '';
        $msg_sub_id = isset($params['msg_sub_id']) ? $params['msg_sub_id'] : '';
        $store_id = isset($params['store_id']) ? $params['store_id'] : '';
        $terminal_id = isset($params['terminal_id']) ? $params['terminal_id'] : '';

        $RTFields = array(
            'transactionid' => $transaction_id, 							// Required.  PayPal transaction ID for the order you're refunding.
            'payerid' => $payer_id, 								// Encrypted PayPal customer account ID number.  Note:  Either transaction ID or payer ID must be specified.  127 char max
            'invoiceid' => $invoice_id, 								// Your own invoice tracking number.
            'refundtype' => $refund_type, 							// Required.  Type of refund.  Must be Full, Partial, or Other.
            'amt' => $amount, 									// Refund Amt.  Required if refund type is Partial.
            'currencycode' => $currency_code, 							// Three-letter currency code.  Required for Partial Refunds.  Do not use for full refunds.
            'note' => $note,  									// Custom memo about the refund.  255 char max.
            'retryuntil' => $retry_until, 							// Maximum time until you must retry the refund.  Note:  this field does not apply to point-of-sale transactions.
            'refundsource' => $refund_source, 							// Type of PayPal funding source (balance or eCheck) that can be used for auto refund.  Values are:  any, default, instant, eCheck
            'merchantstoredetail' => $merchant_store_detail, 					// Information about the merchant store.
            'refundadvice' => $refund_advice, 							// Flag to indicate that the buyer was already given store credit for a given transaction.  Values are:  1/0
            'refunditemdetails' => $refund_item_details, 						// Details about the individual items to be returned.
            'msgsubid' => $msg_sub_id, 								// A message ID used for idempotence to uniquely identify a message.
            'storeid' => $store_id, 								// ID of a merchant store.  This field is required for point-of-sale transactions.  50 char max.
            'terminalid' => $terminal_id								// ID of the terminal.  50 char max.
        );

        $PayPalRequestData = array('RTFields'=>$RTFields);

        // Pass data into class for processing with PayPal and load the response array into $PayPalResult
        $PayPalResult = $this->PayPal->RefundTransaction($PayPalRequestData);

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
            $errors = isset($PayPalResult['ERRORS']) ? $PayPalResult['ERRORS'] : array();
            Session::flash('errors', $errors);
            throw new PayPalException;
        }
    }
}

class PayPalException extends Exception {}