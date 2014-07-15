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
     * @param string $amount
     * @param bool $symbol
     * @return string
     */
    public static function getCurrencyFormat($amount = '', $symbol = true)
    {
        if($symbol)
        {
            $currency_format_amount = Lang::get('currency.symbol') .
                number_format($amount,2,Lang::get('currency.decimal-separator'),Lang::get('currency.thousands-separator'));
        }
        else
        {
            $currency_format_amount = number_format($amount,2,Lang::get('currency.decimal-separator'),Lang::get('currency.thousands-separator'));
        }

        return $currency_format_amount;
    }

}