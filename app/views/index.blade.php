@extends('layouts.default')

@section('page_level_css')
<link href="/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
@stop

@section('page_title') {{ Lang::get('page-names.dashboard') }} @stop
@section('page_name') {{ Lang::get('page-names.dashboard') }} @stop

@section('content')
<div class="row">
    <div class="col-md-2">
        <div class="panel panel-default">
            <div class="panel-heading">{{ Lang::get('panel-headers.current-balance') }}</div>
            <div class="panel-body">
                {{ Format::getCurrencyFormat($data['current_balance']) }}
            </div>
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
                    <table class="table table-striped table-bordered table-hover" id="recent_transactions">
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
                            <td class="center"><a href="/transaction/{{ $transaction['L_TRANSACTIONID'] }}">{{
                                    $transaction['L_TRANSACTIONID'] }}</a></td>
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
@if (Config::get('paypal.raw-api-dump'))
<div class="row">
    <div class="col-lg-12">
        <pre>
            {{ print_r($data['recent_history']) }}
        </pre>
    </div>
</div>
@endif
@stop

@section('page_level_js')
<script src="/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script>
    $(document).ready(function () {
        $('#recent_transactions').dataTable();
    });
</script>
@stop