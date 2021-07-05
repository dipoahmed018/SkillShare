@extends('../Layout/Layout')

@section('title', 'forum')
@section('body')
    <div id="popup_box" class="hide">

    </div>
    <div class="modal fade" id="create" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="moda-type" class="modal-title">create</h5>
                    <button id="close-modal" type="button" class="btn-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="hide" action="{{ route('forum.post.create', ['forum' => $forum->id]) }}" id="create-post"
                        method="post">
                        @csrf
                        <input type="text" name="caption" id="caption">
                        <label for="image">Upload image</label>
                        <input accept=".png, .jpeg, .jpg*" type="file" name="image" id="image" multiple>
                        <input type="submit" value="create">
                    </form>
                    <form class="form-group" class="hide"
                        action="{{ route('forum.question.create', ['forum' => $forum->id]) }}" id="create-question"
                        method="post">
                        @csrf
                        <label for="title">Title</label>
                        <input class="form-control" type="text" name="title" id="title" required min="10"><br>
                        <textarea name="content" id="content" cols="30" rows="10"></textarea>
                        <input type="submit" value="create">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col col-md-6 col-sm-12  thumblins">
                <img src={{ $forum->forum_type->thumblin ? $forum->forum_type->thumblin->file_link : asset('default/Default.jpg') }}
                    alt="Forum Thumblin" style="max-width:100%">
            </div>
            <div class="d-block"></div>
            <div class="col m-2 col-sm-2 forum-details">
                <div class="details">
                    <h3 class="name">{{ $forum->name }}</h3>
                    <div>
                        {!!$forum->description ? $forum->description : ''!!}
                    </div>
                </div>
                @can('update', $forum)
                    <div class="edit">
                        <a href={{ route('edit.forum', ['forum' => $forum->id]) }} class="btn btn-warning">Edit</a>
                    </div>
                @endcan
            </div>
            <div class="d-block"></div>
            <div class="col posts">
                <div class="post-control d-flex justify-content-center gap-4">
                    <button id="create-post-button" class="btn btn-primary">create post</button>
                    <button id="create-question-button" class="btn btn-primary">create question</button>
                </div>
                <div class="col posts">
                    @foreach ($forum->posts as $post)

                    @endforeach
                </div>
                <div class="col questions">
                    @foreach ($forum->questions as $question)
                        <div class="wrapper row">
                            <div class="col align-self-center col-1 control">
                                <i>up</i>
                                <p>{{$question->vode}}</p>
                                <i>down</i>
                            </div>
                            <div class="col col-11 title">
                                <h3><a href="/show/question/{{$question->id}}">{{$question->title}}</a></h3>
                            </div>
                            <div class="d-block"></div>
                            <div class="col d-flex justify-content-end">
                                <button class="btn m-2 btn-warning">Edit</button>
                                <button class="btn m-2 btn-danger">delete</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/ckeditor5/build/ckeditor.js') }}"></script>
    <script>
        const forum = @json($forum);
    </script>
    <script src={{ asset('js/forum-show.js') }}></script>
@endsection
