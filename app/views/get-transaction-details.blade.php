@extends('layouts.default')

@section('page_level_css')
<link href="/css/plugins/social-buttons/social-buttons.css" rel="stylesheet">
@stop

@section('page_title') Transaction Details @stop

@section('page_name') Transaction Details @stop

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">Billing / Payment Information</div>
            <div class="panel-body">

                @if (isset($transaction_details['ORDERTIME']))
                    Date: {{ $transaction_details['ORDERTIME'] }} <br /><br />
                @endif

                @if (isset($transaction_details['FIRSTNAME']))
                    Name: {{ $transaction_details['FIRSTNAME'] }}

                    @if (isset($transaction_details['LASTNAME']))
                        {{ $transaction_details['LASTNAME'] }}<br />
                    @endif
                @endif

                @if (isset($transaction_details['EMAIL']))
                    Email: <a target="_blank" href="mailto:{{ $transaction_details['EMAIL'] }}">{{ $transaction_details['EMAIL'] }}</a> <br/>
                @endif

                @if (isset($transaction_details['PAYERSTATUS']))
                    Payer Status: {{ ucfirst($transaction_details['PAYERSTATUS']) }} <br />
                @endif

                <br />

                @if (isset($transaction_details['TRANSACTIONID']))
                    Transaction ID: {{ $transaction_details['TRANSACTIONID'] }}<br />
                @endif

                @if (isset($transaction_details['PARENTTRANSACTIONID']))
                    {{ $transaction_details['PARENTTRANSACTIONID'] }}<br />
                @endif

                @if (isset($transaction_details['INVNUM']))
                    {{ $transaction_details['INVNUM'] }} <br />
                @endif

                @if (isset($transaction_details['TRANSACTIONTYPE']))
                    Transaction Type: {{ ucfirst($transaction_details['TRANSACTIONTYPE']) }}
                @endif

                @if (isset($transaction_details['PAYMENTTYPE']))
                    ({{ $transaction_details['PAYMENTTYPE'] }})
                @endif

                <br />

                @if (isset($transaction_details['PAYMENTSTATUS']))
                    Payment Status: {{ $transaction_details['PAYMENTSTATUS'] }}
                @endif

                @if (isset($transaction_details['PAYMENTSTATUS']) && strtoupper($transaction_details['PAYMENTSTATUS']) == 'PENDING')
                    ({{ ucwords($transaction_details['PENDINGREASON']) }}

                    @if (strtoupper($transaction_details['REASONCODE']) != 'NONE'))
                        / {{ $transaction_details['REASONCODE'] }})
                    @else
                        )
                    @endif
                @endif

                <br />

                @if (isset($transaction_details['PROTECTIONELIGIBILITY']))
                    Seller Protection: {{ $transaction_details['PROTECTIONELIGIBILITY'] }}
                @endif

                @if (isset($transaction_details['PROTECTIONELIGIBILITYTYPE']) && strtoupper($transaction_details['PROTECTIONELIGIBILITYTYPE']) != 'NONE')
                    ({{ $transaction_details['PROTECTIONELIGIBILITYTYPE'] }})
                @endif

                <br /><br />

                @if (isset($transaction_details['RECEIVEREMAIL']))
                    Sent to: {{ $transaction_details['RECEIVEREMAIL'] }} <br />
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">Order Totals</div>
            <div class="panel-body">
                <div class="table-responsive table-bordered">
                    <table class="table">

                        @if (isset($transaction_details['SUBTOTAL']))
                        <tr>
                            <td>Subtotal</td>
                            <td>{{ number_format($transaction_details['SUBTOTAL'],2) }}</td>
                        </tr>
                        @endif

                        @if (isset($transaction_details['TAXAMT']))
                        <tr>
                            <td>Sales Tax:</td>
                            <td>@if (isset($transaction_details['TAXAMT'])) {{ number_format($transaction_details['TAXAMT'],2) }} @endif</td>
                        </tr>
                        @endif

                        @if (isset($transaction_details['SHIPPINGAMT']))
                        <tr>
                            <td>Shipping:</td>
                            <td>@if (isset($transaction_details['SHIPPINGAMT'])) {{ number_format($transaction_details['SHIPPINGAMT'],2) }} @endif</td>
                        </tr>
                        @endif

                        @if (isset($transaction_details['HANDLINGAMT']))
                        <tr>
                            <td>Handling:</td>
                            <td>@if (isset($transaction_details['HANDLINGAMT'])) {{ number_format($transaction_details['HANDLINGAMT'],2) }} @endif</td>
                        </tr>
                        @endif

                        @if (isset($transaction_details['AMT']))
                        <tr>
                            <td>Total (Fee):</td>
                            <td>{{ number_format($transaction_details['AMT'],2) }} (-{{ $transaction_details['FEEAMT'] or '0.00' }})</td>
                        </tr>
                        @endif

                        @if (isset($transaction_details['NETAMT']))
                        <tr>
                            <td>Net:</td>
                            <td>{{ number_format($transaction_details['NETAMT'],2) }}</td>
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
            <div class="panel-heading">Shipping Address ({{ $transaction_details['ADDRESSSTATUS'] }})</div>
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
            <div class="panel-heading">Actions</div>
            <div class="panel-body">
                @if (isset($transaction_details['PAYMENTSTATUS']) && strtoupper($transaction_details['PAYMENTSTATUS']) == 'COMPLETED')
                    <button type="button" class="btn btn-default">Refund</button>
                @endif

                @if (isset($transaction_details['PAYMENTSTATUS']) && strtoupper($transaction_details['PAYMENTSTATUS']) == 'PENDING'
                && strtoupper($transaction_details['PENDINGREASON']) == 'AUTHORIZATION')
                    <button type="button" class="btn btn-default">Capture</button>
                    <button type="button" class="btn btn-default">Void</button>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    @if (isset($transaction_details['ORDERITEMS']) && count($transaction_details['ORDERITEMS']) > 0)
    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">Shopping Cart Contents</div>
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
                            <td>{{ number_format($order_item['L_AMT'],2) }}</td>
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

<div class="row">
<?php
echo '<pre />';
print_r($transaction_details);
?>
</div>
@stop