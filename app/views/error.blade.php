@extends('layouts.default')

@section('page_title') {{ Lang::get('page-names.error') }} @stop
@section('page_name') {{ Lang::get('page-names.error') }} @stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">{{ Lang::get('panel-headers.error') }}</div>
            <div class="panel-body">
                {{ Lang::get('labels.code') }}: {{ Session::get('errors.0.L_ERRORCODE') }} <br />
                {{ Lang::get('labels.short-message') }}: {{ Session::get('errors.0.L_SHORTMESSAGE') }} <br />
                {{ Lang::get('labels.long-message') }}: {{ Session::get('errors.0.L_LONGMESSAGE') }} <br />
                {{ Lang::get('labels.severity-code') }}: {{ Session::get('errors.0.L_SEVERITYCODE') }} <br />
            </div>
        </div>
    </div>
</div>
@stop