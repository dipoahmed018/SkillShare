@extends('../Layout/Layout')
@section('title', 'show question')
@section('body')
    <div class="modal fade" id="answer-editor" tabindex="-1" aria-hidden="true" data-bs-keyboard="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="editor-type" class="modal-title">create answer</h5>
                </div>
                <div class="modal-body">
                    <form id="edit-answer" action="{{ route('post.create', ['postable' => $question->id, 'type' => 'answer']) }}" method="post">
                        @csrf
                        <textarea name="content" id="answer-editor-box" cols="30" rows="10"></textarea>
                        <input id="answer-submit" type="submit" value="create answer">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container row">
        <div>
            <form class="form-group hide" action="{{ route('post.edit', ['post' => $question->id]) }}" method="post"
                id="edit-question">
                @csrf
                @method('put')
                <label for="question-title">title</label><br>
                <input class="form-control" type="text" name="title" id="question-title" value="{{ $question->title }}"><br>
                <textarea name="content" id="question-editor-box" cols="30" rows="10"></textarea>
                <input type="submit" value="update">
                <button id="preview-question-button">preview</button>
            </form>
        </div>
        <div class="col col-12" id="question-box">
            <x-QNA-show :post="$question"></x-question-show>
        </div>
        {{-- answer editor modal show button--}}
        <button id="answer-create" data-bs-type="create-answer" class="btn btn-success">create answer</button>

        <div class="col col-12 answers-box">
            @foreach ($question->answers as $answer)
                <x-QNA-show :post="$answer"></x-answer-show>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const question = @json($question);
        const answers = @json($question->answers);
    </script>
    <script src="{{ asset('./js/ckeditor5/build/ckeditor.js') }}"></script>
    <script src="{{ asset('js/post_edit.js') }}"></script>
@endsection
