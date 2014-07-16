<?php

class PageController extends \BaseController {

    protected $PayPal;

    /**
     * Constructor
     */
    public function __construct(PayPal $PayPal)
    {
        // PayPal Model Object
        $this->PayPal = $PayPal;
    }

    /**
     * Error Page
     */
    public function error()
    {
        return View::make('error');
    }

    public function errorCheck()
    {
        if(Session::has('errors'))
        {
            throw new Exception('paypal');
        }
    }

    /**
	 * Home Page
	 */
	public function index()
	{
        try
        {
            // GetBalance
            $current_balance = $this->PayPal->getBalance();
            $this->error();

            // TransactionSearch
            $params = array(
                'number_of_days' => 1
            );
            $recent_history = $this->PayPal->transactionSearch($params);
            $this->errorCheck();

            // Make View
            $data = array('current_balance' => $current_balance, 'recent_history' => $recent_history);
            return View::make('index')->with('data', $data);
        }
        catch(Exception $e)
        {
            return Redirect::to('error');
        }
	}

    /**
     * Transaction Details
     */
    public function getTransactionDetails($transaction_id)
    {
        try
        {
            // GetTransactionDetails
            $transaction_details = $this->PayPal->getTransactionDetails($transaction_id);
            return View::make('get-transaction-details')->with('transaction_details', $transaction_details);
        }
        catch(Exception $e)
        {
            return Redirect::to('error');
        }
    }

}
