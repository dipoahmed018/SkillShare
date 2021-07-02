@extends('../Layout/Layout')
@section('title', 'show question')
@section('body')
    {{-- <div class="hello">
        <h3>{{$question->title}}</h3>
    </div> --}}
    @dump($question)
    @dump($answer)
@endsection

@section('scripts')

@endsection
