@extends('Layout.Layout')

@section('title', 'buy course')
    
@section('body')
    @dump($intent)
@endsection

@section('scripts')
    <script src="{{ asset('js/transaction.js') }}"></script>
@endsection