@extends('layouts.default')

@section('page_title') Transaction Details @stop

@section('page_name') Transaction Details @stop

@section('content')
<?php
echo '<pre />';
print_r($transaction_details);
?>
@stop