@extends('layouts.default')

@section('page_title') {{ Lang::get('page-names.overview') }} @stop
@section('page_name') {{ Lang::get('page-names.overview') }} @stop

@section('first-row')

@stop

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ Lang::get('panel-headers.recent-transactions') }}
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Transaction ID</th>
                            <th>Type</th>
                            <th>Name / Email</th>
                            <th>Payment Status</th>
                            <th>Gross</th>
                            <th>Fee</th>
                            <th>Net</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['recent_history'] as $transaction)
                        <tr class="odd gradeX">
                            <td class="center">{{ date('m-d-Y',strtotime($transaction['L_TIMESTAMP'])) }}</td>
                            @if (strtoupper($transaction['L_TYPE']) != 'TEMPORARY HOLD'
                            && strtoupper($transaction['L_TYPE']) != 'MASS PAYMENT SENT'
                            && strtoupper($transaction['L_TYPE']) != 'FEE REVERSAL'
                            && strtoupper($transaction['L_TYPE']) != 'UNCLAIMED FUNDS RETURNED')
                            <td class="center"><a href="/transaction/{{ $transaction['L_TRANSACTIONID'] }}">{{ $transaction['L_TRANSACTIONID'] }}</a></td>
                            @else
                            <td class="center">{{ $transaction['L_TRANSACTIONID'] }}</td>
                            @endif
                            <td class="center">{{ $transaction['L_TYPE'] }}</td>
                            <td class="center">{{ $transaction['L_EMAIL'] }}</td>
                            <td class="center">{{ $transaction['L_STATUS'] }}</td>
                            <td class="center">{{ Format::getCurrencyFormat($transaction['L_AMT']) }}</td>
                            <td class="center">{{ Format::getCurrencyFormat($transaction['L_FEEAMT']) }}</td>
                            <td class="center">{{ Format::getCurrencyFormat($transaction['L_NETAMT']) }}</td>
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
</div>
<div class="row">
    <?php
    echo '<pre />';
    print_r($data['recent_history']);
    ?>
</div>
@stop