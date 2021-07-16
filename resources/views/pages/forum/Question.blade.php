@extends('../Layout/Layout')
@section('title', 'show question')
@section('body')
    <div class="row">
        <div class="col col-12 question-box">
            <x-question-show :question="$question"></x-question-show>
        </div>
        <div class="col col-12 answers-box">
            @foreach ($answers as $answer)
                <x-answer-show :question="$question" :answer="$answer"></x-answer-show>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
    </script>
@endsection
