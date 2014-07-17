@extends('layouts.default')

@section('page_title') {{ Lang::get('page-names.refund') }} @stop
@section('page_name') {{ Lang::get('page-names.refund') }} @stop

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div>
            <p>You can refund all or part of your buyer's payment for 60 days after the buyer sent the original payment.
                When you refund a payment for goods or services, PayPal contributes the variable portion of the original
                transaction fee to the refund, and keeps the fixed-fee portion of that fee from your account.</p>

            <p>To issue a refund, enter the amount in the Refund Amount field and click Continue.</p>
        </div>
        <div>
            {{ Form::open(array('url' => '/transaction/'.$data['transaction_id'].'/refund')) }}

            <div>
                Transaction ID: {{ $data['transaction_id'] }}
            </div>

            <div>
                Original Payment: {{ Format::getCurrencyFormat($data['amount']) }}
                {{ Form::hidden('original_amount', $data['amount']) }}
            </div>

            <div>
                {{ Form::label('refund_amount', 'Refund Amount: ') }}
                {{ Form::text('refund_amount', $data['amount']) }}
            </div>

            <div>
                {{ Form::label('invoice_number', 'Invoice Number (optional): ') }}
                {{ Form::text('invoice_number') }}
            </div>

            <div>
                {{ Form::label('notes', 'Note to Buyer (optional): ') }}
                {{ Form::textarea('notes') }}
            </div>

            <div>
                {{ Form::submit('Process Refund') }}
                <a href="/transaction/{{ $data['transaction_id'] }}">
                    <button type="button" class="btn btn-default">{{ Lang::get('buttons.cancel') }}</button>
                </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
</div>
@stop