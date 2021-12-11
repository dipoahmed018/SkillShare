@extends('../Layout/Layout')
@section('title', 'show question')

@section('headers')
    <link rel="stylesheet" href="{{ asset('css/question.css') }}">
@endsection
@section('body')

    <x-modal title="Delete comment" id="delete-comment-confirmation" size="modal-sm">
        @slot('body')
            <x-confirmation title="Are you sure you want to delete this comment?" />
        @endslot
    </x-modal>

    <x-modal title="Edit Question" id="question-edit-modal" size="modal-lg">
        @slot('body')
            <form id="edit-question" action="{{ route('post.update', ['post' => $question->id]) }}" method="post">
                @csrf
                @method('put')
                <label for="question-title">Title</label>
                <input type="text" name="title" id="question-title" value="{{$question->title}}">
                <label for="question-edit-box">Question</label>
                <textarea name="content" id="question-edit-box" cols="30" rows="10">{!! $question->content !!}</textarea>
                <input id="answer-submit" type="submit" value="update question">
            </form>
        @endslot
    </x-modal>

    <x-modal title="Edit Answer" id="answer-edit-modal" size="modal-lg">
        @slot('body')
            <form id="edit-answer" method="post">
                @csrf
                @method('put')
                <textarea name="content" id="answer-edit-box" cols="30" rows="10"></textarea>
                <input id="answer-submit" type="submit" value="update answer">
            </form>
        @endslot
    </x-modal>

    <main>
        <section class="question-wrapper">
            <x-qna.card :post="$question" :user="$user" editModalTarget="question-edit-modal" />
        </section>
        <section class="answers-wrapper">
            <form id="create-answer"
                action="{{ route('post.create', ['postable' => $question->id, 'type' => 'answer']) }}" method="post">
                @csrf
                <textarea name="content" id="answer-create-box" cols="30" rows="10"></textarea>
                <button type="submit">Create answer</button>
            </form>
            <h6 class="answers-label">Answers</h6>
            <div class="answers">
                @foreach ($question->answers as $answer)
                    <x-qna.card :post="$answer" :user="$user" editModalTarget="answer-edit-modal" />
                @endforeach
            </div>
            <div class="links-wrapper">
                {{$question->answers->links()}}
            </div>
        </section>
    </main>

@endsection

@section('scripts')
    <script>
        const question = @json($question);
        const answers = @json($question->answers);
        const user = @json($user);
    </script>
    <script src="{{ asset('./js/ckeditor5/build/ckeditor.js') }}"></script>
    <script src="{{ asset('js/question_show.js') }}"></script>
    {{-- <script src="{{ asset('js/post_edit.js') }}"></script> --}}
@endsection
