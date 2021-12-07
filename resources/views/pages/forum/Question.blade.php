@extends('../Layout/Layout')
@section('title', 'show question')

@section('headers')
    <link rel="stylesheet" href="{{ asset('css/question.css') }}">
@endsection
@section('body')

    <x-modal title="Edit" id="edit-post-modal" size="modal-lg">
        @slot('body')
            <form id="edit-answer" action="{{ route('post.create', ['postable' => $question->id, 'type' => 'answer']) }}"
                method="post">
                @csrf
                <textarea name="content" id="answer-editor-box" cols="30" rows="10"></textarea>
                <input id="answer-submit" type="submit" value="create answer">
            </form>
        @endslot
    </x-modal>

    <x-modal title="Create answer" id="create-question-modal" size="modal-lg">
        @slot('body')
        {{-- <form action="{{ route('post.create', ['postable' => $question->id, 'type' => 'answer']) }}"
            id="create-answer" method="post">
            @csrf
            <label for="title">Title</label>
            <input class="form-control" type="text" name="title" id="title" required min="10"><br>
            <textarea name="content" id="question-input" cols="30" rows="10"></textarea>
            <button type="submit">Create</button>
        </form> --}}
        @endslot
    </x-modal>

    <main>
        <section class="question-wrapper">
            <x-qna.card :post="$question" :user="$user" editModalTarget="post-edit-modal" />
        </section>
        <section class="answers-wrapper">
            <form  id="create-answer" action="{{ route('post.create', ['postable' => $question->id, 'type' => 'answer']) }}"
                id="create-answer" method="post">
                @csrf
                <textarea name="content" id="answer-input" cols="30" rows="10"></textarea>
                <button type="submit">Create answer</button>
            </form>
            <h6 class="answers-label">Answers</h6>
            <div class="answers">
                @foreach ($question->answers as $answer)
                    <x-qna.card :post="$answer" :user="$user" editModalTarget="post-edit-modal" />
                @endforeach
            </div>
        </section>
    </main>
    {{-- <div class="container row">
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
        answer editor modal show button
        <button id="answer-create" data-bs-type="create-answer" class="btn btn-success">create answer</button>

        <div class="col col-12 answers-box">
            @foreach ($question->answers as $answer)
                <x-QNA-show :post="$answer"></x-answer-show>
            @endforeach
        </div>
    </div> --}}
@endsection

@section('scripts')
    <script>
        const question = @json($question);
        const answers = @json($question->answers);
        const user = @json($user);
    </script>
    <script src="{{ asset('./js/ckeditor5/build/ckeditor.js') }}"></script>
    <script src="{{ asset('js/question_show.js') }}"></script>
    <script src="{{ asset('js/post_edit.js') }}"></script>
@endsection
