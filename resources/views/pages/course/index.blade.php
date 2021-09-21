@extends('Layout.Layout')

@section('title', 'courses')

@section('body')
    @include('Layout.Header')
    @foreach ($data as $item)
        <p>{{ $item->title }}</p>
        <span>{{ $item->price }}</span>
    @endforeach
    @foreach ($data as $item)
        <p>{{ $item->title }}</p>
        <span>{{ $item->price }}</span>
    @endforeach
    @foreach ($data as $item)
        <p>{{ $item->title }}</p>
        <span>{{ $item->price }}</span>
    @endforeach
    @foreach ($data as $item)
        <p>{{ $item->title }}</p>
        <span>{{ $item->price }}</span>
    @endforeach
    @foreach ($data as $item)
        <p>{{ $item->title }}</p>
        <span>{{ $item->price }}</span>
    @endforeach

@endsection

@section('scripts')
    @stack('header')
    <script>
        const courses = @json($data);
        console.log(courses);
    </script>
@endsection
