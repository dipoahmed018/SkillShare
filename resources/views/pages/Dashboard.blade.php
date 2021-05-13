@extends('../Layout/Layout')

@section('title','Dashboard')   

@section('body')
    <h1>Dashboard</h1>
    @auth
        <h1>hello</h1>
    @endauth
@endsection