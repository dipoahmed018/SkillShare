@extends('Layout.Layout')

@section('title', 'courses')
@section('headers')
    <link rel="stylesheet" href="{{ asset('css/course.css') }}">
@endsection
@section('body')
    @include('Layout.Header')
    {{-- @dump($data) --}}
    <div class="courses">
        @foreach ($data as $item)
            <a href="/show/course/{{ $item->id }}" style="text-decoration: none" class="course-card">
                    <div class="thumbnail">
                        @if ($item->thumblin)
                            <img src="{{ asset($item->thumblin->file_link) }}" alt="no image">
                        @endif
                    </div>
                    <div class="title">
                        <h4>{{ $item->title }}</h4>
                    </div>
                    <div class="details">
                        <span class="created_at">created at: {{ $item->created_at->diffForHumans() }}</span>

                        {{-- on javscript there will be a event on click for owner-link which will send usert to it's profile --}}
                        <span class="owner-link owner-name" data-user-id="{{$item->owner_details->id}}">
                            Author:{{ $item->owner_details->name }}
                        </span>
                        <span class="price">Price: {{ $item->price }} usd</span>

                        <span class="rate">
                            <span>Rate:</span>
                            <div class="rate-overlay">
                                <div class="rate-image" style="width:{{ $item->avg_rate * 10 }}%"></div>
                            </div>
                        </span>
                    </div>
            </a>
        @endforeach
    </div>
    <div class="links-wrapper">
        {{$data->links()}}
    </div>

@endsection

@section('scripts')
    @stack('menu')
    <script>
        const courses = @json($data);
        document.querySelectorAll('.owner-link').forEach((el) => {
            el.addEventListener('click', (e) => {
                e.preventDefault()
                location.href = `/profile/${e.target.getAttribute('data-user-id')}`
            })
        })
    </script>
@endsection
