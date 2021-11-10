@extends('Layout.Layout')

@section('title', 'Dashboard')
@section('headers')
    <link rel="stylesheet" href={{ asset('css/dashboard.css') }}>
    <link rel="stylesheet" href={{ asset('css/course.css') }}>

    {{-- 
        simple customization to page layout    
    --}}
    <style>
        .header {
            background-color: rgba(0, 0, 0, 0.172);
        }
    </style>
@endsection
@section('body')
    <div class="cover">
        <h1>hello world</h1>
    </div>
    @dump($courses)
    <div class="bestsellers">
        <h4>best sellers</h4>
        <div class="slider-lane">
            @foreach ($best_sellers as $seller)
                <a class="seller slider-item" href="#">
                    @if ($seller->profilePicture)
                        <img class="avatar" src="{{ $seller->profilePicture->file_link }}" alt="avatar">
                    @else
                        <p class="avatar">{{ substr($seller->name, 0, 1) }}</p>
                    @endif
                    <h6>{{ $seller->name }}</h6>
                </a>
            @endforeach
        </div>
    </div>
    <div class="recommended">
        <h4>recommendation</h4>
        <div class="splide" id='course-slider'>
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach ($courses as $item)
                        <li class="splide__slide">
                            <x-course.card :course="$item" />
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src={{ asset('js/dashboard.js') }}></script>
@endsection
