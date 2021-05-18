@extends('../Layout/Layout')

@section('title','Dashboard')   

@section('body')
    <div id="popup_box">
        
    </div>
    <h1>Dashboard</h1>
    @auth
        <h1>hello</h1>
    @endauth
@endsection