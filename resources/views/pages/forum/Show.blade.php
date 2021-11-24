@extends('../Layout/Layout')

@section('title', 'forum')

@section('headers')
    <link rel="stylesheet" href="{{ asset('css/forum.css') }}">
@endsection
{{-- @section('tamplates')
    @include('Layout.Tamplate.comment-tamplate')
@endsection --}}

@section('body')

    {{-- question deleter modal --}}
    <x-modal title="Delete Question" id="question-delete-modal">
        @slot('body')
            <x-confirmation title="Are you sure you want to delete this question" no="data-bs-dismiss=modal" />
        @endslot
    </x-modal>

    {{-- question creator modal --}}
    <x-modal title="Create question" id="create-question-modal" size="modal-lg">
        @slot('body')
            <form action="{{ route('post.create', ['postable' => $forum->id, 'type' => 'question']) }}" id="create-question"
                method="post">
                @csrf
                <label for="title">Title</label>
                <input class="form-control" type="text" name="title" id="title" required min="10"><br>
                <textarea name="question" id="question" cols="30" rows="10"></textarea>
                <button type="submit">Create</button>
            </form>
        @endslot
    </x-modal>

    {{-- post creation modal --}}
    <x-modal title="Create post" id="create-post-modal">
        @slot('body')
            <form class="forum-group hide" action="{{ route('post.create', ['postable' => $forum->id, 'type' => 'post']) }}"
                id="create-post" method="post">
                @csrf
                <label class="form-label" for="title">caption</label>
                <input class="form-control" type="text" name="title" id="title" required min="5">
                <div class="hide image-u-progress">
                    <p>image uploading plase wait</p>
                </div>
                <div class="image-u-box">
                    <label class="btn btn-primary" for="post-image">Upload image</label>
                    <input accept=".png, .jpeg, .jpg" type="file" class="one-click-upload" name="post-image" id="post-image"
                        multiple>
                </div>
                <input class="btn btn-success" type="submit" value="create">
            </form>
        @endslot
    </x-modal>


    <div id="popup_box" class="hide"></div>
    <div class="forum">
        <div class="banner">
            <img src={{ $forum->thumbnail?->file_link ?? asset('images/default_banner.jpg') }} alt="Forum thumbnail"
                style="max-width:100%">
        </div>
        <div class="forum-control">
            <button data-bs-toggle="modal" data-bs-target="#create-question-modal">Ask question</button>
            <button id="questions" data-event-target="questions-wrapper">Questions</button>
            <button id="students" data-event-target="students-wrapper">Students</button>
            <button id="about" data-event-target="about">About</button>
            @if ($forum->editable)
                <button><a href="/edit/forum/{{$forum->id}}">Edit</a></button>
            @endif
        </div>
        <div class="about hide">
            <h3 class="title">{{ $forum->name }}</h3>
            <div class="description">
                {!! $forum->description !!}
            </div>
        </div>
        <div class="questions-wrapper">
            <div class="questions">
                @foreach ($forum->questions as $question)
                    <x-forum.question.card :question="$question" />
                @endforeach
            </div>
            {{ $forum->questions->links() }}
        </div>
        <div class="students-wrapper hide">
            <div class="students">
                @foreach ($forum->students as $student)
                    <x-forum.studentCard :student="$student" />
                @endforeach
            </div>
            {{ $forum->students->links() }}
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/ckeditor5/build/ckeditor.js') }}"></script>
    <script>
        const forum = @json($forum);
    </script>
    <script src={{ asset('js/forum_show.js') }}></script>
@endsection
