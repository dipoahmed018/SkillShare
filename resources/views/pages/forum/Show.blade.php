@extends('../Layout/Layout')

@section('title','forum')
@section('body')
@dump($forum)
@endsection

@section('scripts')
    <script src={{ asset('js/forum-show.js') }}></script>
@endsection