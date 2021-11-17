@extends('Layout.Layout')

@section('title', 'buy course')

@section('headers')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endsection

@section('body')
                <div class="payment-wrapper">
                    <div class="payment-methods">
                        <button class="card-payment btn btn-primary">card</button>
                        <button class="bank-payment btn btn-primary">Bank</button>
                    </div>
                    <div class="card-pay-box bg-white" style="width: 500px">
                    
                    </div>
                    <div class="bank-pay-box hide">
                        bank
                    </div>
                    <div class="error-box">

                    </div>
                    <button id="checkout" disabled>checkout</button>
                </div>
@endsection

@section('scripts')
    <script>
        const client_sc = @json($client_sc);
    </script>
    <script src="{{ asset('js/transaction.js') }}"></script>
@endsection
