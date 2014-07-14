@extends('layouts.default')

@section('page_title') {{ Lang::get('page-names.overview') }} @stop
@section('page_name') {{ Lang::get('page-names.overview') }} @stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">{{ Lang::get('panel-headers.current-balance') }}</div>
            <div class="panel-body">{{ $data['current_balance'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">&nbsp;</div>
            <div class="panel-body">&nbsp;</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">&nbsp;</div>
            <div class="panel-body">&nbsp;</div>
        </div>
    </div>
</div>
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
                        <?php
                        /**
                         * @todo
                         * I put this here for now so I could quickly
                         * calculate the amounts, but this should probably
                         * be moved into the model and passed along so it's
                         * available here.
                         */
                        if(strtoupper($transaction['L_TYPE']) != 'REFUND')
                        {
                            $net_amount = number_format($transaction['L_NETAMT'],2);
                            $fee_amount = str_replace('-','',$transaction['L_FEEAMT']);
                            $fee_amount = '-'.number_format($fee_amount,2);
                            $gross_amount = number_format($transaction['L_AMT'],2);
                        }
                        else
                        {
                            $net_amount = number_format($transaction['L_NETAMT'],2);
                            $fee_amount = number_format($transaction['L_FEEAMT'],2);
                            $gross_amount = number_format($net_amount - $fee_amount,2);
                        }
                        ?>
                        <tr class="odd gradeX">
                            <td class="center">{{ date('m-d-Y',strtotime($transaction['L_TIMESTAMP'])) }}</td>
                            @if (strtoupper($transaction['L_TYPE']) != 'TEMPORARY HOLD'
                            && strtoupper($transaction['L_TYPE']) != 'MASS PAYMENT SENT'
                            && strtoupper($transaction['L_TYPE']) != 'FEE REVERSAL')
                            <td class="center"><a href="/transaction/{{ $transaction['L_TRANSACTIONID'] }}">{{ $transaction['L_TRANSACTIONID'] }}</a></td>
                            @else
                            <td class="center">{{ $transaction['L_TRANSACTIONID'] }}</td>
                            @endif
                            <td class="center">{{ $transaction['L_TYPE'] }}</td>
                            <td class="center">{{ $transaction['L_EMAIL'] }}</td>
                            <td class="center">{{ $transaction['L_STATUS'] }}</td>
                            <td class="center">{{ $gross_amount }}</td>
                            <td class="center">{{ $fee_amount }}</td>
                            <td class="center">{{ $net_amount }}</td>
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