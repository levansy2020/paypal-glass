@extends('layouts.default')

@section('page_title') PayPal Glass @stop
@section('page_name') Home @stop

@section('content')
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">Recent History</div>
        <div class="panel-body">
           @foreach ($data['recent_history'] as $transaction)
             <pre />
             {{ print_r($transaction) }}
           @endforeach
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">Current Balance</div>
        <div class="panel-body">{{ $data['current_balance'] }}</div>
    </div>
</div>
@stop