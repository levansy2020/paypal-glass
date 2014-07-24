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
        if(Session::has('errors') && Session::get('errors.0.L_ERRORCODE'))
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
            $current_balance = $this->PayPal->getCurrentDefaultBalance();
            $this->errorCheck();

            // TransactionSearch
            $recent_history = $this->PayPal->getRecentHistory();
            $this->errorCheck();
        }
        catch(Exception $e)
        {
            return Redirect::to('error');
        }

        // Make View
        $data = array('current_balance' => $current_balance, 'recent_history' => $recent_history);
        return View::make('index')->with('data', $data);

	}

    /**
     * Transaction Details Page
     *
     * @param $transaction_id
     * @return mixed
     */
    public function getTransactionDetails($transaction_id)
    {

        try
        {
            // GetTransactionDetails
            $transaction_details = $this->PayPal->getTransactionDetails($transaction_id);
            $this->errorCheck();
        }
        catch(Exception $e)
        {
            return Redirect::to('error');
        }

        return View::make('get-transaction-details')->with('transaction_details', $transaction_details);

    }

    public function refundTransaction($transaction_id)
    {
        if(Request::isMethod('get'))
        {
            // Load refund view
            $amount = Input::get('amount');
            $data = array('transaction_id' => $transaction_id, 'amount' => $amount);
            return View::make('refund-transaction')->with('data', $data);
        }
        else
        {
            try
            {
                // Process Refund
                $refund_type = Input::get('refund_amount') < Input::get('original_amount') ? 'Partial' : 'Full';
                $params = array(
                    'transaction_id' => $transaction_id,
                    'original_amount' => Input::get('original_amount'),
                    'amount' => Input::get('refund_amount'),
                    'invoice_number' => Input::get('invoice_number'),
                    'notes' => Input::get('notes'),
                    'invoice_id' => Input::get('invoice_number'),
                    'refund_type' => $refund_type,
                );
                $refund = $this->PayPal->refundTransaction($params);
                $this->errorCheck();
            }
            catch(Exception $e)
            {
                return Redirect::to('error');
            }

            return Redirect::to('/');

        }
    }

    /**
     * Transaction History Page
     */
    public function transactionHistory()
    {
        if(Request::isMethod('get'))
        {
            try
            {
                // TransactionSearch
                $recent_history = $this->PayPal->getRecentHistory();
                $this->errorCheck();
            }
            catch(Exception $e)
            {
                return Redirect::to('error');
            }

            // Make View
            $data = array('recent_history' => $recent_history);
            return View::make('transaction-history')->with('data', $data);
        }
        else
        {

        }
    }

}