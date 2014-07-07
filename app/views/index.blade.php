@extends('layouts.default')

@section('page_title') PayPal Glass @stop
@section('page_name') Overview @stop

@section('content')
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">Current Balance</div>
        <div class="panel-body">{{ $data['current_balance'] }}</div>
    </div>
</div>
<div class="col-md-8">
<div class="panel panel-default">
<div class="panel-heading">
    Recent History
</div>
<!-- /.panel-heading -->
<div class="panel-body">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
        <tr>
            <th>Date</th>
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
        $net_amount = number_format($transaction['L_NETAMT'],2);
        $fee_amount = str_replace('-','',$transaction['L_FEEAMT']);
        $fee_amount = number_format($fee_amount,2);
        $gross_amount = number_format($net_amount + $fee_amount,2);
        ?>
        <tr class="odd gradeX">
            <td class="center">{{ $transaction['L_TIMESTAMP'] }}</td>
            <td class="center">{{ $transaction['L_TYPE'] }}</td>
            <td class="center">{{ $transaction['L_EMAIL'] }}</td>
            <td class="center">{{ $transaction['L_STATUS'] }}</td>
            <td class="center">{{ $gross_amount }}</td>
            <td class="center">-{{ $fee_amount }}</td>
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
@stop