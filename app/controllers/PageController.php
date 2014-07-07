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
	 * Display Overview page.
	 *
	 * @return Response
	 */
	public function index()
	{
        // GetBalance
        $current_balance = $this->PayPal->getBalance();

        // TransactionSearch
        $params = array(
            'number_of_days' => 1
        );
        $recent_history = $this->PayPal->transactionSearch($params);

        // Make View
        $data = array('current_balance' => $current_balance, 'recent_history' => $recent_history);
        return View::make('index')->with('data', $data);
	}


}
