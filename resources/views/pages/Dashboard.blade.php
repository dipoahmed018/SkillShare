@extends('../Layout/Layout')

@section('title', 'Dashboard')
@section('headers')
    <link rel="stylesheet" href={{ asset('css/dashboard.css') }}>
@endsection
@section('body')
    @include('Layout.Header')
    <div class="cover">
        <h1>hello world</h1>
    </div>
    <div class="bestsellers">
        <a class="seller" href="#">
            <img src="{{ asset('css/assets/background_cover.png') }}" alt="avatar">
            <h6>Dipo Ahmed</h6>
        </a>
        <a class="seller" href="#">
            <img src="{{ asset('css/assets/background_cover.png') }}" alt="avatar">
            <h6>Dipo Ahmed no worldndf</h6>
        </a>
        {{-- <a class="seller" href="#">
            <img src="{{ asset('css/assets/background_cover.png') }}" alt="avatar">
            <h6>Dipo Ahmed</h6>
        </a>
        <a class="seller" href="#">
            <img src="{{ asset('css/assets/background_cover.png') }}" alt="avatar">
            <h6>Dipo Ahmed</h6>
        </a>
        <a class="seller" href="#">
            <img src="{{ asset('css/assets/background_cover.png') }}" alt="avatar">
            <h6>Dipo Ahmed</h6>
        </a>
        <a class="seller" href="#">
            <img src="{{ asset('css/assets/background_cover.png') }}" alt="avatar">
            <h6>Dipo Ahmed</h6>
        </a> --}}
    </div>
@endsection

@section('scripts')
@stack('menu')
<script src={{ asset('js/dashboard.js') }}></script>
@endsection
