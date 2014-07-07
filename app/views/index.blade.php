@extends('layouts.default')

@section('page_title') PayPal Glass @stop
@section('page_name') Home @stop

@section('content')
<div>Current Balance: {{ $data['current_balance'] }}</div>
@stop