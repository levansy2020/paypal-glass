<?php

class Format {

    public function __construct()
    {
        /**
         * Constructor
         */
    }

    /**
     * getCurrencyFormat()
     *
     * Returns the amount with localized formatting for currency.
     *
     * @param $amount
     * @return string
     */
    public static function getCurrencyFormat($amount)
    {
        $currency_format_amount = Lang::get('currency.symbol') .
            number_format($amount,2,Lang::get('currency.decimal-separator'),Lang::get('currency.thousands-separator'));

        return $currency_format_amount;
    }

}