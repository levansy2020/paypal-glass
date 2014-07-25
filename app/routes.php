<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'PageController@index');
Route::get('/transaction/history', 'PageController@transactionHistory');
Route::get('/transaction/{transaction_id}', 'PageController@getTransactionDetails');
Route::get('/transaction/{transaction_id}/refund', 'PageController@refundTransaction');
Route::get('/error', 'PageController@error');

Route::group(array('before' => 'csrf'), function()
{
    Route::post('/transaction/history', 'PageController@transactionHistory');
    Route::post('/transaction/{transaction_id}/refund', 'PageController@refundTransaction');
});