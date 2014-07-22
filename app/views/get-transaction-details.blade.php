@extends('layouts.default')

@section('page_level_css')
<link href="/css/plugins/social-buttons/social-buttons.css" rel="stylesheet">
@stop

@section('page_title') {{ Lang::get('page-names.transaction-details') }} @stop

@section('page_name') {{ Lang::get('page-names.transaction-details') }} @stop

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">{{ Lang::get('panel-headers.billing') }}</div>
            <div class="panel-body">

                @if (isset($transaction_details['ORDERTIME']))
                    {{ Lang::get('labels.date') }}: {{ $transaction_details['ORDERTIME'] }} <br /><br />
                @endif

                @if (isset($transaction_details['FIRSTNAME']))
                    {{ Lang::get('labels.name') }}: {{ $transaction_details['FIRSTNAME'] }}

                    @if (isset($transaction_details['LASTNAME']))
                        {{ $transaction_details['LASTNAME'] }}<br />
                    @endif
                @endif

                @if (isset($transaction_details['EMAIL']))
                    {{ Lang::get('labels.email') }}: <a target="_blank" href="mailto:{{ $transaction_details['EMAIL'] }}">{{ $transaction_details['EMAIL'] }}</a> <br/>
                @endif

                @if (isset($transaction_details['PAYERSTATUS']))
                    {{ Lang::get('labels.payer-status') }}: {{ Lang::get('paypal.'.strtolower($transaction_details['PAYERSTATUS'])) }}<br />
                @endif

                <br />

                @if (isset($transaction_details['TRANSACTIONID']))
                    {{ Lang::get('labels.transaction-id') }}: {{ $transaction_details['TRANSACTIONID'] }}<br />
                @endif

                @if (isset($transaction_details['PARENTTRANSACTIONID']))
                    {{ Lang::get('labels.parent-transaction-id') }}:
                    <a href="/transaction/{{ $transaction_details['PARENTTRANSACTIONID'] }}">{{ $transaction_details['PARENTTRANSACTIONID'] }}</a><br />
                @endif

                @if (isset($transaction_details['INVNUM']))
                    {{ Lang::get('labels.invoice-id') }}: {{ $transaction_details['INVNUM'] }} <br />
                @endif

                @if (isset($transaction_details['TRANSACTIONTYPE']))
                    {{ Lang::get('labels.transaction-type') }}: {{ ucfirst(Lang::get('paypal.'.strtolower($transaction_details['TRANSACTIONTYPE']))) }}
                @endif

                @if (isset($transaction_details['PAYMENTTYPE']))
                    ({{ Lang::get('paypal.'.strtolower($transaction_details['PAYMENTTYPE'])) }})
                @endif

                <br />

                @if (isset($transaction_details['PAYMENTSTATUS']))
                    {{ Lang::get('labels.payment-status') }}: {{ Lang::get('paypal.'.strtolower($transaction_details['PAYMENTSTATUS'])) }}
                @endif

                @if (isset($transaction_details['PAYMENTSTATUS']) && strtoupper($transaction_details['PAYMENTSTATUS']) == 'PENDING')
                    ({{ Lang::get('paypal.'.$transaction_details['PENDINGREASON']) }}

                    @if (strtoupper($transaction_details['REASONCODE']) != 'NONE'))
                        / {{ $transaction_details['REASONCODE'] }})
                    @else
                        )
                    @endif
                @endif

                <br />

                @if (isset($transaction_details['PROTECTIONELIGIBILITY']))
                    {{ Lang::get('labels.seller-protection') }}: {{ Lang::get('paypal.'.strtolower($transaction_details['PROTECTIONELIGIBILITY'])) }}
                @endif

                @if (isset($transaction_details['PROTECTIONELIGIBILITYTYPE'])
                && strtoupper($transaction_details['PROTECTIONELIGIBILITYTYPE']) != 'NONE')
                    ({{ $transaction_details['PROTECTIONELIGIBILITYTYPE'] }})
                @endif

                <br /><br />

                @if (isset($transaction_details['RECEIVEREMAIL']))
                    {{ Lang::get('labels.sent-to') }}: {{ $transaction_details['RECEIVEREMAIL'] }} <br />
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">{{ Lang::get('panel-headers.order-totals') }}</div>
            <div class="panel-body">
                <div class="table-responsive table-bordered">
                    <table class="table">

                        @if (isset($transaction_details['SUBTOTAL']))
                        <tr>
                            <td>{{ Lang::get('labels.subtotal') }}</td>
                            <td>{{ Format::getCurrencyFormat($transaction_details['SUBTOTAL']) }}</td>
                        </tr>
                        @endif

                        @if (isset($transaction_details['TAXAMT']))
                        <tr>
                            <td>{{ Lang::get('labels.sales-tax') }}</td>
                            <td>{{ Format::getCurrencyFormat($transaction_details['TAXAMT']) }}</td>
                        </tr>
                        @endif

                        @if (isset($transaction_details['SHIPPINGAMT']))
                        <tr>
                            <td>{{ Lang::get('labels.shipping') }}</td>
                            <td>{{ Format::getCurrencyFormat($transaction_details['SHIPPINGAMT']) }}</td>
                        </tr>
                        @endif

                        @if (isset($transaction_details['HANDLINGAMT']))
                        <tr>
                            <td>{{ Lang::get('labels.handling') }}</td>
                            <td>{{ Format::getCurrencyFormat($transaction_details['HANDLINGAMT']) }}</td>
                        </tr>
                        @endif

                        @if (isset($transaction_details['AMT']))
                        <tr>
                            <td>
                                {{ Lang::get('labels.total') }}
                                @if(isset($transaction_details['FEEAMT']))
                                    ({{ Lang::get('labels.fee') }})
                                @endif
                            </td>
                            <td>
                                {{ Format::getCurrencyFormat($transaction_details['AMT']) }}
                                @if (isset($transaction_details['FEEAMT']))
                                    (-<span class="fee">{{ Format::getCurrencyFormat($transaction_details['FEEAMT']) }}</span>)
                                @endif
                            </td>
                        </tr>
                        @endif

                        @if (isset($transaction_details['NETAMT']))
                        <tr>
                            <td>{{ Lang::get('labels.net') }}</td>
                            <td>{{ Format::getCurrencyFormat($transaction_details['NETAMT']) }}</td>
                        </tr>
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if (isset($transaction_details['SHIPTONAME']))
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">{{ Lang::get('panel-headers.shipping-address') }} ({{ Lang::get('paypal.'.strtolower($transaction_details['ADDRESSSTATUS'])) }})</div>
            <div class="panel-body">
                {{ $transaction_details['SHIPTONAME'] }}<br />
                {{ $transaction_details['SHIPTOSTREET'] }}<br />
                {{ $transaction_details['SHIPTOCITY'] }}, {{ $transaction_details['SHIPTOSTATE'] }}  {{ $transaction_details['SHIPTOZIP'] }}<br />
                {{ $transaction_details['SHIPTOCOUNTRYNAME'] }}<br />
            </div>
        </div>
    </div>
    @endif
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">{{ Lang::get('panel-headers.actions') }}</div>
            <div class="panel-body">
                @if (isset($transaction_details['PAYMENTSTATUS'])
                && (strtoupper($transaction_details['PAYMENTSTATUS']) == 'COMPLETED'
                || strtoupper($transaction_details['PAYMENTSTATUS']) == 'PARTIALLYREFUNDED')
                && strtoupper($transaction_details['TRANSACTIONTYPE']) != 'SENDMONEY')
                    <a href="/transaction/{{ $transaction_details['TRANSACTIONID'] }}/refund?amount={{ $transaction_details['AMT'] }} ">
                    <button type="button" class="btn btn-default">{{ Lang::get('buttons.refund') }}</button>
                    </a>
                @endif

                @if (isset($transaction_details['PAYMENTSTATUS'])
                && strtoupper($transaction_details['PAYMENTSTATUS']) == 'PENDING'
                && strtoupper($transaction_details['PENDINGREASON']) == 'AUTHORIZATION')
                    <button type="button" class="btn btn-default">{{ Lang::get('buttons.capture') }}</button>
                    <button type="button" class="btn btn-default">{{ Lang::get('buttons.void') }}</button>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    @if (isset($transaction_details['ORDERITEMS']) && count($transaction_details['ORDERITEMS']) > 0)
    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">{{ Lang::get('panel-headers.shopping-cart-contents') }}</div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive table-bordered">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>QTY</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($transaction_details['ORDERITEMS'] as $order_item)
                        <tr>
                            <td>{{ $order_item['L_NUMBER'] }}</td>
                            <td>{{ $order_item['L_NAME'] }}</td>
                            <td>{{ $order_item['L_QTY'] }}</td>
                            @if ($order_item['L_AMT'] > 0)
                            <td>{{ Format::getCurrencyFormat($order_item['L_AMT']) }}</td>
                            @else
                            <td>&nbsp;</td>
                            @endif
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
    @endif
</div>
@if (Config::get('paypal.raw-api-dump'))
<div class="row">
    <div class="col-lg-12">
        <pre>
            {{ print_r($transaction_details) }}
        </pre>
    </div>
</div>
@endif
@stop