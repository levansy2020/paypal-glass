@extends('layouts.default')

@section('page_level_css')
<link href="/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
@stop

@section('page_title') {{ Lang::get('page-names.transaction-history') }} @stop
@section('page_name') {{ Lang::get('page-names.transaction-history') }} @stop

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ Lang::get('panel-headers.date-range') }}
            </div>
            <div class="panel-body">
                {{ Form::open() }}
                From {{ Form::text('start_date', '', array('id'=>'start_date', 'size'=>'10')) }} to
                {{ Form::text('end_date', '', array('id'=>'end_date', 'size'=>'10')) }}
                {{ Form::submit('Get Date Range') }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ Lang::get('panel-headers.transactions') }}&nbsp;
                @if ($data['start_date'] != '')
                    ({{ $data['start_date']}} - {{ $data['end_date'] }})
                @endif
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="transaction_history">
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
                        @foreach ($data['transaction_history'] as $transaction)
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
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script src="/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script>
    $(document).ready(function () {
        $('#transaction_history').dataTable();
    });
</script>
<script>
    $(function () {
        $("#start_date").datepicker();
        $("#end_date").datepicker();
    });
</script>
@stop