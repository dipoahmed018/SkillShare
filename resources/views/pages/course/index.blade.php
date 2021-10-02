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
            <x-course.card :course="$item"> </x-course.card>
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
