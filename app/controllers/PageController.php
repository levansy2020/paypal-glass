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

    /**
	 * Home Page
	 */
	public function index()
	{
        // GetBalance
        $current_balance = $this->PayPal->getBalance();

        if(Session::has('errors'))
        {
            return Redirect::to('error');
        }

        // TransactionSearch
        $params = array(
            'number_of_days' => 1
        );
        $recent_history = $this->PayPal->transactionSearch($params);

        if(Session::has('errors'))
        {
            return Redirect::to('error');
        }

        // Make View
        $data = array('current_balance' => $current_balance, 'recent_history' => $recent_history);
        return View::make('index')->with('data', $data);
	}

    /**
     * Transaction Details
     */
    public function getTransactionDetails($transaction_id)
    {
        // GetTransactionDetails
        $transaction_details = $this->PayPal->getTransactionDetails($transaction_id);

        if(Session::has('errors'))
        {
            return Redirect::to('error');
        }

        return View::make('get-transaction-details')->with('transaction_details', $transaction_details);
    }

}
