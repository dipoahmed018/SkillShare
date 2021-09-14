@extends('Layout.Layout')

@section('title', 'courses')

@section('body')
    @include('Layout.Header')
        
    @dump($data);
@endsection

@section('scripts')
    <script>
        const courses = @json($data);
        console.log(courses);
    </script>
@endsection