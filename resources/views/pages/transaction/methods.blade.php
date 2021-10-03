@extends('Layout.Layout')

@section('title', 'buy course')

@section('body')
    <div class="container-fluid">
        <div class="row" >
            <div class="col-sm-12 col-md-6 bg-primary order-md-2">
                <div class="payment-wrapper" style="position: fixed">
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
                    <button class="btn btn-warning checkout" disabled>checkout</button>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 bg-primary order-md-1">
                <div class="row product-wrapper">
                    @if ($product->thumbnail)
                        <div class="col-12 mb-2">
                            <img style="max-width: 100%" src="{{ $product->thumbnail->file_link }}" alt="no thumbnail">
                        </div>
                    @endif
                    <h3 class="title mb-2">{{ $product->title }}</h3>
                    <p class="description">
                        {{ $product->description }}
                    </p>
                    <div class="addition-details">
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                        <p class="price">price: {{ $product->price }} usd</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const client_sc = @json($client_sc);
    </script>
    <script src="{{ asset('js/transaction.js') }}"></script>
@endsection
